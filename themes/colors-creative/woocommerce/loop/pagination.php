<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wp_query;

if ($wp_query->max_num_pages <= 1) {
    return;
}

$links = paginate_links(array(
    'base' => esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false)))),
    'format' => '',
    'add_args' => '',
    'current' => max(1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages,
    'prev_next' => false,
    'type' => 'array',
    'end_size' => 3,
    'mid_size' => 3
));

?>
<nav class="page-nav">
    <ul class="pagination pagination-sm">
        <?php foreach ($links as $link) : ?>
            <li<?php if (preg_match("/^<span class='page-numbers current'>/", $link)) echo ' class="active"' ?>>
                <?php om_html(str_replace("'", '"', $link)) ?>
            </li>
        <?php endforeach ?>
    </ul>
</nav>