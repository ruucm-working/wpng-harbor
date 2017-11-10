<?php global $wp_query; ?>

<header>
    <div class="section section-header">
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-sm-5 col-md-4">
                        <h1 class="search-title">
                            <?php esc_html_e('Search Results for:', 'colors-creative') ?>
                            <span class="sr-only"><?php the_search_query() ?></span>
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>

<div class="section section-zero">
    <div class="container">
        <div class="section-content">

            <?php if (!have_posts()) : ?>
                <div class="alert alert-warning">
                    <?php esc_html_e('Sorry, no results were found.', 'colors-creative'); ?>
                </div>
            <?php endif; ?>

            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title(sprintf('<h2 class="entry-title h3"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                    </header>

                    <?php if (in_array(get_post_type(), array('post', 'project'), true)) : ?>
                        <div class="entry-meta">
                            <small class="text-muted">
                                <?php get_template_part('parts/entry-meta'); ?>
                            </small>
                        </div>
                    <?php endif ?>

                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile ?>

            <?php if ($wp_query->max_num_pages > 1) : ?>
                <nav class="post-nav">
                    <ul class="pager">
                        <li class="previous"><?php next_posts_link('<span class="arrow-left"></span> ' . esc_html__('Previous', 'colors-creative')) ?></li>
                        <li class="next"><?php previous_posts_link(esc_html__('Next', 'colors-creative') . ' <span class="arrow-right"></span>') ?></li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>