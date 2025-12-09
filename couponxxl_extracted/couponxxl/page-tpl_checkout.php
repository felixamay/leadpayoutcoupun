<?php
/*
    Template Name: Checkout
*/
get_header();
the_post();

global $couponxxl_cart;
?>
	<section>
		<div class="container">
			<div class="ajax-cart-wrap">
				<?php
				$action = ! empty( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
				$gateway = ! empty( $_GET['gateway'] ) ? sanitize_text_field( wp_unslash( $_GET['gateway'] ) ) : '';
				
				if ( $action === 'gateway-return' && ! empty( $gateway ) ) {
					do_action( 'couponxxl_process_response', $gateway );
				} else {
					$order_key = ! empty( $_GET['order_key'] ) ? sanitize_text_field( wp_unslash( $_GET['order_key'] ) ) : '';
					$package   = ! empty( $_GET['package'] ) ? absint( $_GET['package'] ) : 0;
					if ( empty( $package ) ) {
						$couponxxl_cart->initiate_payment( $order_key );
					} else {
						couponxxl_generate_credit_payments();
					}
				}
				?>
			</div>
		</div>
	</section>
<?php get_footer(); ?>