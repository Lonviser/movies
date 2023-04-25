<?php
/**
 * The header for our theme
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package movies
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<title>Movies</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<header class="header">
	<div class="container">
		<div class="header__content">
			<a class="header__link" href="/">
				<h1 class="header__title">
					Колекция фильмов
				</h1>
			</a>
		</div>
	</div>
</header>