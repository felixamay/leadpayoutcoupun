<!-- Modern Buyer Welcome Section -->
<div class="dashboard-welcome animate-fade-in-up" style="margin-bottom: 30px;">
    <i class="fa fa-user-circle welcome-icon"></i>
    <h2><?php esc_html_e( 'Welcome', 'couponxxl' ); ?>, <?php echo esc_html( $current_user->display_name ); ?>! 👋</h2>
    <?php $purchases = couponxxl_user_purchases( get_current_user_id(), 'deals' ); ?>
    <p><?php esc_html_e( 'You have purchased', 'couponxxl' ); ?> <strong><?php echo esc_html( $purchases ); ?></strong> <?php echo $purchases == 1 ? esc_html__( 'deal', 'couponxxl' ) : esc_html__( 'deals', 'couponxxl' ); ?>. <a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'purchases' ), $profile_link ) ); ?>" style="color: #fff; text-decoration: underline;"><?php esc_html_e( 'View purchases', 'couponxxl' ); ?></a></p>
</div>

<div class="white-block-modern buyer-block">
    <div class="white-block-title">
        <h4><i class="fa fa-user-edit"></i> <?php esc_html_e( 'Edit Profile', 'couponxxl' ); ?></h4>
    </div>
    <div class="white-block-content">
        <form method="post" class="form-modern">
            <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="profile-sidebar-modern" style="margin-bottom: 20px;">
                        <div class="avatar-wrapper">
                            <a href="javascript:;" class="user-avatar<?php echo pbs_is_demo() ? 's' : ''; ?>">
                                <?php echo get_avatar( $current_user->ID, 120 ); ?>
                            </a>
                            <div class="upload-overlay">
                                <i class="fa fa-camera"></i>
                            </div>
                        </div>
                        <div class="user-name"><?php echo esc_html( $current_user->display_name ); ?></div>
                        <div class="user-role"><i class="fa fa-check-circle" style="color: #10b981;"></i> <?php esc_html_e( 'Verified Buyer', 'couponxxl' ); ?></div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <label for="first_name"><?php esc_html_e( 'First Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your first name', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_firstname ) ?>">
                                <i class="pline-user"></i>
                                <p class="description"><?php esc_html_e( 'Input your first name.', 'couponxxl' ); ?></p>
                            </div>
                            <div class="input-group">
                                <label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" data-validation="match" data-match="repeat_password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>" placeholder="******">
                                <i class="pline-lock-locked"></i>
                                <p class="description"><?php esc_html_e( 'Input your password.', 'couponxxl' ); ?></p>
                            </div>
                            <div class="input-group">
                                <label for="email"><?php esc_html_e( 'Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="text" name="email" id="email" class="form-control" data-validation="required|email"  data-error="<?php _e( 'Email is empty or invalid', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_email ) ?>">
                                <i class="pline-envelope"></i>
                                <p class="description"><?php esc_html_e( 'Input your email.', 'couponxxl' ); ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <label for="last_name"><?php esc_html_e( 'Last Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your last name', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_lastname ) ?>">
                                <i class="pline-user"></i>
                                <p class="description"><?php esc_html_e( 'Input your last name.', 'couponxxl' ); ?></p>
                            </div>
                            <div class="input-group">
                                <label for="repeat_password"><?php esc_html_e( 'Repeat Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="password" name="repeat_password" id="repeat_password" class="form-control" data-validation="match" data-match="password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>" placeholder="******">
                                <i class="pline-lock-locked"></i>
                                <p class="description"><?php esc_html_e( 'Input password again.', 'couponxxl' ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12">
                            <?php wp_nonce_field('update_profile','update_profile_field'); ?>
                            <a href="javascript:;" class="btn btn-modern btn-modern-primary register-form <?php echo pbs_is_demo() ? '' : esc_attr( 'submit-form' ); ?>">
                                <i class="fa fa-save"></i> <?php esc_html_e( 'Save Profile', 'couponxxl' ); ?>
                            </a>
                            <div class="ajax-response" style="margin-top: 15px;"></div>
                            <input type="hidden" value="update_profile" name="action">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>