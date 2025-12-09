<?php
$subpage = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : '';
$order = isset( $_GET['order'] ) ? absint( $_GET['order'] ) : 0;
$search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
?>

<!-- Modern Buyer Dashboard Navigation -->
<ul class="list-unstyled buyer-nav-modern">
	<li class="<?php echo empty( $subpage ) ? esc_attr( 'active' ) : '' ?>">
		<a href="<?php echo esc_url( $profile_link ) ?>" title="<?php esc_attr_e( 'My Profile', 'couponxxl' ); ?>">
			<i class="fa fa-user-circle"></i>
			<span><?php esc_html_e( 'My Profile', 'couponxxl' ); ?></span>
		</a>
	</li>
	<li class="<?php echo 'purchases' == $subpage ? esc_attr( 'active' ) : '' ?>">
		<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'purchases' ), $profile_link ) ) ?>" title="<?php esc_attr_e( 'My Purchases', 'couponxxl' ); ?>">
			<i class="fa fa-shopping-bag"></i>
			<span><?php esc_html_e( 'My Purchases', 'couponxxl' ); ?></span>
		</a>
	</li>
	<li class="logout">
		<a href="<?php echo esc_url( wp_logout_url( home_url('/') ) ) ?>" title="<?php esc_attr_e( 'Log Out', 'couponxxl' ); ?>">
			<i class="fa fa-sign-out-alt"></i>
			<span><?php esc_html_e( 'Log Out', 'couponxxl' ); ?></span>
		</a>
	</li>
</ul>

<?php

if( $subpage == 'purchases' ){
	if( !empty( $order ) ){
		include( couponxxl_load_path( 'includes/profile-pages/buyer/view-order.php' ) );
	}
	else{
		include( couponxxl_load_path( 'includes/profile-pages/buyer/list-orders.php' ) );
	}

}
else{
	include( couponxxl_load_path( 'includes/profile-pages/buyer/profile.php' ) );
}
?>