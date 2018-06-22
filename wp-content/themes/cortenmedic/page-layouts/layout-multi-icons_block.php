<?php 
$icons  = get_sub_field('_icons');
$header = get_sub_field('_header');
?>
<?php if($icons) : ?>
<?php $count = count( $icons ); ?>
<div class="page-block page-block-multi-icons">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>
    
    <div class="pbmi-icons">
      <div class="pbmi-row row">
      <?php $i=1; foreach($icons as $icon) : ?>
        <?php  
        $fa         = $icon['_icon'];
        $header     = $icon['_header'];
        $text       = $icon['_text'];
        $link_type  = $icon['_link_type'];
        $link_inner = $icon['_link_inner'];
        $link_outer = $icon['_link_outer'];
        $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;

        switch( $count ) {
          case 1:
            $class = 'col-sm-12';
            break;
          case 2:
            $class = 'col-sm-6';
            break;
          default:
            $class = 'col-sm-4';
            break;
        }
        ?>
        <div class="pbmi-item <?php echo $class; ?>">
          <div class="pbmi-item-inner text-center">
            <div class="pbmi-item-icon">
              <i class="fa <?php echo esc_html( $fa ); ?>"></i>
            </div>
            <?php if( $header ) : ?><h4 class="text-uppercase"><strong><?php echo esc_html( $header ); ?></strong></h4><?php endif; ?>
            <?php if( $text ) : ?><div class="pbmi-text text-left"><?php echo wp_kses( _p2br( $text ), array( 'br' => array(), 'strong' => array(), 'ul' => array(), 'li' => array() ) ); ?></div><?php endif; ?>
            <?php if( $link ) : ?><a href="<?php echo esc_url( $link ); ?>" class="btn btn-info btn-wide">więcej</a><?php endif; ?>
          </div>
        </div>
      <?php if( $i%3 == 0 && $i != $count) : ?>
      </div>
      <div class="pbmi-row row">
      <?php endif; ?>
      <?php $i++; endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>