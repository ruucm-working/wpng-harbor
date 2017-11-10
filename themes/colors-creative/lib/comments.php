<?php

add_filter('edit_comment_link', function ($link) {
    return str_replace('comment-edit-link','comment-edit-link btn btn-flat btn-xs btn-default', $link);
});

add_filter( 'comment_reply_link', function($link){
    return str_replace('comment-reply-link','comment-reply-link btn btn-flat btn-xs btn-light', $link);
});

add_filter('comment_form_defaults', function ($defaults) {
    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';
    $user_avatar = $user->exists() ? get_avatar($user->ID, 44) : '';

    $req = get_option('require_name_email');
    $label_req = ($req ? ' <span class="required">*</span>' : '');
    $placeholder_req = ($req ? '*' : '');
    $html_req = ($req ? ' aria-required="true" required="required"' : '');

    return array_merge($defaults, array(
        'comment_notes_before' => '',

        'logged_in_as' => '<div class="col-sm-12"><div class="logged-in-as">' . $user_avatar . '<a class="comment-reply-user" href="' . admin_url('profile.php') . '">' . $user_identity . '</a>' . '<a class="comment-reply-logout" href="' . wp_logout_url(apply_filters('the_permalink', get_permalink())) . '" title="' . esc_attr__('Log out of this account', 'colors-creative') . '"><span class="ion-log-out"></span></a>' . '</div></div>',
        'fields' => array(
            'author' => '<div class="col-sm-4"><div class="form-group"><label class="sr-only" for="author">' . __('Name', 'default') . $label_req . '</label><input id="author" name="author" class="form-control" type="text" placeholder="' . __('Name', 'default') . $placeholder_req . '" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $html_req . ' /></div></div>',
            'email' => '<div class="col-sm-4"><div class="form-group"><label class="sr-only" for="email">' . __('Email', 'default') . $label_req . '</label><input id="email" name="email" class="form-control" type="email" placeholder="' . __('Email', 'default') . $placeholder_req . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-describedby="email-notes"' . $html_req . ' /></div></div>',
            'url' => '<div class="col-sm-4"><div class="form-group"><label class="sr-only" for="url">' . __('Website', 'default') . '</label><input id="url" name="url" class="form-control" type="url" placeholder="' . __('Website', 'default') . '" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></div></div>',
        ),
        'comment_field' => '<div class="col-sm-12"><div class="form-group"><label class="sr-only" for="comment">' . _x('Comment', 'noun', 'default') . '</label><textarea id="comment" name="comment" class="form-control" placeholder="' . _x('Comment', 'noun', 'default') . '" rows="3" aria-required="true"></textarea></div></div>',

        'comment_notes_after' => '',

        'submit_field' => '<div class="col-sm-12"><p class="form-submit">%1$s %2$s</p></div>',
        'class_submit' => 'btn btn-flat btn-primary'
    ));
});

add_action('comment_form_top', function () {
    echo '<div class="row">';
});

add_action('comment_form', function () {
    echo '</div>';
});