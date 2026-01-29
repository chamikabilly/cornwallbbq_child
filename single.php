<?php
/**
 * The template for displaying single blog posts
 * Child theme override with custom styling
 *
 * @package Miheli_Solutions_Child
 */

get_header();
?>

<main id="primary" class="site-main single-blog-post">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-wrapper'); ?>>
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-post-featured-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Header -->
                <header class="single-post-header">
                    <h1 class="single-post-title"><?php the_title(); ?></h1>
                    
                    <div class="single-post-meta">
                        <span class="post-author">
                            <i class="fa-solid fa-user"></i>
                            <?php echo get_the_author(); ?>
                        </span>
                        <span class="seperator">|</span>
                        <span class="post-date">
                            <i class="fa-solid fa-calendar"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="seperator">|</span>
                        <span class="post-category">
                            <i class="fa-solid fa-folder"></i>
                            <?php the_category(', '); ?>
                        </span>
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <span class="seperator">|</span>
                            <span class="post-comments">
                                <i class="fa-solid fa-comments"></i>
                                <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Post Content -->
                <div class="single-post-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'miheli-solutions'),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <!-- Post Tags -->
                <?php if (has_tag()) : ?>
                    <div class="single-post-tags">
                        <i class="fa-solid fa-tags"></i>
                        <?php the_tags('', '', ''); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Navigation -->
                <div class="single-post-navigation">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post) : ?>
                        <div class="nav-previous">
                            <a href="<?php echo get_permalink($prev_post); ?>">
                                <i class="fa-solid fa-arrow-left"></i>
                                <div class="nav-content">
                                    <span class="nav-label">Previous Post</span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($next_post) : ?>
                        <div class="nav-next">
                            <a href="<?php echo get_permalink($next_post); ?>">
                                <div class="nav-content">
                                    <span class="nav-label">Next Post</span>
                                    <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                </div>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </article>

            <!-- Comments Section -->
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
