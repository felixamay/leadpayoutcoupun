<?php
global $current_user, $wpdb;
$current_user = wp_get_current_user();
$coupons = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id WHERE posts.post_type = 'offer' AND post_author = %d AND offers.offer_type = 'coupon'", get_current_user_id() ) );
$deals = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id WHERE posts.post_type = 'offer' AND post_author = %d AND offers.offer_type = 'deal'", get_current_user_id() ) );
$sales = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(item_id) FROM {$wpdb->prefix}order_items WHERE seller_id = %d", get_current_user_id() ) );
$earnings = couponxxl_user_earnings(get_current_user_id() );
?>

<!-- Modern Welcome Header -->
<div class="dashboard-welcome animate-fade-in-up">
	<i class="fa fa-chart-line welcome-icon"></i>
	<h2><?php esc_html_e( 'Welcome back', 'couponxxl' ); ?>, <?php echo esc_html( !empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login ); ?>! 👋</h2>
	<p><?php esc_html_e( 'Here\'s a quick overview of your store performance.', 'couponxxl' ); ?></p>
</div>

<!-- Modern Stats Cards -->
<ul class="list-unstyled dashboard-stats-modern">
	<li class="stat-card coupons animate-fade-in-up animate-delay-1">
		<div class="stat-icon">
			<i class="fa fa-ticket-alt"></i>
		</div>
		<div class="stat-content">
			<div class="stat-label"><?php esc_html_e( 'Active Coupons', 'couponxxl' ); ?></div>
			<div class="stat-value"><?php echo esc_html( $coupons ); ?></div>
		</div>
	</li>
	<li class="stat-card deals animate-fade-in-up animate-delay-2">
		<div class="stat-icon">
			<i class="fa fa-percentage"></i>
		</div>
		<div class="stat-content">
			<div class="stat-label"><?php esc_html_e( 'Active Deals', 'couponxxl' ); ?></div>
			<div class="stat-value"><?php echo esc_html( $deals ); ?></div>
		</div>
	</li>
	<li class="stat-card sales animate-fade-in-up animate-delay-3">
		<div class="stat-icon">
			<i class="fa fa-shopping-cart"></i>
		</div>
		<div class="stat-content">
			<div class="stat-label"><?php esc_html_e( 'Total Sales', 'couponxxl' ); ?></div>
			<div class="stat-value"><?php echo esc_html( $sales ); ?></div>
		</div>
	</li>
	<li class="stat-card earnings-due animate-fade-in-up animate-delay-4">
		<div class="stat-icon">
			<i class="fa fa-clock"></i>
		</div>
		<div class="stat-content">
			<div class="stat-label"><?php esc_html_e( 'Earnings Pending', 'couponxxl' ); ?></div>
			<div class="stat-value"><?php echo couponxxl_format_price_number( 0 + $earnings->not_paid ); ?></div>
		</div>
	</li>
	<li class="stat-card earnings-sent animate-fade-in-up animate-delay-5">
		<div class="stat-icon">
			<i class="fa fa-check-circle"></i>
		</div>
		<div class="stat-content">
			<div class="stat-label"><?php esc_html_e( 'Earnings Paid', 'couponxxl' ); ?></div>
			<div class="stat-value"><?php echo couponxxl_format_price_number( 0 + $earnings->paid ); ?></div>
		</div>
	</li>
</ul>