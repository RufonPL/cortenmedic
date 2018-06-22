<?php
/**
 * Template name: Kariera - Główna
 * The template for displaying career page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$header = get_field('_career_main_header');
$boxes  = get_field('_career_main_boxes'); 
?>

<main>
  <div class="container-fluid page-container career-main-page">
    <div class="container">
      <?php if( $boxes ) : ?>
      <?php $found = count( $boxes ); ?>
      <div class="career-boxes row">
        <div class="career-boxes-group row">
          <div class="career-boxes-header pull-left">
            <?php if( $header ) : ?>
              <h1><?php echo wp_kses( _p2br($header), array( 'br' => array(), 'strong' => array() ) ); ?></h1>
            <?php endif; ?>
          </div>
          <?php $i=1; foreach($boxes as $box) : ?>
            <?php  
            $image      = $box['_image'];
            $text       = $box['_text'];
            $link_type  = $box['_link_type'];
            $link_inner = $box['_link_inner'];
            $link_outer = $box['_link_outer'];
            $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;
            ?>
            <?php if($image) : ?>
              <div class="career-box pull-left">
                <?php if( $link ) : ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
                <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php if( $text ) : ?>
                <div class="career-box-text">
                  <div class="career-box-text-inner">
                    <?php echo wp_kses( _p2br($text), array( 'br' => array(), 'strong' => array() ) ); ?>
                  </div>
                </div>
                <?php endif; ?>
                <?php if( $link ) : ?></a><?php endif; ?>
              </div>

              <?php if( $i == 2 ) : ?>
              </div>
              <div class="career-boxes-group row">
              <?php endif; ?>
            <?php endif; ?>
          <?php $i++; endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="career-main-button text-center">
        <a href="<?php echo esc_url( get_permalink( _page_template_id('career') ) ); ?>" class="btn btn-primary"><?php pll_trans('Sprawdź aktualne oferty pracy'); ?></a>
      </div>

    </div>
  </div>
</main>

<?php get_footer(); ?>