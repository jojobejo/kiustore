<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- jquery 3.6.0 -->
<script src="<?php echo get_theme_uri('js/jquery-3.6.0.min.js');?>"></script>

<!-- Bootstrap Js -->
<script src="<?php echo get_theme_uri('js/bootstrap.bundle.min.js');?>"></script>

<!-- Slick Slider js -->
<script src="<?php echo get_theme_uri('js/slick.js');?>"></script>
<script src="<?php echo get_theme_uri('js/slick.min.js');?>"></script>
<script src="<?php echo get_theme_uri('js/slick-custom.js');?>"></script>

<!-- Feather Icon -->
<script src="<?php echo get_theme_uri('js/feather.min.js');?>"></script>

<!-- Theme Setting js -->
<!-- <script src="<?php echo get_theme_uri('js/theme-setting.js');?>"></script> -->

<!-- Script js -->
<script src="<?php echo get_theme_uri('js/script.js');?>"></script>
<script src="<?php echo site_url('assets/js/helper.js');?>"></script>
<audio id="message_notification_audio" src="<?php echo base_url('assets/audio/order2.mp3'); ?>" preload="auto"></audio>
<script>var base_url = "<?=base_url();?>"</script>
<script>
    const element = document.getElementById("chat_body");
    var customerMessageAudioUnlocked = false;

    if (element) {
      element.scrollIntoView(false);
    }

    function requestMessageNotificationPermission() {
      if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
      }
    }

    function unlockCustomerMessageAudio() {
      if (customerMessageAudioUnlocked) {
        return;
      }

      var audio = document.getElementById('message_notification_audio');
      if (!audio) {
        return;
      }

      audio.muted = true;
      var playPromise = audio.play();

      if (playPromise && playPromise.then) {
        playPromise.then(function() {
          audio.pause();
          audio.currentTime = 0;
          audio.muted = false;
          customerMessageAudioUnlocked = true;
        }).catch(function() {
          audio.muted = false;
        });
      } else {
        audio.muted = false;
        customerMessageAudioUnlocked = true;
      }
    }

    $(document).one('click touchstart keydown', function() {
      requestMessageNotificationPermission();
      unlockCustomerMessageAudio();
    });

    $('#pesan').keypress(function (e) {
      if (e.which == 13) {
        $('form#send_message').submit();
        return false;    
      }
    });

    function escapeHtml(value) {
      return $('<div>').text(value || '').html();
    }

    function scrollChatToBottom() {
      if (element) {
        element.scrollIntoView(false);
      }
    }

    function playCustomerMessageSound() {
      var audio = document.getElementById('message_notification_audio');
      if (!audio) {
        return;
      }

      audio.muted = false;
      audio.currentTime = 0;
      var playPromise = audio.play();

      if (playPromise && playPromise.catch) {
        playPromise.catch(function() {});
      }
    }

    function showCustomerMessageNotification(message) {
      playCustomerMessageSound();

      if (!('Notification' in window)) {
        return;
      }

      if (Notification.permission === 'granted') {
        var notify = new Notification('Pesan Baru - Karisma Online', {
          body: message.message || 'Anda menerima pesan baru',
          icon: '<?php echo site_url('assets/images/favicon.png'); ?>'
        });

        notify.onclick = function(event) {
          event.preventDefault();
          window.focus();
        };
      } else if (Notification.permission === 'default') {
        Notification.requestPermission();
      }
    }

    function renderCustomerChatMessage(message, shouldNotify) {
      var messageId = parseInt(message.id || 0, 10);

      if (messageId > 0 && $('#chat_wrapper [data-message-id="' + messageId + '"]').length) {
        return;
      }

      var text = escapeHtml(message.message);
      var time = escapeHtml(message.created_at_formatted || format_tanggal());
      var chat = '';

      if (parseInt(message.chat_from, 10) === 1) {
        chat =
          '<div class="agent-message-content d-flex align-items-start" data-message-id="' + messageId + '">' +
          '<div class="agent-thumbnail me-2 mt-2"><img src="<?php echo get_user_image(); ?>" alt=""></div>' +
          '<div class="agent-message-text">' +
          '<div class="d-block"><p>' + text + '</p></div><span>' + time + '</span>' +
          '</div>' +
          '</div>';
      } else {
        chat =
          '<div class="user-message-content" data-message-id="' + messageId + '">' +
          '<div class="user-message-text">' +
          '<div class="d-block"><p>' + text + '</p></div><span>' + time + '</span>' +
          '</div>' +
          '</div>';
      }

      $('#chat_wrapper').append(chat);

      if (messageId > (window.customerChatLastMessageId || 0)) {
        window.customerChatLastMessageId = messageId;
      }

      scrollChatToBottom();

      if (shouldNotify && parseInt(message.chat_from, 10) === 1) {
        showCustomerMessageNotification(message);
      }
    }

    function send_message() {
          var message = $.trim($("#pesan").val());

          if (message === '') {
            return;
          }

          $.ajax({
          url: base_url + 'send_message',
          type: 'POST',
          dataType: 'json',
          data: {
            message: message
          },
          success: function(data) {
            if (data.error) {
              return;
            }

            $("#pesan").val('');
            if (window.resizeCustomerMessageTextarea) {
              window.resizeCustomerMessageTextarea();
            }
            renderCustomerChatMessage(data.data || {
              id: 0,
              message: message,
              chat_from: 2,
              created_at_formatted: format_tanggal()
            }, false);
          },
          error: function(data) {
            console.log(data);
          },
        });
      }

    function fetchCustomerMessages() {
      if (!$('#chat_wrapper').length) {
        return;
      }

      $.ajax({
        url: base_url + 'message/fetch',
        type: 'GET',
        dataType: 'json',
        data: {
          last_id: window.customerChatLastMessageId || 0
        },
        success: function(res) {
          if (!res.error && res.data && res.data.length) {
            $.each(res.data, function(_, message) {
              renderCustomerChatMessage(message, true);
            });
          }
        }
      });
    }

    if ($('#chat_wrapper').length) {
      setInterval(fetchCustomerMessages, 3000);
    }
</script>
</body>
</html>

