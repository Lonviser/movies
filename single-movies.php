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

    <div class="wrapper">
        <div class="content">
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
                        ?>
                         <div class="catalog__item-genres">
                                <h3 class="catalog__item-title">Жанры:</h3>
                                        <?php
                                            $genres = get_the_terms( get_the_ID(), 'genres' );
                                            if ( $genres && ! is_wp_error( $genres ) ) :
                                            ?>
                                                <ul class="genres">
                                                    <?php foreach ( $genres as $genre ) : ?>
                                                        <li><a href="<?php echo esc_url( get_term_link( $genre ) ); ?>"><?php echo esc_html( $genre->name ); ?></a></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                        <?php endif; ?>
                                    </div>

                                    <div class="catalog__itme-actors">
                                    <h3 class="catalog__item-title">Актёры:</h3>

                                        <?php
                                        $actors = get_the_terms( get_the_ID(), 'actors' );

                                        if ( $actors && ! is_wp_error( $actors ) ) :
                                            ?>
                                            <ul class="vendors">
                                                <?php foreach ( $actors as $actor ) : ?>
                                                    <li><a href="<?php echo esc_url( get_term_link( $actor ) ); ?>"><?php echo esc_html( $actor->name ); ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="catalog__item-date">
                                    <h3 class="catalog__item-title">Дата выхода:</h3>
                                        <?the_field('vremya_seansa');?>
                                    </div>
                                    <div class="catalog__item-price">
                                        <h3 class="catalog__item-title">Стоимость:</h3>
                                        <span><?the_field('stoimost');?> рублей</span>
                                    </div>
                        <?php
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
        </div>
        <footer class="footer">
         <?php get_footer(); ?>
        </footer>
    </div>
	

