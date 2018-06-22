<?php  
$header = get_sub_field('_header');
$prizes = get_sub_field('_prizes');
?>
<?php if( $prizes ) : ?>
<?php $count = count( $prizes ); ?>
<div class="page-block page-block-prizes">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>
    
    <div class="page-block-prizes-row row">
    <?php $i=1; foreach($prizes as $prize) : ?>
      <?php  
      $logo = $prize['_logo'];
      $text = $prize['_text'];
      $link = get_post_meta($logo['ID'], '_wpmf_gallery_custom_image_link', true);
      ?>
      <div class="col-sm-4 page-block-prize">
        <div class="pbp-image text-center">
          <?php if($logo) : ?>
          <?php if( $link ) : ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
            <img src="<?php echo esc_url($logo['sizes']['medium']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>">
          <?php if( $link ) : ?></a><?php endif; ?>
          <?php endif; ?>
        </div>
        <?php if( !_empty_content( $text ) ) : ?>
        <blockquote><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></blockquote>
        <?php endif; ?>
      </div>
    <?php if( $i%3==0 && $i!=$count) : ?>
    </div>
    <div class="page-block-prizes-row row">
    <?php endif; ?>
    <?php $i++; endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>