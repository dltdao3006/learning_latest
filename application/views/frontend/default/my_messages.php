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
                <div class="message-details-box" id="toggle-1">
                    <?php include 'inner_messages.php'; ?>
                </div>
                <div class="message-details-box" id="toggle-2" style="display: none;">
                    <div class="new-message-details">
                        <div class="message-header">
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
                                        <optgroup label="Quan ly">
                                            <?php
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
                                <button type="button" class="btn cancel-btn" onclick="CancelNewMessage(event)">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    // Biến lưu mã cuộc trò chuyện hiện tại
    var current_thread_code = "<?php echo isset($message_thread_code) ? $message_thread_code : ''; ?>";

    function NewMessage(e){
        e.preventDefault();
        $('#toggle-1').hide();
        $('#toggle-2').show();
        $('#NewMessage').attr('onclick', 'CancelNewMessage(event)');
        $('#NewMessage').text('Cancel');
    }

    function CancelNewMessage(e){
        e.preventDefault();
        $('#toggle-2').hide();
        $('#toggle-1').show();
        $('#NewMessage').attr('onclick', 'NewMessage(event)');
        $('#NewMessage').text('Compose');
    }

    function scrollToBottom() {
        var chatContainer = $('.message-content'); 
        if (chatContainer.length > 0) {
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }
    }

    $(document).ready(function() {
        scrollToBottom();

        // XỬ LÝ GỬI TIN NHẮN VÀ FIX LỖI MẤT KHUNG CHAT
        $(document).on('submit', '.message-footer form', function(e) {
            e.preventDefault(); 
            var form = $(this);
            var url = form.attr('action');
            var message = form.find('textarea').val();
            
            if(message.trim() === "") return;

            $.ajax({
                url: url,
                type: 'POST',
                data: {message: message},
                success: function(response) {
                    form.find('textarea').val(''); // Xóa text đã nhập
                    
                    // URL để đọc lại tin nhắn
                    var readUrl = url.replace('send_reply', 'read_message');

                    // Gọi AJAX lấy nội dung mới
                    $.ajax({
                        url: readUrl,
                        success: function(response) {
                            // --- ĐÂY LÀ PHẦN SỬA LỖI ---
                            // 1. Cố gắng tìm đúng phần nội dung tin nhắn trong phản hồi
                            var newContent = $(response).find('.message-details').html();
                            
                            // 2. Nếu tìm thấy (tức là server trả về cả trang), chỉ lấy phần .message-details
                            if (newContent) {
                                // Cập nhật nội dung bên trong div.message-details hiện tại
                                $('.message-details').html(newContent);
                            } else {
                                // 3. Nếu server trả về partial view (chỉ có inner_messages), dùng luôn response
                                // Kiểm tra xem response có chứa thẻ div message-details không để thay thế cho đúng
                                if($(response).hasClass('message-details') || response.indexOf('class="message-details"') !== -1){
                                     $('#toggle-1').html(response);
                                } else {
                                     // Trường hợp dự phòng khác
                                     $('#toggle-1').html(response);
                                }
                            }
                            
                            scrollToBottom(); // Cuộn xuống cuối
                        }
                    });
                }
            });
        });

        // Tự động đồng bộ tin nhắn (Real-time polling 3 giây/lần)
        if (current_thread_code !== "") {
            setInterval(function() {
                $.ajax({
                    url: '<?php echo site_url('home/my_messages/read_message/'); ?>' + current_thread_code,
                    success: function(response) {
                        // Logic tương tự: Chỉ lấy nội dung tin nhắn, bỏ qua header/footer thừa
                        var newMessages = $(response).find('.message-content').html();
                        
                        if (newMessages) {
                             // Chỉ thay thế phần danh sách tin nhắn (giữ nguyên header & footer form)
                             $('.message-content').html(newMessages);
                             scrollToBottom();
                        }
                    }
                });
            }, 3000);
        }
    });
</script>