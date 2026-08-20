<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- Header Start -->
<header class="header header-chat">
  <div class="logo-wrap">
    <a href="<?= base_url(); ?>"><i class="iconly-Arrow-Left-Square icli"></i></a>
    <h1 class="title-color font-md"> Pesan </h1>
  </div>
  <div class="avatar-wrap">
    <a href="<?= base_url(); ?>">
      <i class="iconly-Home icli"></i>
    </a>
  </div>
</header>
<!-- Header End -->
<!-- Main Start -->
<main class="chat-page">
  <div id="chat_body">
    <!-- Live Chat Intro-->
    <div class="live-chat-intro">
      <p><?= get_current_salesman_name(); ?></p>
      <img src="<?php echo get_user_image(); ?>" alt="">
      <!-- Use this code for "Offline" Status-->
      <!-- .status.offline We’ll be back soon-->
    </div>
    <!-- Support Wrapper-->
    <div class="support-wrapper py-3">
      <div class="container">
        <!-- Live Chat Wrapper-->
        <div class="live-chat-wrapper" id="chat_wrapper">
          <!-- Agent Message Content-->
          <?php $last_message_id = 0; ?>
          <?php foreach ($message as $data) {
            $last_message_id = max($last_message_id, (int) $data->id);
            if ($data->chat_from == 1) { ?>
              <div class="agent-message-content d-flex align-items-start" data-message-id="<?= (int) $data->id ?>">
                <div class="agent-thumbnail me-2 mt-2"><img src="<?php echo get_user_image(); ?>" alt=""></div>
                <div class="agent-message-text">
                  <div class="d-block">
                    <p><?= html_escape($data->message) ?></p>
                  </div><span><?= date('d-m-Y H:i', strtotime($data->created_at)) ?></span>
                </div>
              </div>
            <?php } else { ?>
              <div class="user-message-content" data-message-id="<?= (int) $data->id ?>">
                <div class="user-message-text">
                  <div class="d-block">
                    <p><?= html_escape($data->message) ?></p>
                  </div><span><?= date('d-m-Y H:i', strtotime($data->created_at)) ?></span>
                </div>
              </div>
          <?php }
          } ?>
        </div>
      </div>
    </div>

  </div>

  <!-- Type Message Form-->
  <div class="type-text-form">
    <form action="#" id="send_message" onsubmit="send_message();return false">
      <div class="form-group file-upload mb-0">
        <input type="file"><i class="lni lni-plus"></i>
      </div>
      <textarea class="form-control" name="message" cols="30" rows="1" placeholder="Ketik pesan anda" id="pesan"></textarea>
      <button type="submit">
        <svg class="bi bi-arrow-right" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
        </svg>
      </button>
    </form>
  </div>
</main>
<!-- Main End -->

<script>
window.customerChatLastMessageId = <?= (int) $last_message_id ?>;

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const textarea = document.getElementById('pesan');

    if (!textarea) {
        return;
    }

    if (urlParams.has('prefill_message')) {
        textarea.value = urlParams.get('prefill_message') || '';
    } else if (urlParams.has('auto_text')) {
        textarea.value = "Halo Admin! , Mohon informasi terkait ekspedisi dan biaya ongkir dong.";
    }

    if (textarea.value !== '') {
        textarea.focus();
    }
});

(function() {
    const maxTextareaHeight = 120;

    window.resizeCustomerMessageTextarea = function() {
        const textarea = document.getElementById('pesan');
        if (!textarea) {
            return;
        }

        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, maxTextareaHeight) + 'px';
    };

    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('pesan');
        if (!textarea) {
            return;
        }

        textarea.addEventListener('input', window.resizeCustomerMessageTextarea);
        window.resizeCustomerMessageTextarea();
    });
})();
</script>
