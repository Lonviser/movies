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
<div class="wrapper">
    <div class="content">
        <main id="primary" class="site-main">
            <div class="container">
                <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
                    <input type="text" name="price_min" placeholder="Минимальная цена" />
                    <input type="text" name="price_max" placeholder="Максимальная цена" />
                    <input type="date" name="date_min" placeholder="Дата от" />
                    <input type="date" name="date_max" placeholder="Дата до" />
                    
                    <div>
                        <input type="radio" name="sort_by" value="date_asc" id="sort-by-date-asc">
                        <label for="sort-by-date-asc">Сортировать по дате (возрастание)</label>
                    </div>
                    <div>
                        <input type="radio" name="sort_by" value="date_desc" id="sort-by-date-desc">
                        <label for="sort-by-date-desc">Сортировать по дате (убывание)</label>
                    </div>
                    <div>
                        <input type="radio" name="sort_by" value="price_asc" id="sort-by-price-asc">
                        <label for="sort-by-price-asc">Сортировать по цене (возрастание)</label>
                    </div>
                    <div>
                        <input type="radio" name="sort_by" value="price_desc" id="sort-by-price-desc">
                        <label for="sort-by-price-desc">Сортировать по цене (убывание)</label>
                    </div>
    
                    <button>Применить</button>
                    <input type="hidden" name="action" value="myfilter">
                </form>
                <div id="response"></div>

                <h2 class="films__title">Все фильмы</h2>

                <?php
                $the_query = new WP_Query(
                    array(
                        'posts_per_page' => -1,
                        'post_type' => 'movies',
                    )
                );
                ?>
                <div class="catalog__items">
                    <?php while ($the_query->have_posts()):
                        $the_query->the_post(); ?>
                        <div class="catalog__item-card">
                            <div class="catalog__item-content">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                                    the_post_thumbnail(array(300, 400)); ?>
                                </a>
                                <h2 class="catalog__item-title">
                                    <?php the_title(); ?>
                                </h2>
                                <div class="catalog__item-description">
                                    <?php echo kama_excerpt(['maxchar' => 100]); ?>
                                </div>
                                <div class="catalog__item-country">
                                    <?php
                                    $termini = get_the_terms($post, array('lands'));
                                    if ($termini && !is_wp_error($termini)) {
                                        $termini_massiv = array();
                                        foreach ($termini as $termin) {
                                            // добавление элемента в массив
                                            $termini_massiv[] = '<a href="' . get_term_link($termin) . '" title="Перейти к ' . esc_attr($termin->name) . '">' . $termin->name . '</a>';
                                        }
                                        $termini_a_hrefs = join(", ", $termini_massiv);
                                        echo '<div class="catalog__item-title">Страны: <span class="catalog__item-text">' . $termini_a_hrefs . '</span></div>';
                                    }

                                    ?>

                                </div>
                                <div class="catalog__item-genres">
                                    <h3 class="catalog__item-title">Жанры:</h3>
                                    <?php
                                    $genres = get_the_terms(get_the_ID(), 'genres');
                                    if ($genres && !is_wp_error($genres)):
                                        ?>
                                        <ul class="genres">
                                            <?php foreach ($genres as $genre): ?>
                                                <li><a href="<?php echo esc_url(get_term_link($genre)); ?>"><?php echo esc_html($genre->name); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                                <div class="catalog__itme-actors">
                                    <h3 class="catalog__item-title">Актёры:</h3>

                                    <?php
                                    $actors = get_the_terms(get_the_ID(), 'actors');
                                    if ($actors && !is_wp_error($actors)):
                                        ?>
                                        <ul class="vendors">
                                            <?php foreach ($actors as $actor): ?>
                                                <li><a href="<?php echo esc_url(get_term_link($actor)); ?>"><?php echo esc_html($actor->name); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="catalog__item-date">
                                    <h3 class="catalog__item-title">Дата выхода:</h3>
                                    <? the_field('vremya_seansa'); ?>
                                </div>
                                <div class="catalog__item-price">
                                    <h3 class="catalog__item-title">Стоимость:</h3>
                                    <span>
                                        <? the_field('stoimost'); ?> рублей
                                    </span>
                                </div>
                            </div>
                            <a class="catalog__button" href="<?php the_permalink(); ?>">
                                <div>Подробнее →</div>
                            </a>
                            <div>
                            </div>

                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
        </main>
    </div>
    <footer class="footer">
        <?php get_footer(); ?>
    </footer>
</div>