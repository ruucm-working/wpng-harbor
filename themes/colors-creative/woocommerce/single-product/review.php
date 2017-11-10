<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" class="comment">

    <article id="comment-<?php comment_ID(); ?>" class="comment-body">
        <footer class="comment-meta">
            <div class="comment-author vcard">

                <?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '64' ), '' ); ?>

                <?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

                <div class="star-rating-review">
                    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'woocommerce' ), $rating ) ?>">
                        <span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo $rating; ?></strong> <?php esc_html_e( 'out of 5', 'woocommerce' ); ?></span>
                    </div>
                </div>

                <?php endif; ?>

                <span class="fn">
                    <strong itemprop="author"><?php comment_author(); ?></strong>

                    <?php if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' && wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) ) : ?>
                        <small class="verified text-muted">&mdash; <?php esc_html_e( 'verified owner', 'woocommerce' ) ?></small>
                    <?php endif ?>
                </span>
            </div><!-- .comment-author -->

            <?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>

            <div class="comment-metadata">
                <time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></time>
            </div><!-- .comment-metadata -->

        </footer><!-- .comment-meta -->

        <?php do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

        <div itemprop="description" class="comment-content">
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="text-info">
                    <?php esc_html_e( 'Your comment is awaiting approval', 'woocommerce' ); ?>
                </p>
            <?php endif ?>

            <?php comment_text(); ?>
        </div><!-- .comment-content -->

        <?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>
    </article>
