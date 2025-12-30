<?php
    // 1. Lấy thông tin bài học
    $lesson_details = $this->crud_model->get_lessons('lesson', $lesson_id)->row_array();
    $lesson_thumbnail_url = $this->crud_model->get_lesson_thumbnail_url($lesson_id);
    $course_details = $this->crud_model->get_course_by_id($lesson_details['course_id'])->row_array();

    // 2. Gán biến chuẩn xác từ Database (Sửa lỗi undefined variable)
    $video_url = $lesson_details['video_url'];
    $provider  = strtolower($lesson_details['video_type']); // Chuyển về chữ thường để so sánh

    // 3. Logic kiểm tra Bunny
    $is_bunny = ($provider == 'bunny' || strpos($video_url, 'iframe.mediadelivery.net') !== false);
?>

<ul class="breadcrumb">
    <li><a href="<?php echo site_url('admin/dashboard'); ?>" class=""><?php echo get_phrase('dashboard'); ?></a> </li>
    <li><a href="<?php echo site_url('admin/lessons/').$lesson_details['course_id']; ?>"><?php echo get_phrase('lessons'); ?></a> </li>
    <li><a href="#" class="active"><?php echo get_phrase('video_player'); ?></a> </li>
</ul>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $page_title; ?></h3>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4><?php echo get_phrase('course_title'); ?> : <span style="font-weight: 800;"><?php echo $course_details['title']; ?></span></h4>
                <h4><?php echo get_phrase('lesson_title'); ?> : <span style="font-weight: 800;"><?php echo $lesson_details['title']; ?></span></h4>
            </div>
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                        
                        <?php if ($lesson_details['lesson_type'] != 'other'): ?>
                            
                            <?php if ($is_bunny): ?>
                                <div class="bunny-embed-container">
                                    <iframe 
                                        src="<?php echo $video_url; ?>" 
                                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" 
                                        allowfullscreen="true"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                    ></iframe>
                                </div>
                                <style>
                                    .bunny-embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; }
                                </style>

                            <?php elseif ($provider == 'youtube'): ?>
                                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                                <div class="plyr__video-embed" id="player">
                                    <iframe height="500" src="<?php echo $video_url;?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                                </div>
                                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                                <script>const player = new Plyr('#player');</script>

                            <?php elseif ($provider == 'vimeo'):
                                $video_details = $this->video_model->getVideoDetails($video_url);?>
                                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                                <div class="plyr__video-embed" id="player">
                                    <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                                </div>
                                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                                <script>const player = new Plyr('#player');</script>

                            <?php else :?>
                                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                                <video poster="<?php echo $lesson_thumbnail_url;?>" id="player" playsinline controls>
                                    <?php if (get_video_extension($video_url) == 'mp4'): ?>
                                        <source src="<?php echo $video_url; ?>" type="video/mp4">
                                    <?php elseif (get_video_extension($video_url) == 'webm'): ?>
                                        <source src="<?php echo $video_url; ?>" type="video/webm">
                                    <?php else: ?>
                                        <source src="<?php echo $video_url; ?>" type="video/mp4">
                                    <?php endif; ?>
                                </video>
                                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                                <script>const player = new Plyr('#player');</script>
                            <?php endif; ?>

                        <?php else: ?>
                            <a href="<?php echo base_url().'uploads/lesson_files/'.$lesson_details['attachment']; ?>" class="btn btn-primary" download>
                                <i class="entypo-download" style="font-size: 20px;"></i> <?php echo get_phrase('download').' '.$lesson_details['title']; ?>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>