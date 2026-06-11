<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Footer -->
<footer class="footer pt-0">
  <div class="row align-items-center justify-content-lg-between">
    <div class="col-lg-6">
      <div class="copyright text-center text-lg-left text-muted">
        &copy; <?= date('Y'); ?> <a href="#" class="font-weight-bold ml-1" target="_blank">PT. KARISMA INDOAGRO UNIVERSAL</a>
        <audio id="myAudio" autoplay muted>
          <source src="<?= base_url('assets/audio/') ?>order1.mp3" type="audio/mpeg">
          Your browser does not support the audio element.
        </audio>
        <audio id="beep__active" src="http://freesound.org/data/previews/263/263133_2064400-lq.mp3"></audio>
      </div>
    </div>
  </div>
</footer>
</div>
</div>

<!-- Argon Scripts -->
<!-- Core -->
<script src="<?php echo get_theme_uri('vendor/js-cookie/js.cookie.js', 'argon'); ?>"></script>
<script src="<?php echo get_theme_uri('vendor/jquery.scrollbar/jquery.scrollbar.min.js', 'argon'); ?>"></script>
<script src="<?php echo get_theme_uri('vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js', 'argon'); ?>"></script>

<!-- <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script> -->

<!-- Argon JS -->
<script src="<?php echo get_theme_uri('js/argon9f1e.js?v=1.1.0', 'argon'); ?>"></script>
<script src="<?php echo base_url('assets/js/') . 'selectrows.min.js'; ?>"></script>
<script>
  var base_url = "<?= base_url(); ?>"
