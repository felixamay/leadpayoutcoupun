<?php
global $current_user;
$user_id = $vendor_id;
$store = get_posts(array( 'post_type' => 'store', 'author' => $user_id ));
$user_offers_args = array(
	'post_type'      => 'offer',
	'post_status'    => 'publish,draft',
	'posts_per_page' => - 1,
	'author'         => $current_user->ID,
);
$user_offers = new WP_Query( $user_offers_args );
$offer_ids  = wp_list_pluck( $user_offers->posts, 'ID' );
if( !empty( $store ) ):
	$store = array_shift( $store );
	$subpage = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : '';
	$search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

	/*check if we are returning from purchasing the credits*/
	if( !empty( $_GET['action'] ) && $_GET['action'] == 'gateway-return' && !empty( $_GET['gateway'] ) ){
		$gateway = sanitize_text_field( wp_unslash( $_GET['gateway'] ) );
    	do_action( 'couponxxl_process_response', $gateway );
	}
	?>
	<!-- Modern Vendor Dashboard Navigation -->
	<ul class="list-unstyled dashboard-nav-modern">
		<li class="<?php echo empty( $subpage ) ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( $profile_link ) ?>" title="<?php esc_attr_e( 'Dashboard', 'couponxxl' ); ?>">
				<i class="fa fa-tachometer-alt"></i>
				<span><?php esc_html_e( 'Dashboard', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'profile' == $subpage ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'profile' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Profile', 'couponxxl' ); ?>">
				<i class="fa fa-user-circle"></i>
				<span><?php esc_html_e( 'Profile', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'store' == $subpage ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'store' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Store', 'couponxxl' ); ?>">
				<i class="fa fa-store"></i>
				<span><?php esc_html_e( 'Store', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'coupons' == $subpage || $subpage == 'manage-offer-coupon' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'coupons' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Coupons', 'couponxxl' ); ?>">
				<i class="fa fa-ticket-alt"></i>
				<span><?php esc_html_e( 'Coupons', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'deals' == $subpage || $subpage == 'manage-offer-deal' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'deals' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Deals', 'couponxxl' ); ?>">
				<i class="fa fa-percentage"></i>
				<span><?php esc_html_e( 'Deals', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'vouchers' == $subpage ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'vouchers' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Vouchers', 'couponxxl' ); ?>">
				<i class="fa fa-receipt"></i>
				<span><?php esc_html_e( 'Vouchers', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'agents' == $subpage ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'agents' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Agents', 'couponxxl' ); ?>">
				<i class="fa fa-users"></i>
				<span><?php esc_html_e( 'Agents', 'couponxxl' ); ?></span>
			</a>
		</li>
		<li class="<?php echo 'credits' == $subpage ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'credits' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'Credits', 'couponxxl' ); ?>">
				<i class="fa fa-coins"></i>
				<span><?php esc_html_e( 'Credits', 'couponxxl' ); ?></span>
			</a>
		</li>
	</ul>

	<div class="row">
		<div class="col-sm-4 col-md-3">
			<!-- Modern Profile Sidebar -->
			<div class="profile-sidebar-modern">
				<div class="avatar-wrapper">
					<a href="javascript:;" class="<?php echo pbs_is_demo() ? '' : esc_attr( 'store-logo' ); ?>" data-store_id="<?php echo esc_attr( $store->ID ) ?>">
						<?php
						if( has_post_thumbnail( $store->ID ) ){
							echo get_the_post_thumbnail( $store->ID, 'thumbnail', array( 'class' => 'store-avatar' ) );
						}
						else{
							echo '<img src="' . esc_url( get_template_directory_uri() . '/images/m1.png' ) . '" alt="' . esc_attr__( 'Store Logo', 'couponxxl' ) . '">';
						}
						?>
					</a>
					<div class="upload-overlay">
						<i class="fa fa-camera"></i>
					</div>
				</div>
				<div class="user-name"><?php echo esc_html( $store->post_title ); ?></div>
				<div class="user-role"><i class="fa fa-check-circle" style="color: #10b981;"></i> <?php esc_html_e( 'Verified Vendor', 'couponxxl' ); ?></div>
				<?php
				$credits = get_user_meta( $user_id, 'cxxl_credits', true );
				if( empty( $credits ) ){
					$credits = 0;
				}
				?>
				<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'credits' ), $profile_link ) ); ?>" class="credits-badge">
					<i class="fa fa-coins"></i>
					<?php echo esc_html( $credits ); ?> <?php echo $credits == 1 ? esc_html__( 'Credit', 'couponxxl' ) : esc_html__( 'Credits', 'couponxxl' ); ?>
				</a>
				<a class="btn-logout" href="<?php echo esc_url( wp_logout_url( home_url('/') ) ) ?>">
					<i class="fa fa-sign-out-alt"></i>
					<?php esc_html_e( 'Log Out', 'couponxxl' ); ?>
				</a>
			</div>
		</div>
		<div class="col-sm-8 col-md-9">
			<?php
			switch( $subpage ){
				case 'profile' : include( couponxxl_load_path( 'includes/profile-pages/vendor/profile.php' ) ); break;
				case 'store' : include( couponxxl_load_path( 'includes/profile-pages/vendor/store.php' ) ); break;
				case 'coupons' : include( couponxxl_load_path( 'includes/profile-pages/vendor/coupons.php' ) ); break;
				case 'deals' : include( couponxxl_load_path( 'includes/profile-pages/vendor/deals.php' ) ); break;
				case 'vouchers' : include( couponxxl_load_path( 'includes/profile-pages/vendor/vouchers.php' ) ); break;
				case 'agents' : include( couponxxl_load_path( 'includes/profile-pages/vendor/agents.php' ) ); break;
				case 'credits' : include( couponxxl_load_path( 'includes/profile-pages/vendor/credits.php' ) ); break;

				case 'manage-offer-coupon' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'manage-offer-deal' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'delete-offer' : include( couponxxl_load_path( 'includes/profile-pages/vendor/delete-offer.php' ) ); break;

				case 'delete-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/delete-agent.php' ) ); break;
				case 'edit-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/edit-agent.php' ) ); break;
				case 'new-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/new-agent.php' ) ); break;
				default: include( couponxxl_load_path( 'includes/profile-pages/vendor/dashboard.php' ) ); break;
			}
			?>
		</div>
	</div>
<?php  endif; ?>