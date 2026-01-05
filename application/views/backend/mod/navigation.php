<?php
	$status_wise_courses = $this->crud_model->get_status_wise_courses();
 ?>
<div class="left-side-menu left-side-menu-detached">
	<div class="leftbar-user">
		<a href="javascript: void(0);">
			<img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="user-image" height="42" class="rounded-circle shadow-sm">
			<?php
			$admin_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
			?>
			<span class="leftbar-user-name"><?php echo $admin_details['first_name'].' '.$admin_details['last_name']; ?></span>
		</a>
	</div>

	<ul class="metismenu side-nav side-nav-light">

			<li class="side-nav-title side-nav-item"><?php echo get_phrase('navigation'); ?></li>

			<li class="side-nav-item <?php if ($page_name == 'dashboard')echo 'active';?>">
				<a href="<?php echo site_url('mod/dashboard'); ?>" class="side-nav-link">
					<i class="dripicons-view-apps"></i>
					<span><?php echo get_phrase('dashboard'); ?></span>
				</a>
			</li>

			<li class="side-nav-item <?php if ($page_name == 'categories' || $page_name == 'category_add' || $page_name == 'category_edit' ): ?> active <?php endif; ?>">
				<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'categories' || $page_name == 'category_add' || $page_name == 'category_edit' ): ?> active <?php endif; ?>">
					<i class="dripicons-network-1"></i>
					<span> <?php echo get_phrase('categories'); ?> </span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li class = "<?php if($page_name == 'categories' || $page_name == 'category_edit') echo 'active'; ?>">
						<a href="<?php echo site_url('mod/categories'); ?>"><?php echo get_phrase('categories'); ?></a>
					</li>

					<li class = "<?php if($page_name == 'category_add') echo 'active'; ?>">
						<a href="<?php echo site_url('mod/category_form/add_category'); ?>"><?php echo get_phrase('add_new_category'); ?></a>
					</li>
				</ul>
			</li>

			<li class="side-nav-item">
				<a href="<?php echo site_url('mod/courses'); ?>" class="side-nav-link <?php if ($page_name == 'courses' || $page_name == 'course_add' || $page_name == 'course_edit')echo 'active';?>">
					<i class="dripicons-archive"></i>
					<span><?php echo get_phrase('courses'); ?></span>
				</a>
			</li>
			<li class="side-nav-item">
				<a href="<?php echo site_url('mod/users'); ?>" class="side-nav-link <?php if ($page_name == 'users' || $page_name == 'user_add' || $page_name == 'user_edit')echo 'active';?>">
					<i class="dripicons-user-group"></i>
					<span><?php echo get_phrase('students'); ?></span>
				</a>
			</li>

			<li class="side-nav-item <?php if ($page_name == 'enrol_history' || $page_name == 'enrol_student'): ?> active <?php endif; ?>">
				<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'enrol_history' || $page_name == 'enrol_student'): ?> active <?php endif; ?>">
					<i class="dripicons-network-3"></i>
					<span> <?php echo get_phrase('enrolment'); ?> </span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li class = "<?php if($page_name == 'enrol_history') echo 'active'; ?>">
						<a href="<?php echo site_url('mod/enrol_history'); ?>"><?php echo get_phrase('enrol_history'); ?></a>
					</li>

					<li class = "<?php if($page_name == 'enrol_student') echo 'active'; ?>">
						<a href="<?php echo site_url('mod/enrol_student'); ?>"><?php echo get_phrase('enrol_a_student'); ?></a>
					</li>
				</ul>
			</li>

            <li class="side-nav-item <?php if ($page_name == 'message') echo 'active'; ?>">
			    <a href="<?php echo site_url('mod/message'); ?>" class="side-nav-link">
			        <i class="dripicons-message"></i>
                    
                    <span class="badge badge-danger float-right" id="sidebar_message_badge_mod" style="display: none; margin-top: 3px;">0</span>
			        
			        <span><?php echo get_phrase('message'); ?></span>
			    </a>
			</li>
            
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    function updateModSidebarCount() {
                        var xhr = new XMLHttpRequest();
                        // Gọi API lấy số tin nhắn
                        xhr.open('GET', '<?php echo site_url('home/get_unread_messages_count'); ?>', true);
                        
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                // Lấy số tin nhắn từ phản hồi server
                                var count = parseInt(xhr.responseText);
                                var badge = document.getElementById('sidebar_message_badge_mod');
                                
                                if (badge) {
                                    if (!isNaN(count) && count > 0) {
                                        // Có tin nhắn -> Hiện số & Hiển thị Badge
                                        badge.innerText = count;
                                        badge.style.display = 'inline-block'; 
                                    } else {
                                        // Không có tin nhắn -> Ẩn Badge
                                        badge.style.display = 'none'; 
                                    }
                                }
                            }
                        };
                        xhr.send();
                    }

                    // 1. Chạy ngay khi tải trang
                    updateModSidebarCount();
                    
                    // 2. Tự động kiểm tra lại mỗi 5 giây
                    setInterval(updateModSidebarCount, 5000);
                });
            </script>
            </ul>
</div>