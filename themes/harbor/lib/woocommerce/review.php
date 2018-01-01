<?php

add_filter('woocommerce_product_review_comment_form_args', function ($form) {
    /**
     * @var array $form
     */

    $commenter = wp_get_current_commenter();

    $label_req = '<span class="required">*</span>';
    $placeholder_req = '*';
    $html_req = ' aria-required="true" required="required"';

    $form['fields'] = array(
        'author' => '<div class="col-sm-4"><div class="form-group"><label class="sr-only" for="author">' . __('Name', 'woocommerce') . $label_req . '</label><input id="author" name="author" class="form-control" type="text" placeholder="' . __('Name', 'woocommerce') . $placeholder_req . '" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $html_req . ' /></div></div>',
        'email' => '<div class="col-sm-4"><div class="form-group"><label class="sr-only" for="email">' . __('Email', 'woocommerce') . $label_req . '</label><input id="email" name="email" class="form-control" type="email" placeholder="' . __('Email', 'woocommerce') . $placeholder_req . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-describedby="email-notes"' . $html_req . ' /></div></div>'
    );

    $form['comment_field'] = (get_option('woocommerce_enable_review_rating') === 'yes')
        ? '<div class="col-sm-12"><div class="form-group"><label class="sr-only" for="rating">' . __('Your Rating', 'woocommerce') . '</label>' . om_wc_get_rating_select_field() . '</div></div>'
        : '';

    $form['comment_field'] .= '<div class="col-sm-12"><div class="form-group"><label class="sr-only" for="comment">' . esc_html__('Your Review', 'woocommerce') . '</label><textarea id="comment" name="comment" class="form-control" placeholder="' . esc_html__('Your Review', 'woocommerce') . '" rows="8" aria-required="true"></textarea></div></div>';

    return $form;
});