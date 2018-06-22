<?php  
$packages_page_id = _page_template_id('packages', true);

$know_how_header  = get_field('_packages_know_how_header', $packages_page_id);
$know_how         = get_field('_packages_know_how', $packages_page_id);
$form_header      = get_field('_packages_form_header', $packages_page_id);
$form             = get_field('_packages_form', $packages_page_id);
?>

<div class="packages-bottom col-sm-offset-3">
  <div class="row">
    <div class="col-sm-6 packages-know-how-col">
      <?php if( $know_how_header ) : ?>
        <?php echo _section_header($know_how_header, false); ?>
      <?php endif; ?>
      <?php if( $know_how ) : ?>
      <div class="packages-know-how">
        <?php foreach($know_how as $item) : ?>
          <?php  
          $icon = $item['_icon'];
          $text = $item['_text'];
          ?>
          <div class="packages-know-how-item">
            <div class="packages-know-how-icon pull-left">
              <?php if( $icon ) : ?><i class="fa <?php echo esc_attr( $icon ); ?>"></i><?php endif; ?>
            </div>
            <p><?php echo $text; ?></p>
            <div class="packages-know-how-icon-down text-center">
              <i class="fa fa-chevron-down"></i>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="col-sm-6 packages-form-col">
      <?php if( $form ) : ?>
        <div class="row">
          <div class="packages-form">
            <?php if( $form_header ) : ?>
              <p class="packages-form-header">
                <?php echo wp_kses(_p2br($form_header), array( 'br' => array(), 'strong' => array() ) ); ?>
              </p>
              <?php echo do_shortcode( $form ); ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>