<section class="menu-area">
  <div class="container-xl">
    <div class="row">
      <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

          <ul class="mobile-header-buttons">
            <li><a class="mobile-nav-trigger" href="#mobile-primary-nav">Menu<span></span></a></li>
            <li><a class="mobile-search-trigger" href="#mobile-search">Search<span></span></a></li>
          </ul>

          <a href="<?php echo site_url(
            "",
          ); ?>" class="navbar-brand" href="#"><img src="<?php echo base_url() .
  "uploads/system/logo-dark.png"; ?>" alt="" height="65"></a>

          <div class="col-auto">
                <?php include "menu.php"; ?>
            </div>

          <form class="inline-form" action="<?php echo site_url(
            "home/search",
          ); ?>" method="get" style="width: 100%;">
            <div class="input-group search-box mobile-search">
              <input type="text" name = 'query' class="form-control" placeholder="<?php echo get_phrase(
                "search_for_courses",
              ); ?>">
              <div class="input-group-append">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>

         <div class="col-lg-3 col-md-3 col-6">
          <div class="d-flex justify-content-end align-items-center">
              
              <?php
              // Kiểm tra quyền: Admin hoặc Mod
              $is_admin = $this->session->userdata("admin_login");
              $is_mod = $this->session->userdata("mod_login");
              ?>

              <?php if ($is_admin || $is_mod): ?>
                  
                  <div class="ml-3">
                      <a href="<?php echo site_url(
                        $is_admin ? "admin" : "mod",
                      ); ?>" class="btn btn-danger py-2 px-3 shadow-sm" style="font-weight: 600; border-radius: 4px;">
                          <i class="fas fa-user-shield mr-1"></i> 
                          <?php echo get_phrase(
                            $is_admin ? "administrator" : "moderator",
                          ); ?>
                      </a>
                  </div>

              <?php else: ?>
                  
                  <div class="cart-box menu-icon-box" id = "cart_items">
                      <?php include "cart_items.php"; ?>
                  </div>

                  <span class="signin-box-move-desktop-helper"></span>
                  
                  <div class="sign-in-box btn-group ml-3">
                      <a href="<?php echo site_url(
                        "home/login",
                      ); ?>" class="btn btn-sign-in"><?php echo get_phrase("log_in",); ?></a>
                      <a href="<?php echo site_url("home/sign_up",); ?>" class="btn btn-sign-up"><?php echo get_phrase("sign_up",); ?></a>
                  </div>
              <?php endif; ?>
          </div>
        </div>
        </nav>
      </div>
    </div>
  </div>
</section>
