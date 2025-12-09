<div class="white-block offer-box<?php echo current_time( 'timestamp' ) > $offer->offer_expire ? ' expired' : ''; ?>">
	<div class="white-block-media">
		<div class="embed-responsive embed-responsive-16by9">
			<a href="<?php the_permalink() ?>">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'couponxxl-offer-box', array( 'class' => 'embed-responsive-item' ) );
				}
				?>
			</a>
		</div>
	</div>

	<div class="white-block-content">

		<h6>
			<a href="<?php the_permalink() ?>">
				<?php
				$title = get_the_title();
				if ( strlen( $title > 78 ) ) {
					$title = substr( $title, 0, 78 ) . '...';
				}

				echo wp_kses_post( $title );
				?>
			</a>
		</h6>

		<?php echo couponxxl_deal_meta(); ?>

	</div>
</div>