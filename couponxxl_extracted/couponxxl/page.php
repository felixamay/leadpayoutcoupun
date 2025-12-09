<?php
get_header();
the_post();
get_template_part( 'includes/title' );
global $numpages;
?>
    <section class="contact-page">
        <div class="container">
            <div class="row">
                <div class="col-md-<?php echo is_active_sidebar( 'sidebar-right' ) ? '9' : '12' ?>">
                    <div class="white-block">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'post-thumbnail' );
						}
						?>

                        <div class="white-block-content">
                            <div class="page-content clearfix">
								<?php the_content() ?>

                            </div>
                        </div>

	                    <?php if ( $numpages > 1 ) : ?>
                            <div class="page-content mb-0">
			                    <?php wp_link_pages(); ?>
                            </div>
	                    <?php endif; ?>

                    </div>

					<?php comments_template( '', true ); ?>

                </div>

				<?php get_sidebar( 'right' ) ?>

            </div>
        </div>
    </section>
<?php get_footer(); ?>