</script>
<script type="text/javascript">
  var adminMessageAudioUnlocked = false;

  function requestAdminMessageNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission();
    }
  }

  function unlockAdminMessageAudio() {
    if (adminMessageAudioUnlocked || !$('#myAudio').length) {
      return;
    }

    $("#myAudio").prop('muted', true);
    var playPromise = $("#myAudio").get(0).play();

    if (playPromise && playPromise.then) {
      playPromise.then(function() {
        $("#myAudio").get(0).pause();
        $("#myAudio").get(0).currentTime = 0;
        $("#myAudio").prop('muted', false);
        adminMessageAudioUnlocked = true;
      }).catch(function() {
        $("#myAudio").prop('muted', false);
      });
    } else {
      $("#myAudio").prop('muted', false);
      adminMessageAudioUnlocked = true;
    }
  }

  function playAdminMessageSound() {
    if (!$('#myAudio').length) {
      return;
    }

    $("#myAudio").prop('muted', false);
    $("#myAudio").get(0).currentTime = 0;
    var playPromise = $("#myAudio").get(0).play();

    if (playPromise && playPromise.catch) {
      playPromise.catch(function() {});
    }
  }

  function showAdminMessageNotification(body, targetUrl) {
    playAdminMessageSound();

    if (!window.Notification) {
      return;
    }

    if (Notification.permission === 'granted') {
      const notify = new Notification('Perhatian - Karisma Online', {
        body: body || 'Chat Baru',
        icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
      });

      notify.onclick = (event) => {
        event.preventDefault();
        window.open(targetUrl || '<?php echo site_url('admin/messages'); ?>', '_blank');
      };
    } else if (Notification.permission === 'default') {
      Notification.requestPermission();
    }
  }

  $(document).ready(function() {
    $('#myTable').DataTable();
    selectRowsJs.init();

    $("#myAudio").prop('muted', true);
    $("#myAudio").get(0).play();
    $(document).scrollTop($(document).height());

    $(document).one('click touchstart keydown', function() {
      requestAdminMessageNotificationPermission();
      unlockAdminMessageAudio();
    });

    setInterval(function() {

      <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline') : ?>
        var unread_message = $("#unread_message").text();
        $.ajax({
          url: "<?= base_url() ?>admin/messages/get_unread",
          type: "POST",
          dataType: "json", //datatype lainnya: html, text
          data: {},
          success: function(data) {
            console.log('old order= ' + unread_message);
            console.log('new order= ' + data);
            // $("#myAudio").prop('muted', false);
            // $("#myAudio").delay(50).get(0).play();
            if (parseInt(data, 10) > parseInt(unread_message, 10)) {
              showAdminMessageNotification('Chat Baru', '<?php echo site_url('admin/messages'); ?>');
            }
            $("#unread_message").html(data);
          }
        });
      <?php endif; ?>

      <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline') : ?>
        var order_total = $("#order_total").text();
        $.ajax({
          url: "<?= base_url() ?>admin/orders/get_total_order",
          type: "POST",
          dataType: "json", //datatype lainnya: html, text
          data: {},
          success: function(data) {
            console.log('old chat= ' + order_total);
            console.log('new chat= ' + data);
            // $("#myAudio").prop('muted', false);
            // $("#myAudio").delay(50).get(0).play();
            if (order_total != 0) {
              if (order_total != data) {
                $("#myAudio").prop('muted', false);
                $("#myAudio").delay(50).get(0).play();

                if (!window.Notification) {
                  console.log('Browser does not support notifications.')
                } else {
                  if (Notification.permission === 'granted') {
                    const notify = new Notification('Perhatian', {
                      body: 'Pesanan Baru',
                      icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
                    })
                    notify.onclick = (event) => {
                      event.preventDefault();
                      window.open('https://os.youngpreneur.co.id/admin/orders', '_blank');
                    }
                  } else {
                    Notification.requestPermission()
                      .then(function(p) {
                        if (p === 'granted') {
                          const notify = new Notification('Perhatian!', {
                            body: 'Pesanan Baru',
                            icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
                          })
                          notify.onclick = (event) => {
                            event.preventDefault();
                            window.open('https://os.youngpreneur.co.id/admin/orders', '_blank');
                          }
                        } else {
                          console.log('User has blocked notifications.')
                        }
                      })
                      .catch(function(err) {
                        console.error(err)
                      })
                  }
                }

              }
            }
            $("#order_total").html(data);
          }
        });
      <?php endif; ?>

      <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
        var payment_total = $('#payment_total').text();
        $.ajax({
          url: "<?= base_url() ?>admin/payments/get_total_payment",
          type: "POST",
          dataType: "json", //datatype lainnya: html, text
          data: {},
          success: function(data) {
            console.log('old payment=' + payment_total);
            console.log('new payment=' + data);
            // $("#myAudio").prop('muted', false);
            // $("#myAudio").delay(50).get(0).play();

            if (payment_total != 0) {
              if (payment_total != data) {
                // $("#myAudio").prop('muted', false);
                // $("#myAudio").delay(50).get(0).play();

                if (!window.Notification) {
                  console.log('Browser does not support notifications.')
                } else {
                  if (Notification.permission === 'granted') {
                    const notify = new Notification('Perhatian', {
                      body: 'Pembayaran Baru',
                      icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
                    })
                    notify.onclick = (event) => {
                      event.preventDefault();
                      window.open('https://os.youngpreneur.co.id/admin/payments', '_blank');
                    }
                  } else {
                    Notification.requestPermission()
                      .then(function(p) {
                        if (p === 'granted') {
                          const notify = new Notification('Perhatian', {
                            body: 'Pembayaran Baru',
                            icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
                          })
                          notify.onclick = (event) => {
                            event.preventDefault();
                            window.open('https://os.youngpreneur.co.id/admin/payments', '_blank');
                          }
                        } else {
                          console.log('User has blocked notifications.')
                        }
                      })
                      .catch(function(err) {
                        console.error(err)
                      })
                  }
                }
              }
            }
            $("#payment_total").html(data);
          }
        });
      <?php endif; ?>
    }, 2000); //default 2000 = 2s

    $('#FormLaporan').submit(function(e) {
      e.preventDefault();

      let val = $('#bulan').val();
      const tanggal = val.split("-");

      let Tahun = tanggal[0];
      let Bulan = tanggal[1];
      if (val == '') {
        Swal.fire({
          title: 'Oops!',
          html: 'Bulan harus diisi',
          icon: 'error',
          confirmButtonText: 'Ok'
        });
      } else {
        var URL = "<?php echo site_url('admin/rating/tabel'); ?>/" + Bulan + "/" + Tahun;
        $('#result').load(URL);
      }
    });

    $('#FormLaporan1').submit(function(e) {
      e.preventDefault();

      let val = $('#bulan').val();
      const tanggal = val.split("-");

      let Tahun = tanggal[0];
      let Bulan = tanggal[1];
      if (val == '') {
        Swal.fire({
          title: 'Oops!',
          html: 'Bulan harus diisi',
          icon: 'error',
          confirmButtonText: 'Ok'
        });
      } else {
        var URL = "<?php echo site_url('admin/report/tabel'); ?>/" + Bulan + "/" + Tahun;
        $('#result').load(URL);
      }
    });

  });

  $('#text_message').keypress(function(e) {
    if (e.which == 13) {
      $('form#message_form').submit();
      return false;
    }
  });

  function escapeHtml(value) {
    return $('<div>').text(value || '').html();
  }

  function scrollAdminChatToBottom() {
    if ($('#chat_wrapper').length) {
      $('#chat_wrapper').animate({
        scrollTop: $('#chat_wrapper').get(0).scrollHeight
      }, 300);
    }
  }

  function renderAdminChatMessage(message, shouldNotify) {
    var messageId = parseInt(message.id || 0, 10);

    if (messageId > 0 && $('#chat_wrapper [data-message-id="' + messageId + '"]').length) {
      return;
    }

    var text = escapeHtml(message.message);
    var time = escapeHtml(message.created_at_formatted || format_tanggal());
    var chat = '';

    if (parseInt(message.chat_from, 10) === 1) {
      chat =
        '<div class="chat-message-right pb-4" data-message-id="' + messageId + '">' +
        '<div>' +
        '<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Admin" width="40" height="40">' +
        '</div>' +
        '<div class="chat-text">' +
        '<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">' +
        '<div class="font-weight-bold mb-1">Anda</div>' +
        text +
        '</div>' +
        '<div class="datetime text-muted small text-nowrap mt-2">' + time + '</div>' +
        '</div>' +
        '</div>';
    } else {
      chat =
        '<div class="chat-message-left pb-4" data-message-id="' + messageId + '">' +
        '<div>' +
        '<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Customer" width="40" height="40">' +
        '</div>' +
        '<div class="chat-text">' +
        '<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3 rel">' +
        '<div class="font-weight-bold mb-1">' + escapeHtml(message.name || 'Customer') + '</div>' +
        text +
        '</div>' +
        '<div class="datetime text-muted small text-nowrap mt-2 rel">' + time + '</div>' +
        '</div>' +
        '</div>';
    }

    $('#chat_wrapper').append(chat);

    if (messageId > (window.adminChatLastMessageId || 0)) {
      window.adminChatLastMessageId = messageId;
    }

    scrollAdminChatToBottom();

    if (shouldNotify && parseInt(message.chat_from, 10) === 2) {
      showAdminMessageNotification(
        (message.name ? message.name + ': ' : '') + (message.message || 'Chat Baru'),
        '<?php echo site_url('admin/messages'); ?>?user_id=' + (window.adminChatCustomerId || '')
      );
    }
  }

  function send_message() {
    var message = $.trim($("#text_message").val());

    if (message === '') {
      return;
    }

    $.ajax({
      url: base_url + 'send_admin_message',
      type: 'POST',
      dataType: 'json',
      data: $('#message_form').serialize(),
      success: function(data) {
        if (data.error) {
          return;
        }

        $("#text_message").val('');
        renderAdminChatMessage(data.data || {
          id: 0,
          message: message,
          chat_from: 1,
          created_at_formatted: format_tanggal()
        }, false);

      },
      error: function(data) {
        console.log(data + ' errrrr');
      },
    });
  }

  function fetchAdminMessages() {
    if (!$('#chat_wrapper').length || !window.adminChatCustomerId) {
      return;
    }

    $.ajax({
      url: base_url + 'admin/messages/fetch',
      type: 'GET',
      dataType: 'json',
      data: {
        customer_id: window.adminChatCustomerId,
        last_id: window.adminChatLastMessageId || 0
      },
      success: function(res) {
        if (!res.error && res.data && res.data.length) {
          $.each(res.data, function(_, message) {
            renderAdminChatMessage(message, true);
          });
        }
      }
    });
  }

  if ($('#chat_wrapper').length && window.adminChatCustomerId) {
    setInterval(fetchAdminMessages, 3000);
  }
</script>
</body>

</html>
