<?php  
$list_header  = get_field('_jobs_list_header');

$meta_query = array();
$tax_query  = array();
$clear      = false;
$transient  = 'list';
$page       = get_query_var('paged') ? get_query_var('paged') : 1;
$orderby    = 'date';
$order      = 'DESC';

if( get_search_form_param('jtype') > 0 || get_search_form_param('jcity') != '' ) {
  $clear      = true;
  $transient  = 'list_search';
}

if( get_search_form_param('jcity') != '' ) {
  $type_query = array(
    'key'     => '_job_location_city',
    'value'   => get_search_form_param('jcity'),
    'type'    => 'CHAR',
    'compare' => '='
  );
  $meta_query[] = $type_query;
}
if( get_search_form_param('jtype') > 0 ) {
  $city_query = array(
    array(
      'taxonomy'  => 'job-type',
      'field'     => 'id',
      'terms'     => array( get_search_form_param('jtype') ),
      'operator'  => 'IN'
    )
  );
  $tax_query[] = $city_query;
}

$posts = _run_wp_query('job-offer', $transient, 8, $meta_query, $tax_query, $clear, $page, array(), $orderby, $order);
?>

<div class="jobs-list">
  <?php if($list_header) : ?><?php echo _section_header( $list_header ); ?><?php endif; ?>

  <?php if( $posts->have_posts() ) : ?>
    <div class="jobs-table">
      <div class="jobs-table-header row hidden-xs">
        <div class="col-sm-7">
          <div class="col-sm-6">
            <p><strong><?php pll_trans('Stanowisko'); ?></strong></p>
          </div>
          <div class="col-sm-6">
            <p><strong><?php pll_trans('Typ pracy'); ?></strong></p>
          </div>
        </div>
        <div class="col-sm-5">
          <div class="col-sm-4">
            <p><strong><?php pll_trans('Miejsce pracy'); ?></strong></p>
          </div>
          <div class="col-sm-4">
            <p><strong><?php pll_trans('Data publikacji'); ?></strong></p>
          </div>
          <div class="col-sm-4">
            <p><strong><?php pll_trans('Data składania apl.'); ?></strong></p>
          </div>
        </div>
      </div>

      <div class="jobs-table-body">
        <?php while($posts->have_posts()) : $posts->the_post(); ?>
        <?php  
        $position = get_field('_job_position') ? get_field('_job_position') : get_the_title();
        $type     = _get_post_terms_html(get_the_ID(), 'job-type');
        $city     = get_field('_job_location_city');
        $deadline = get_field('_job_deadline');
        ?>
        <a class="jobs-table-row row" href="<?php the_permalink(); ?>">
          <div class="col-sm-7">
            <div class="col-sm-6">
              <div class="col-xs-5 visible-xs">
                <p><strong><?php pll_trans('Stanowisko'); ?></strong></p>
              </div>
              <div class="col-xs-7">
                <p><?php echo esc_html( $position ); ?></p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="col-xs-5 visible-xs">
              <p><strong><?php pll_trans('Typ pracy'); ?></strong></p>
              </div>
              <div class="col-xs-7">
                <p><?php echo $type; ?></p>
              </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div class="col-sm-4">
              <div class="col-xs-5 visible-xs">
              <p><strong><?php pll_trans('Miejsce pracy'); ?></strong></p>
              </div>
              <div class="col-xs-7">
                <p><?php echo esc_html( $city ); ?></p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="col-xs-5 visible-xs">
              <p><strong><?php pll_trans('Data publikacji'); ?></strong></p>
              </div>
              <div class="col-xs-7">
                <p><?php the_time('d.m.Y'); ?></p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="col-xs-5 visible-xs">
              <p><strong><?php pll_trans('Data składania apl.'); ?></strong></p>
              </div>
              <div class="col-xs-7">
                <p><?php echo esc_html( $deadline ); ?></p>
              </div>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
        <?php echo _pagination( '', 2, $posts, $page); ?>
      </div>
    </div>
  <?php else : ?>
  <p><?php pll_trans('Nie znaleziono ofert'); ?></p>
  <?php endif; wp_reset_postdata(); ?>
</div>