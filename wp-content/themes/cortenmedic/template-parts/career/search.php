<?php  
$header  = isset( $params['header'] ) ? sanitize_text_field( $params['header'] ) : '';
$types = get_terms(array(
  'taxonomy' => 'job-type',
  'hide_empty' => true
));
//$cities = _get_meta_values('_job_location_city');
$cities = get_terms(array(
  'taxonomy' => 'institution-city',
  'hide_empty' => true
));
?>

<div class="jobs-search">
  <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

  <form class="row institutions-search-form search-form-filter">

    <div class="form-group col-sm-6">
      <label class="sr-only" for="jtype"><?php pll_trans('Typ pracy'); ?></label>
      <select name="jtype" id="jtype" class="selectpicker" data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Typ pracy'); ?></option>
        <?php if( $types ) : ?>
          <?php foreach($types as $type) : ?>
            <option<?php if( get_search_form_param('jtype') == $type ) : ?> selected<?php endif; ?> value="<?php echo absint( $type->term_id ); ?>"><?php echo esc_html( $type->name ); ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group col-sm-6">
      <label class="sr-only" for="jcity"><?php pll_trans('Miejsce pracy'); ?></label>
      <select name="jcity" id="jcity" class="selectpicker" data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Miejsce pracy'); ?></option>
        <?php if( $cities ) : ?>
          <?php foreach($cities as $city) : ?>
            <option<?php if( get_search_form_param('jcity') == $city->name ) : ?> selected<?php endif; ?> value="<?php echo esc_html( $city->name ); ?>"><?php echo esc_html( $city->name ); ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>