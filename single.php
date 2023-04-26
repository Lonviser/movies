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

           <?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		endwhile; 
		?>

           
        </div>
	</main><!-- #main -->
        </div>
        <footer class="footer">
         <?php get_footer(); ?>
        </footer>
    </div>
	

