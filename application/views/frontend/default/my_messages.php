<?php
    $instructor_list = $this->user_model->get_instructor_list()->result_array();
?>
<section class="page-header-area my-course-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="page-title"><?php echo get_phrase('my_courses'); ?></h1>
                <ul>
                  <li><a href="<?php echo site_url('home/my_courses'); ?>"><?php echo get_phrase('all_courses'); ?></a></li>
                  <li><a href="<?php echo site_url('home/my_wishlist'); ?>"><?php echo get_phrase('wishlists'); ?></a></li>
                  <li class="active"><a href="<?php echo site_url('home/my_messages'); ?>"><?php echo get_phrase('my_messages'); ?></a></li>
                  <li><a href="<?php echo site_url('home/purchase_history'); ?>"><?php echo get_phrase('purchase_history'); ?></a></li>
                  <li><a href="<?php echo site_url('home/profile/user_profile'); ?>"><?php echo get_phrase('user_profile'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>


<section class="message-area">
    <div class="container">
        <div class="row no-gutters align-items-stretch">
            <div class="col-lg-5">
                <div class="message-sender-list-box">
                    <button class="btn compose-btn" type="button" id="NewMessage" onclick="NewMessage(event)">Compose</button>
                    <hr>
                    <ul class="message-sender-list">

                        <?php
                        $current_user = $this->session->userdata('user_id');
                        $this->db->where('sender', $current_user);
                        $this->db->or_where('receiver', $current_user);
                        $message_threads = $this->db->get('message_thread')->result_array();
                        foreach ($message_threads as $row):

                            // defining the user to show
                            if ($row['sender'] == $current_user)
                            $user_to_show_id = $row['receiver'];
                            if ($row['receiver'] == $current_user)
                            $user_to_show_id = $row['sender'];

                            $last_messages_details =  $this->crud_model->get_last_message_by_message_thread_code($row['message_thread_code'])->row_array();
                            ?>
                            <a href="<?php echo site_url('home/my_messages/read_message/'.$row['message_thread_code']); ?>">
                                <li>
                                    <div class="message-sender-wrap">
                                        <div class="message-sender-head clearfix">
                                            <div class="message-sender-info d-inline-block">
                                                <div class="sender-image d-inline-block">
                                                    <img src="<?php echo $this->user_model->get_user_image_url($user_to_show_id);?>" alt="" class="img-fluid">
                                                </div>
                                                <div class="sender-name d-inline-block">
                                                    <?php
                                                    $user_to_show_details = $this->user_model->get_all_user($user_to_show_id)->row_array();
                                                    echo $user_to_show_details['first_name'].' '.$user_to_show_details['last_name'];
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="message-time d-inline-block float-right"><?php echo date('D, d-M-Y', $last_messages_details['timestamp']); ?></div>
                                        </div>
                                        <div class="message-sender-body">
                                            <?php echo $last_messages_details['message']; ?>
                                        </div>
                                    </div>
                                </li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="message-details-box" id = "toggle-1">
                    <?php include 'inner_messages.php'; ?>
                </div>
                <div class="message-details-box" id = "toggle-2" style="display: none;">
                    <div class="new-message-details"><div class="message-header">
                        <div class="sender-info">
                            <span class="d-inline-block">
                                <i class="far fa-user"></i>
                            </span>
                            <span class="d-inline-block"><?php echo get_phrase('new_message'); ?></span>
                        </div>
                    </div>
                    <form class="" action="<?php echo site_url('home/my_messages/send_new'); ?>" method="post">
                        <div class="message-body">
                            <div class="form-group">
                                <select class="form-control select2" name="receiver">
<!--                                     <optgroup label="<?php echo get_phrase('instructors'); ?>">
                                        <?php
                                        $instructor_list = $this->user_model->get_instructor_list()->result_array();
                                        foreach ($instructor_list as $instructor):
                                            if ($instructor['id'] == $this->session->userdata('user_id')) continue;
                                        ?>
                                            <option value="<?php echo $instructor['id']; ?>">
                                                <?php echo $instructor['first_name'].' '.$instructor['last_name']; ?> (Admin/Instructor)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup> -->

                                    <optgroup label="Quan ly">
                                        <?php
                                        // Lấy tất cả user có role_id = 3
                                        $this->db->where('role_id', 3);
                                        $mods = $this->db->get('users')->result_array();
                                        foreach ($mods as $mod):
                                            if ($mod['id'] == $this->session->userdata('user_id')) continue;
                                        ?>
                                            <option value="<?php echo $mod['id']; ?>">
                                                <?php echo $mod['first_name'].' '.$mod['last_name']; ?> (Mod)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea name="message" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn send-btn"><?php echo get_phrase('send'); ?></button>
                            <button type="button" class="btn cancel-btn" onclick = "CancelNewMessage(event)">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script type="text/javascript">
function NewMessage(e){

    e.preventDefault();
    $('#toggle-1').hide();
    $('#toggle-2').show();
    $('#NewMessage').removeAttr('onclick');
}

function CancelNewMessage(e){

    e.preventDefault();
    $('#toggle-2').hide();
    $('#toggle-1').show();

    $('#NewMessage').attr('onclick','NewMessage(event)');
}
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // Biến lưu mã cuộc trò chuyện hiện tại (Lấy từ PHP nếu đang mở tin nhắn)
        var current_thread_code = "<?php echo isset($message_thread_code) ? $message_thread_code : ''; ?>";

        // 1. Hàm cuộn xuống dưới cùng
        function scrollToBottom() {
            // Tìm khung chứa tin nhắn (Class này có thể khác tùy theme, hãy kiểm tra lại bằng F12)
            var msgContainer = $('.message-details'); 
            if(msgContainer.length > 0) {
                // Cuộn xuống
                $('html, body').animate({
                    scrollTop: msgContainer.offset().top + msgContainer.height()
                }, 100);
            }
        }

        // Chạy cuộn trang khi mới vào
        if (current_thread_code != "") {
            scrollToBottom();
        }

        // 2. Chức năng Real-time giả lập (Polling 3 giây)
        if (current_thread_code != "") {
            setInterval(function() {
            var current_thread_code = "<?php echo $current_message_thread_code; ?>";
            $.ajax({
                url: "<?php echo site_url('home/my_messages/read_message/'); ?>" + current_thread_code,
                success: function(response) {
                    // 1. Lấy nội dung tin nhắn mới
                    var newMessages = $(response).find('.conversation-list').html();
                    
                    // 2. Cập nhật vào khung chat
                    var chatList = $('.conversation-list');
                    chatList.html(newMessages);
                    
                    // 3. QUAN TRỌNG: Cuộn xuống dưới cùng ngay lập tức
                    if(chatList.length > 0) {
                        chatList.scrollTop(chatList[0].scrollHeight);
                    }
                }
            });
        }, 3000);
        }
    });
</script>