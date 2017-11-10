<?php
use Essentials\Data\Options;

if (!om_is_essentials()) {
    $show_categories = true;
    $show_tags = false;
} else {
    $show_categories = Options::get('project_categories_show');
    $show_tags = Options::get('project_tags_show');
}
?>

<div class="entry-single-meta">
    <div class="row">
        <?php if ($show_categories) : ?>
            <div class="col-sm-10 col-sm-offset-1">
                <div class="entry-info">
                    <?php om_categories_links('<span class="entry-categories"><span class="ion-folder"></span> ', '</span>') ?>
                </div>
            </div>
        <?php endif ?>

        <?php if ($show_tags) : ?>
            <?php echo get_the_term_list(0, 'project_tag', '<div class="clearfix"></div><div class="col-sm-10 col-sm-offset-1"><div class="entry-tags"><span class="ion-pound"></span> ', ', ', '</div></div>'); ?>
        <?php endif ?>
    </div>
</div>