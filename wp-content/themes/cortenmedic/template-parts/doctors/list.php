<?php
$meta_query = array('relation' => 'OR');
$tax_query  = array();
$clear      = true;
$transient  = 'list';

$page           = get_query_var('page') > 1 ? get_query_var('page') : 1;
$ppp            = $page * 12;
$list_header    = get_field('_doctors_list_header');
$city_found     = false;
$spec_found     = false;

$is_spec_query  = absint( get_search_form_param('igroup') ) > 0 && absint( get_search_form_param('ispec') ) > 0;
$is_city_query  = absint( get_search_form_param('icity') ) > 0 && absint( get_search_form_param('iaddress') ) > 0;

if( $is_spec_query ) {
  //$institutions = _institutions_by_service_group( absint( get_search_form_param('igroup') ) );
  // $specs = get_terms(array(
  //   'taxonomy'    => 'specialization',
  //   'meta_query'  => array(
  //     array(
  //       'key' => '_specialization_group',
  //       'value' => '"'.absint( get_search_form_param('igroup') ).'"',
  //       'type'  => 'CHAR',
  //       'compare' => 'LIKE'
  //     )
  //   )
  // ));

  // if( $specs ) {
  //   $spec_ids = array();
  //   foreach($specs as $spec) {
  //     $spec_ids[] = $spec->term_id;
  //   }

    $tax_query[] = array(
      'taxonomy'  => 'specialization',
      'field'     => 'id',
      'terms'     => array( absint( get_search_form_param('ispec') ) ),
      'operator'  => 'IN'
    );

  //   $spec_found = true;
  // }
}

if( $is_city_query) {
  // $institutions = _institutions_list_query();
  // $institutions_ids = array();

  // if( $institutions->have_posts()) {
  //   while( $institutions->have_posts()) { $institutions->the_post();
  //     $institutions_ids[] = get_the_ID();
  //   }
  // }; wp_reset_postdata();

  // if( $institutions_ids ) {
  //   foreach($institutions_ids as $id) {
      $meta_query[] = array(
        'key'     => '_doctor_institution',
        'value'   => '"'.absint( get_search_form_param('iaddress') ).'"',
        'type'    => 'CHAR',
        'compare' => 'LIKE'
      );
    //}

    //$city_found = true;
  //}

}

$posts = _run_wp_query( 'doctor', $transient, $ppp, $meta_query, $tax_query, $clear);
$doctors_pages  = ceil( $posts->max_num_pages );
?>

<div class="doctors-list">
  <?php if($list_header) : ?><?php echo _section_header( $list_header ); ?><?php endif; ?>
  <?php if( $posts->have_posts() ) : ?>
  <?php $found = $posts->found_posts ?>
    <div class="row doctors-row">
      <?php $i=1; while($posts->have_posts()) : $posts->the_post(); ?>
        <?php  
        $image            = get_field('_doctor_image');
        $title            = get_field('_doctor_title');
        $name             = get_the_title();
        $specializations  = doctor_specializations( get_the_ID() );
        ?>
        <div class="col-md-3 col-sm-4 col-xs-6 doctor-item">
          <div class="doctor-item-inner">
            <a href="<?php the_permalink(); ?>">
              <div class="doctor-item-mask"></div>
              <?php if($image) : ?>
                <img src="<?php echo esc_url( $image['sizes']['doctor-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
              <?php endif; ?>
              <div class="doctor-item-info">
                <?php if( $title ) : ?><p class="doctor-title"><?php echo esc_html( $title ); ?></p><?php endif; ?>
                <p class="doctor-name"><?php echo esc_html( $name ); ?></p>
                <?php if( $specializations ) : ?><p class="doctor-specialization"><?php echo esc_html( $specializations ); ?></p><?php endif; ?>
              </div>
            </a>
          </div>
        </div>
      <?php if( $i%4 == 0 && $i!=$found ) : ?>
      <!--</div>
      <div class="row doctors-row">-->
      <?php endif; ?>
      <?php $i++; endwhile; ?>
    </div>
  <?php else : ?>
  <p><?php pll_trans('Nie znaleziono lekarzy'); ?></p>
  <?php endif; wp_reset_postdata(); ?>

  <?php if( $doctors_pages > 1 ) : ?>
  <div class="text-center load-more-doctors">
    <form method="get">
      <input type="hidden" name="page" value="<?php echo $page + 1; ?>">
      <input type="hidden" name="igroup" value="<?php echo get_search_form_param('igroup'); ?>">
      <input type="hidden" name="ispec" value="<?php echo get_search_form_param('ispec'); ?>">
      <input type="hidden" name="icity" value="<?php echo get_search_form_param('icity'); ?>">
      <input type="hidden" name="iaddress" value="<?php echo get_search_form_param('iaddress'); ?>">
      <button type="submit" class="btn btn-info">Zobacz więcej</button>
    </form>
  </div>
  <?php endif; ?>
</div>