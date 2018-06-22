<?php
/**
 * Template name: Kariera - Zdjęcia
 * The template for displaying career photos page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php $photos = get_field('_career_photos'); ?>

<main>
  <div class="container-fluid page-container career-photos-page">
    <?php if( $photos ) : ?>
      <?php foreach($photos as $photo) : ?>
        <?php  
        $image      = $photo['_image'];
        $header     = $photo['_header'];
        $text       = $photo['_text'];
        $link_type  = $photo['_link_type'];
        $link_inner = $photo['_link_inner'];
        $link_outer = $photo['_link_outer'];
        $link       = $link_type == 'page' ? get_permalink( $link_inner ) : $link_outer;
        ?>
        <div class="row career-photo">
          <div class="career-photo-mask"></div>
          <img src="<?php echo esc_url( $image['sizes']['slider-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
          <div class="container career-photo-content">
            <div class="career-photo-content-inner">
              <?php if( $header ) : ?><h2><strong><?php echo esc_html( $header ); ?></strong></h2><?php endif; ?>
              <?php if( $text ) : ?><h3 class="h1"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></h3><?php endif; ?>
              <?php if( $link ) : ?><a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary btn-medium"><?php pll_trans('Więcej'); ?></a><?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>