<?php
$address            = get_field('_institution_address');
$opening_hours      = get_field('_institution_opening_hours');
$contacts           = get_field('_institution_contacts');
$contacts_employees = get_field('_institution_contacts_employees');
$location           = get_field('_institution_location');
?>
<div class="row institution-single-general">
  <div class="col-sm-6 institution-single-info">
    <?php echo _section_header( pll_trans('Informacje podstawowe', true) ); ?>
    <p>
      <strong><?php bloginfo('name') ?></strong>
      <?php if( !_empty_content( $address ) ) : ?>
      <br>
      <?php echo wp_kses( $address, array( 'br' => array() ) ); ?>
      <?php endif; ?>
    </p>
    <?php echo _institution_contacts($contacts, 'phones', 999); ?>
    <?php echo _institution_contacts($contacts, 'emails', 999); ?>
    <?php if( !_empty_content( $opening_hours ) ) : ?>
    <p>
      <strong><?php pll_trans('Godziny otwarcia'); ?></strong>
      <br>
      <?php echo wp_kses( $opening_hours, array( 'br' => array() ) ); ?>
    </p>
    <?php endif; ?>
    <?php echo _institution_contacts_employees($contacts_employees); ?>
  </div>
  <div class="col-sm-6 institution-single-location">
    <?php if( $location ) : ?>
    <?php echo _section_header( pll_trans('Lokalizacja', true) ); ?>

    <div id="google-map" class="institution-map" data-map-id="<?php the_ID(); ?>" data-map-type="single"></div>
    <?php endif; ?>
  </div>
</div>