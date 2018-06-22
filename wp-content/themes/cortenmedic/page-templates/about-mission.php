<?php
/**
 * Template name: Pacjenta leczymy
 * The template for displaying about mission page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$image      = get_field('_amp_entry_image');
$image_sign = get_field('_amp_entry_image_sign');
$slogan     = get_field('_amp_entry_slogan');
$list       = get_field('_amp_entry_list');
$list_bg_offset = get_field('_amp_entry_list_bg_offset');
?>

<main>
  <div class="container-fluid page-container about-mission-page">

    <div class="amp-entry">
      <div class="container">
        <div class="col-sm-5 amp-entry-left amp-entry-side">
          <?php if($image) : ?>
          <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
          <?php endif; ?>
        </div>
        <div class="col-sm-7 amp-entry-right amp-entry-side">
          <?php if( !_empty_content( $image_sign ) ) : ?>
          <h4 class="amp-sign"><?php echo wp_kses( _p2br($image_sign), array( 'br' => array(), 'strong' => array() ) ); ?></h4>
          <?php endif; ?>
          
          <?php if( $slogan ) : ?><h1><strong><?php echo esc_html( $slogan ); ?></strong></h1><?php endif; ?>

          <?php if( $list ) : ?>
          <div class="amp-list">
            <div class="amp-list-bg" style="width: <?php echo absint( 100 + $list_bg_offset ); ?>%"></div>
            <?php foreach($list as $item) : ?>
              <?php  
              $header = $item['_header'];
              $text   = $item['_text'];
              ?>
              <?php if( $header || !_empty_content( $text ) ) : ?>
              <div class="amp-list-item row">
                <div class="amp-list-item-left pull-left">
                  <?php if( $header ) : ?><h4><?php echo esc_html( $header ); ?></h4><?php endif; ?>
                </div>
                <div class="amp-list-item-right">
                  <?php if( !_empty_content( $text ) ) : ?><p><?php echo wp_kses($text, array( 'br' => array() ) ); ?></p><?php endif; ?>
                </div>
              </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>

    <?php get_template_part( 'content', 'page' ); ?>
      
  </div>
</main>

<?php get_footer(); ?>