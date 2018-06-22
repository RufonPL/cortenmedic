<?php
/**
 * Template name: O nas
 * The template for displaying about us page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php $groups = get_field('_about_groups'); ?>

<main>
  <div class="container-fluid page-container about-us-page">

    <div class="container">
      <h1 class="h4 page-section-header"><?php the_title(); ?></h1>

      <?php if( $groups ) : ?>
      <div class="about-us-groups row">
        <?php $i=1; foreach($groups as $group) : ?>
          <?php  
          $image  = $group['_image'];
          $header = $group['_header'];
          $text   = $group['_text'];
          ?>
          <div class="about-us-group row">
            <div class="col-sm-6 <?php if( $i%2 != 0 ) : ?>pull-right aug-left<?php else : ?>aug-right<?php endif; ?>">
              <div class="aug-content">
                <?php if( $header ) : ?><p class="aug-header text-center"><?php echo esc_html( $header ); ?></p><?php endif; ?>

                <?php if( !_empty_content($text) ) : ?>
                <div class="aug-text"><?php echo ( $text ); ?></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-sm-6 <?php if( $i%2 == 0 ) : ?>aug-right<?php else : ?>aug-left<?php endif; ?>">
              <div class="aug-image">
                <?php if($image) : ?>
                <img src="<?php echo esc_url($image['sizes']['about-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php $i++; endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
      
  </div>
</main>

<?php get_footer(); ?>