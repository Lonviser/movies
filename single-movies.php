<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package movies
 */

get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">

            <div class="movie__content">
                <div class="movie__img">
                        <?php
                             $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                             $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                            the_post_thumbnail (array(400, 400)); 
                        ?>
                </div>
                <div class="movie__txt">
                <?php
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content', get_post_type() );

                        the_post_navigation(
                            array(
                                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Предыдущий фильм:', 'movies' ) . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Следующий фильм:', 'movies' ) . '</span> <span class="nav-title">%title</span>',
                            )
                        );

                        endwhile; // End of the loop.
                ?>
                </div>
            </div>
           
        </div>
	</main><!-- #main -->

<?php
get_footer();
