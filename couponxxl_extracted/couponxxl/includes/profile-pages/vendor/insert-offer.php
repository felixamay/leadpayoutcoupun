<?php
if ( ! function_exists( 'couponxxl_insert_offer' ) ) {
	function couponxxl_insert_offer() {
		$all_checks_passed = false;
		$message = '';

		// Security check - user must be logged in
		if ( ! is_user_logged_in() ) {
			echo '<div class="alert alert-danger">' . esc_html__( 'You must be logged in to submit offers.', 'couponxxl' ) . '</div>';
			die();
		}

		$edit     = isset( $_POST['offer_id'] ) && ! empty( $_POST['offer_id'] ) ? true : false;
		$offer_id = $edit ? absint( $_POST['offer_id'] ) : 0;

		$vendor_id = isset( $_POST['vendor_id'] ) ? absint( $_POST['vendor_id'] ) : get_current_user_id();

		// Verify the vendor_id matches current user or user is admin
		if ( $vendor_id !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
			echo '<div class="alert alert-danger">' . esc_html__( 'You do not have permission to perform this action.', 'couponxxl' ) . '</div>';
			die();
		}

		$offer_type           = isset( $_POST['offer_type'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_type'] ) ) : '';
		$offer_title          = isset( $_POST['offer_title'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_title'] ) ) : '';
		$offer_description    = isset( $_POST['offer_description'] ) ? wp_kses_post( wp_unslash( $_POST['offer_description'] ) ) : '';
		$offer_featured_image = isset( $_POST['offer_featured_image'] ) ? absint( $_POST['offer_featured_image'] ) : 0;
		$offer_store          = isset( $_POST['offer_store'] ) ? absint( $_POST['offer_store'] ) : 0;
		$offer_cat            = isset( $_POST['offer_cat'] ) ? absint( $_POST['offer_cat'] ) : 0;
		$offer_new_category   = isset( $_POST['offer_new_category'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_new_category'] ) ) : '';
		$offer_start          = isset( $_POST['offer_start'] ) && ! empty( $_POST['offer_start'] ) ? strtotime( sanitize_text_field( wp_unslash( $_POST['offer_start'] ) ) ) : current_time( 'timestamp' );
		$offer_expire         = isset( $_POST['offer_expire'] ) && ! empty( $_POST['offer_expire'] ) ? strtotime( sanitize_text_field( wp_unslash( $_POST['offer_expire'] ) ) ) : '';
		/*COUPON RELATED */
		$coupon_excerpt = isset( $_POST['coupon_excerpt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['coupon_excerpt'] ) ) : '';
		$coupon_type    = isset( $_POST['coupon_type'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_type'] ) ) : '';
		$coupon_code    = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';
		$coupon_sale    = isset( $_POST['coupon_sale'] ) ? esc_url_raw( wp_unslash( $_POST['coupon_sale'] ) ) : '';
		$coupon_image   = isset( $_POST['coupon_image'] ) ? absint( $_POST['coupon_image'] ) : 0;
		$coupon_link    = isset( $_POST['coupon_link'] ) ? esc_url_raw( wp_unslash( $_POST['coupon_link'] ) ) : '';
		/*DEAL RELATED*/
		$deal_link           = isset( $_POST['deal_link'] ) ? esc_url_raw( wp_unslash( $_POST['deal_link'] ) ) : '';
		$deal_items          = isset( $_POST['deal_items'] ) ? absint( $_POST['deal_items'] ) : 0;
		$deal_item_vouchers  = isset( $_POST['deal_item_vouchers'] ) ? sanitize_textarea_field( wp_unslash( $_POST['deal_item_vouchers'] ) ) : '';
		$deal_price          = isset( $_POST['deal_price'] ) ? floatval( $_POST['deal_price'] ) : 0;
		$deal_sale_price     = isset( $_POST['deal_sale_price'] ) ? floatval( $_POST['deal_sale_price'] ) : 0;
		$deal_min_sales      = isset( $_POST['deal_min_sales'] ) ? absint( $_POST['deal_min_sales'] ) : 0;
		$deal_voucher_expire = isset( $_POST['deal_voucher_expire'] ) && ! empty( $_POST['deal_voucher_expire'] ) ? strtotime( sanitize_text_field( wp_unslash( $_POST['deal_voucher_expire'] ) ) ) : '';
		$deal_excerpt        = isset( $_POST['deal_excerpt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['deal_excerpt'] ) ) : '';
		$deal_images         = isset( $_POST['deal_images'] ) ? sanitize_text_field( wp_unslash( $_POST['deal_images'] ) ) : '';
		$deal_type           = isset( $_POST['deal_type'] ) ? sanitize_text_field( wp_unslash( $_POST['deal_type'] ) ) : '';

		$date_ranges      = couponxxl_get_option( 'date_ranges' );
		$unlimited_expire = couponxxl_get_option( 'unlimited_expire' );

		if ( pbs_is_demo() ) {
			exit;
		}

		if ( isset( $_POST['offer_type'] ) ) {
			if ( ! empty( $offer_title ) ) {
				if ( ! empty( $offer_description ) ) {
					if ( ! empty( $offer_featured_image ) ) {
						if ( ! empty( $offer_store ) ) {
							if ( ! empty( $offer_cat ) || ! empty( $offer_new_category ) ) {
								if ( ( ( empty( $unlimited_expire ) || $unlimited_expire == 'no' ) && ! empty( $offer_expire ) ) || $unlimited_expire == 'yes' || $edit ) {
									$check_ranges = true;
									if ( ! $edit && ! empty( $date_ranges ) && $unlimited_expire !== 'yes' ) {
										if ( $offer_expire - $offer_start >= $date_ranges * 24 * 60 * 60 ) {
											$check_ranges = false;
											$message      = '<div class="alert alert-danger">' . esc_html__( 'Maximum range between days is ', 'couponxxl' ) . ' ' . $date_ranges . '</div>';
										}
									}

									if ( $check_ranges == true ) {
										if ( empty( $offer_expire ) && ! $edit ) {
											$offer_expire = '99999999999';
										}
										$show_form = false;
										/* HANDLE COUPON DATA */
										if ( $offer_type == 'coupon' ) {
											if ( ! empty( $coupon_type ) ) {
												$check_coupon_type_value = true;
												switch ( $coupon_type ) {
													case 'code' :
														if ( empty( $coupon_code ) ) {
															$check_coupon_type_value = false;
															$message                 = '<div class="alert alert-danger">' . esc_html__( 'You need to input code for the coupon type code', 'couponxxl' ) . '</div>';
														}
														break;
													case 'sale' :
														if ( empty( $coupon_sale ) ) {
															$check_coupon_type_value = false;
															$message                 = '<div class="alert alert-danger">' . esc_html__( 'You need to input sale link for the coupon type sale', 'couponxxl' ) . '</div>';
														}
														break;
													case 'printable' :
														if ( empty( $coupon_image ) ) {
															$check_coupon_type_value = false;
															$message                 = '<div class="alert alert-danger">' . esc_html__( 'You need to upload coupon image for the printable coupon type', 'couponxxl' ) . '</div>';
														}
														break;
													default:
														$check_coupon_type_value = false;
														$message                 = '<div class="alert alert-danger">' . esc_html__( 'You need to specify coupon data based on its type', 'couponxxl' ) . '</div>';
												}
												if ( $check_coupon_type_value == true ) {
													$all_checks_passed = true;
												}
											} else {
												$message = '<div class="alert alert-danger">' . esc_html__( 'You need to select coupon type', 'couponxxl' ) . '</div>';
											}
										} /* HANDLE DEAL DATA */ else {
											if ( ! empty( $deal_items ) ) {
												if ( ! empty( $deal_price ) ) {
													if ( ! empty( $deal_sale_price ) ) {
														if ( ! empty( $deal_type ) ) {
															$seller_payout_account = get_user_meta( $vendor_id, 'seller_payout_account', true );
															$method_check          = true;
															if ( $deal_type == 'shared' && empty( $seller_payout_account ) ) {
																$method_check = false;
															}
															if ( $method_check ) {
																$all_checks_passed = true;
															} else {
																$message = '<div class="alert alert-danger">' . esc_html__( 'You need to connect your profile with payment method on which you wish to receive your share in order to use Website Offer type of deal.', 'couponxxl' ) . '</div>';
															}
														} else {
															$message = '<div class="alert alert-danger">' . esc_html__( 'You need to select deal type', 'couponxxl' ) . '</div>';
														}
													} else {
														$message = '<div class="alert alert-danger">' . esc_html__( 'You need to specify sale price of the deal ( number only with max two decimal separated by . )', 'couponxxl' ) . '</div>';
													}
												} else {
													$message = '<div class="alert alert-danger">' . esc_html__( 'You need to specify real price of the deal ( number only with max two decimal separated by . )', 'couponxxl' ) . '</div>';
												}
											} else {
												$message = '<div class="alert alert-danger">' . esc_html__( 'You need to specify number of items you wish to sell', 'couponxxl' ) . '</div>';
											}
										}
									}
								} else {
									$message = '<div class="alert alert-danger">' . esc_html__( 'You need to specify expire date', 'couponxxl' ) . '</div>';
								}
							} else {
								$message = '<div class="alert alert-danger">' . esc_html__( 'You need to select or submit new category', 'couponxxl' ) . '</div>';
							}
						} else {
							$message = '<div class="alert alert-danger">' . esc_html__( 'You need to select or submit new store', 'couponxxl' ) . '</div>';
						}
					} else {
						$message = '<div class="alert alert-danger">' . esc_html__( 'You need to select featured image for the coupon', 'couponxxl' ) . '</div>';
					}
				} else {
					$message = '<div class="alert alert-danger">' . esc_html__( 'Description is required', 'couponxxl' ) . '</div>';
				}
			} else {
				$message = '<div class="alert alert-danger">' . esc_html__( 'Title is required', 'couponxxl' ) . '</div>';
			}
		}

		if ( $all_checks_passed ) {
            $default_status = couponxxl_get_option('default_status');
			$args = array(
				'post_type'    => 'offer',
				'post_title'   => $offer_title,
				'post_content' => $offer_description,
				'post_status'  => 'live' === $default_status ? 'publish' : 'draft',
				'post_author'  => $vendor_id,
				'post_excerpt' => $offer_type == 'coupon' ? $coupon_excerpt : $deal_excerpt,
				'tax_input'    => array()
			);

			if ( ! empty( $offer_cat ) ) {
				$all_cats      = array();
				$all_to_assign = get_ancestors( $offer_cat, 'offer_cat' );
				if ( ! empty( $all_to_assign ) ) {
					$all_cats = $all_to_assign;
				}
				$all_cats[]                     = $offer_cat;
				$args['tax_input']['offer_cat'] = $all_cats;
			}

			if ( $offer_type == 'coupon' && ! empty( $coupon_excerpt ) ) {
				$args['post_excerpt'] = $coupon_excerpt;
			}

			if ( $edit ) {
				$status              = get_post_status( $offer_id );
				$args['ID']          = $offer_id;
				$args['post_status'] = 'draft' === $status ? 'draft' : 'publish';
				$offer_id            = wp_update_post( $args );
			} else {
				$offer_id = wp_insert_post( $args );
			}

			set_post_thumbnail( $offer_id, $offer_featured_image );

			couponxxl_update_post_meta( $offer_type, 'offer_type', $offer_id );
			update_post_meta( $offer_id, 'offer_store', $offer_store );
			update_post_meta( $offer_id, 'offer_new_category', $offer_new_category );
			if ( ! $edit ) {
				couponxxl_update_post_meta( (float) $offer_start, 'offer_start', $offer_id );
				couponxxl_update_post_meta( (float) $offer_expire, 'offer_expire', $offer_id );
			}
			couponxxl_update_post_meta( 'no', 'offer_in_slider', $offer_id );

			/* SAVE COUPON SPECIFIC DATA */
			if ( $offer_type == 'coupon' ) {
				update_post_meta( $offer_id, 'coupon_type', $coupon_type );
				update_post_meta( $offer_id, 'coupon_code', $coupon_code );
				update_post_meta( $offer_id, 'coupon_sale', $coupon_sale );
				update_post_meta( $offer_id, 'coupon_image', $coupon_image );
				update_post_meta( $offer_id, 'coupon_link', $coupon_link );
				$offer_submit_price = couponxxl_get_option( 'coupon_submit_price' );
			} /* SAVE DEAL SPECIFIC DATA */ else {
				update_post_meta( $offer_id, 'deal_link', $deal_link );
				update_post_meta( $offer_id, 'deal_items', $deal_items );
				update_post_meta( $offer_id, 'deal_item_vouchers', $deal_item_vouchers );
				update_post_meta( $offer_id, 'deal_min_sales', $deal_min_sales );
				update_post_meta( $offer_id, 'deal_voucher_expire', $deal_voucher_expire );
				update_post_meta( $offer_id, 'deal_price', $deal_price );
				update_post_meta( $offer_id, 'deal_sale_price', $deal_sale_price );

				couponxxl_save_deal_marker_locations( $offer_id );

				if ( ! empty( $deal_images ) ) {
					$deal_images       = explode( ",", $deal_images );
					$deal_images_array = array();
					$counter           = 0;
					foreach ( $deal_images as $deal_image ) {
						$deal_images_array[ 'sm-field-' . $counter ] = $deal_image;
						$counter ++;
					}
					$deal_images = array( serialize( $deal_images_array ) );
					update_post_meta( $offer_id, 'deal_images', implode( "", $deal_images ) );
				}
				update_post_meta( $offer_id, 'deal_type', $deal_type );
				$offer_submit_price = couponxxl_get_option( 'deal_submit_price' );
			}

			/* charge for the submission */
			if ( ! $edit ) {
				$cxxl_credits = get_user_meta( $vendor_id, 'cxxl_credits', true );
				if ( $cxxl_credits >= $offer_submit_price ) {
					$cxxl_credits -= $offer_submit_price;
					update_user_meta( $vendor_id, 'cxxl_credits', $cxxl_credits );

					$to            = couponxxl_get_option( 'new_offer_email' );
					$subject       = sprintf( __( '[%s] New offer has been submitted!', 'couponxxl' ), get_bloginfo( 'name', 'display' ) );
					$email_message = sprintf( __( 'Offer: %s has been submitted.', 'couponxxl' ), '<a href="' . esc_url( get_admin_url() . '/post.php?post=' . $offer_id . '/&action=edit' ) . '">' . get_the_title( $offer_id ) . '</a>' );
					if ( ! empty( $to ) ) {
						couponxxl_send_mail( $to, $subject, $email_message );
					}

                    if ('live' === $default_status) {
                        $message = '<div class="alert alert-success">' . esc_html__( 'Your offer is successfully submitted.', 'couponxxl' ) . '</div>';
                    } else {
                        $message = '<div class="alert alert-success">' . esc_html__( 'Your offer is successfully submitted. After the review it will be approved or rejected.', 'couponxxl' ) . '</div>';
                    }
				} else {
					$message = '<div class="alert alert-danger">' . esc_html__( 'You do not have enough credits to submit this offer.', 'couponxxl' ) . '</div>';
				}
			} else {
				$message = '<div class="alert alert-success">' . esc_html__( 'Your offer is successfully edited.', 'couponxxl' ) . '</div>';
			}
		}

		echo wp_kses_post( $message );
		die();
	}

	add_action( 'wp_ajax_insert_offer', 'couponxxl_insert_offer' );
	add_action( 'wp_ajax_nopriv_insert_offer', 'couponxxl_insert_offer' );
}
?>