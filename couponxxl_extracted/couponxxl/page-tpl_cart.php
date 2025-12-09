<?php
/*
    Template Name: Cart
*/
get_header();
the_post();
get_template_part( 'includes/title' );

global $couponxxl_cart;
?>
<section>
    <div class="container">
    	<div class="ajax-cart-wrap">
        	<?php $couponxxl_cart->cart( true ); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>