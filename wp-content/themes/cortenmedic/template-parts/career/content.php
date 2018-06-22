<?php  
$position = get_field('_job_position') ? get_field('_job_position') : get_the_title();
$subtitle = get_field('_job_subtitle');
$ref_no   = get_field('_job_ref_no');
$company  = get_field('_job_company');
$city     = get_field('_job_location_city');
$address  = get_field('_job_location_address');
$image    = get_field('_job_image');
$text     = get_field('_job_text');

$form     = get_field('_job_offer_apply_form', _page_template_id('career'));
?>

<div class="job-offer-content">

  <?php if(  _is_job_apply_page() ) : ?>

    <div class="job-apply-page">
      <div class="job-apply-info container">
        <p><?php pll_trans('Stanowisko:'); ?> <strong><?php echo esc_html( $position ); ?></strong></p>
        <?php if( $ref_no ) : ?><p><?php pll_trans('Numer referencyjny oferty:'); ?> <strong><?php echo esc_html( $ref_no ); ?></strong></p><?php endif; ?>
        <?php if( $company ) : ?><p><?php pll_trans('Firma:'); ?> <strong><?php echo esc_html( $company ); ?></strong></p><?php endif; ?>
      </div>
      
      <?php if( $form ) : ?>
      <div class="job-apply-form">
        <?php echo do_shortcode( $form ); ?>
      </div>
      <?php endif; ?>
    </div>
  
  <?php else : ?>

    <div class="job-offer-single-header<?php if( !$image ) : ?> job-offer-no-image<?php endif; ?>">
      <?php if($image) : ?>
      <img src="<?php echo esc_url($image['sizes']['slider-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
      <?php endif; ?>
      <div class="job-offer-single-title container">
        <h1 class="text-uppercase"><strong><?php echo esc_html( $position ); ?></strong></h1>
        <?php if( $subtitle ) : ?><p><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
      </div>
      <?php if( $address || $city ) : ?><div class="job-offer-single-address"><div class="container"><?php pll_trans('Miejsce pracy:'); ?> <strong><?php echo esc_html( $city ); ?> <?php echo esc_html( $address ); ?></strong></div></div><?php endif; ?>
    </div>
    <?php if( !_empty_content( $text ) ) : ?>
    <div class="container job-offer-single-text content-text"><?php echo ( $text ); ?></div>
    <?php endif; ?>

    <div class="container job-offer-aplly">
      <a href="?apply=<?php the_ID(); ?>" class="btn btn-primary btn-large"><?php pll_trans('Aplikuj'); ?></a>
    </div>

  <?php endif; ?>

</div>