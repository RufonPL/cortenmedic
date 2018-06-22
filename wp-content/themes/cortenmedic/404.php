<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container page-404">
    <div class="container">
      <div class="row">
        <article>
          <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header>
              <h1><?php pll_trans('Strona, której szukasz nie istnieje!'); ?></h1>
            </header>
          </div>
        </article>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>