<?php  
$branch_id      = $params['branch_id'];
$institution_id = get_field('_contact_institution', $branch_id);
$contact_form   = get_field('_contact_form', $branch_id);
$pull           = get_field('_contact_details_pull', $branch_id);
$contact_details = get_field('_contact_details', $branch_id);

$is_pulled      = $pull && ($institution_id > 0 && get_post_status( $institution_id ) == 'publish');
$not_pulled     = !$pull && $contact_details;
?>

<?php if( $is_pulled || $not_pulled ) : ?>
  <?php
  if( $is_pulled ) {
    $cities             = wp_get_post_terms( $institution_id, 'institution-city' );
    $city               = $cities ? $cities[0]->name : '';
    $contacts           = get_field('_institution_contacts', $institution_id);
    $contacts_employees = get_field('_institution_contacts_employees', $institution_id);
    $address            = get_field('_institution_address', $institution_id);
    $header             = esc_html( $city.' '.get_the_title( $institution_id ) );
  }
  if( $not_pulled ) {
    $contacts           = get_field('_contact_details__institution_contacts', $branch_id);
    $contacts_employees = get_field('_contact_details__institution_contacts_employees', $branch_id);
    $address            = get_field('_contact_details__institution_address', $branch_id);
    $header             = get_field('_contact_details_header', $branch_id);
  }
  ?>
  <div class="container contact-branch-details">
    <?php echo _section_header($header, true); ?>
    <div class="row">
      <div class="col-sm-4 contact-branch-left">
          <?php echo _institution_contacts_employees($contacts_employees); ?>
      </div>
      <div class="col-sm-4 contact-branch-middle">
        <?php if( !_empty_content( $address ) ) : ?>
          <strong><?php pll_trans('Adres:'); ?></strong> <?php echo wp_kses( $address, array() ); ?>
        <?php endif; ?>
        <?php echo _institution_contacts($contacts, 'phones', 999); ?>
        <?php echo _institution_contacts($contacts, 'emails', 999); ?>
      </div>
      <?php if( $is_pulled ) : ?>
      <div class="col-sm-4 contact-branch-right">
        <a href="<?php echo esc_url( get_permalink( $institution_id ) ); ?>"><strong class="text-uppercase"><?php pll_trans('Zobacz na mapie'); ?><i class="fa fa-angle-double-right"></i></strong></a>
      </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php if( $contact_form ) : ?>
<div class="contact-branch-form">
  <div class="container">
    <h2 class="text-center"><strong>Formularz kontaktowy</strong></h2>
    <div class="contact-branch-contact-form">
      <?php echo do_shortcode( $contact_form ); ?>
    </div>
  </div>
</div>
<?php endif; ?>