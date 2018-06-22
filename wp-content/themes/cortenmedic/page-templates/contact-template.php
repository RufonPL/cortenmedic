<?php
/**
 * Template name: Kontakt
 * The template for displaying contact page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php 
$header = get_field('_contact_page_header');
$groups = get_field('_contact_groups');
?>

<main>
  <div class="container-fluid page-container contact-page">
    <div class="container">

      <div class="row contact-page-top">
        <?php echo _section_header( pll_trans('Centra Medyczne', true) ); ?>
        <?php echo _header_links(3, 'contact'); ?>
      </div>

      <div class="row contact-page-content">
        <?php if( $header ) : ?>
          <?php echo _section_header( $header ); ?>
        <?php endif; ?>

        <?php if( $groups ) : ?>
        <div class="contact-groups">
          <p><?php pll_trans('Wybierz grupę kontaktu:'); ?></p>
          <select name="contact_group" id="contact_group" class="selectpicker" data-size="5">
            <option data-hidden="false" value="" class="first-option"><?php pll_trans('- Wybierz z listy -'); ?></option>
            <?php foreach($groups as $group) : ?>
              <?php 
              $name     = $group['_name'];
              $branches = $group['_branches'];
              ?>
              <option value="<?php echo serialize($branches); ?>"><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="contact-groups">
          <p><?php pll_trans('Wybierz dział do kontaktu:'); ?></p>
          <select name="contact_branch" id="contact_branch" class="selectpicker" disabled data-size="5">
            <option data-hidden="false" value="" class="first-option"><?php pll_trans('- Wybierz z listy -'); ?></option>
          </select>
        </div>
        <?php endif; ?>

        <div class="contact-loader"></div>

      </div>
      
    </div>

    <div class="contact-branch-content"><?php //echo _get_template_part('branch', 'contact', array('branch_id' => 495), true); ?></div>

  </div>
</main>

<?php get_footer(); ?>