<?php 
$header         = get_sub_field('_header');
$nav_header     = get_sub_field('_nav_header');
$content_header = get_sub_field('_content_header');
$list           = get_sub_field('_list');
$bg_image       = get_sub_field('_bg_image');
?>

<?php if( $list ) : ?>
<div class="page-block page-block-steps">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

    <div class="pgs-box row">
      <?php if( $bg_image ) : ?>
      <img class="pgs-box-bg" src="<?php echo esc_url( $bg_image['sizes']['bigbox-image'] ); ?>" alt="<?php echo esc_attr( $bg_image['alt'] ); ?>">
      <?php endif; ?>
      <div class="pgs-box-left pull-left">
        <?php if( $nav_header ) : ?><h2><?php echo esc_html( $nav_header ); ?></h2><?php endif; ?>

        <ul class="nav nav-tabs" role="tablist">
          <?php $i=1; foreach($list as $item) : ?>
            <?php $name = $item['_name']; ?>
            <li<?php if( $i == 1 ) : ?> class="active"<?php endif; ?>><a href="#<?php echo 'item-'.$i.'-'.sanitize_title( $name ); ?>" role="tab" data-toggle="tab"><?php echo esc_html( $name ); ?></a></li>
          <?php $i++; endforeach; ?>
        </ul>
      </div>
      <div class="pgs-box-right">
        <?php if( $content_header ) : ?><h2><strong><?php echo esc_html( $content_header ); ?></strong></h2><?php endif; ?>

        <div class="tab-content">
        <?php $i=1; foreach($list as $item) : ?>
          <?php 
          $name = $item['_name']; 
          $tabs = $item['_tabs'];
          ?>
          <div class="tab-pane<?php if( $i == 1 ) : ?> active<?php endif; ?>" id="<?php echo 'item-'.$i.'-'.sanitize_title( $name ); ?>">

            <?php if( $tabs ) : ?>

              <ul class="nav nav-tabs nav-justified" role="tablist">
                <?php $j=1; foreach($tabs as $tab) : ?>
                  <?php $name = $tab['_name']; ?>
                  <li<?php if( $j == 1 ) : ?> class="active"<?php endif; ?>><a href="#<?php echo 'tab-'.$i.'-'.$j.'-'.sanitize_title( $name ); ?>" role="tab" data-toggle="tab"><span><?php echo esc_html( $name ); ?></span></a></li>
                <?php $j++; endforeach; ?>
              </ul>

              <div class="tab-content">
                <?php $j=1; foreach($tabs as $tab) : ?>
                  <?php 
                  $name = $tab['_name']; 
                  $text = $tab['_text'];
                  ?>
                  <div class="tab-pane<?php if( $j == 1 ) : ?> active<?php endif; ?>" id="<?php echo 'tab-'.$i.'-'.$j.'-'.sanitize_title( $name ); ?>">
                    <?php echo wp_kses( $text, array( 'br' => array() ) ); ?>
                  </div>
                <?php $j++; endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
        <?php $i++; endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>