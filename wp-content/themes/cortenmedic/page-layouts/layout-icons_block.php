<?php 
$icons  = get_sub_field('_icons');
$header = get_sub_field('_header');
?>
<?php if($icons) : ?>
<?php $count = count( $icons ); ?>
<div class="page-block page-block-icons">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>
    
    <div class="page-block-icons-row row">
    <?php foreach($icons as $icon) : ?>
      <?php  
      $image  = $icon['_icon'];
      $header = $icon['_header'];
      $text   = $icon['_text'];
      
      switch( $count ) {
        case 2:
          $class = 'col-sm-6';
          break;
        case 3:
          $class = 'col-sm-4';
          break;
        default:
          $class = 'col-sm-12';
          break;
      }
      ?>
      <div class="page-block-icon <?php echo $class; ?>">
        <div class="page-block-icon-inner text-center">
          <div class="page-block-icon-image">
          <?php if($image) : ?>
            <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
          <?php endif; ?>
          </div>
          <?php if( $header ) : ?><h4 class="text-uppercase"><?php echo esc_html( $header ); ?></h4><?php endif; ?>
          <?php if( $text ) : ?><p><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p><?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>