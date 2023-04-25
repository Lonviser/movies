<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package movies
 */

get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">
        <?php
     $the_query = new WP_Query( array('posts_per_page'=> -1,
                                     'post_type'=>'movies',
                                 )); 
                                 ?>
     <div class="catalog__items">
         <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
             
                                            <div class="catalog__item-card">
                                                <div class="catalog__item-content"> 
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php
                                                                $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                                                                the_post_thumbnail (array(300, 400)); 
                                                            ?>
                                                    </a>
                                                        <div class="catalog__item-title">
                                                            <?php the_title();?>
                                                        </div>
                                                    
                                                 </div>
                                                 <div>
                                                    
                                                 </div>
                                                    <a class="catalog__button" href="<?php the_permalink(); ?>" > 
                                                        <div >Подробнее →</div>
                                                    </a>
                                                </div>    
         <?php
         endwhile;
         ?>
        </div>

	</main>

<?php
get_footer();
