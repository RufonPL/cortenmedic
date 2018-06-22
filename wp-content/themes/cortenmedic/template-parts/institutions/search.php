<?php
$header  = isset( $params['header'] ) ? sanitize_text_field( $params['header'] ) : '';
$doctors = isset( $params['doctors'] ) ? absint( $params['doctors'] ) : '';
$groups = get_terms(array(
  'taxonomy' => 'services-groups',
  'hide_empty' => true
));
$cities = get_terms(array(
  'taxonomy' => 'institution-city',
  'hide_empty' => true
));

$types = _get_meta_values('_institution_type');


$input_init = array( get_search_form_param('igroup'), get_search_form_param('icity') );
$dependency_init = $doctors ? array( get_search_form_param('ispec'), get_search_form_param('iaddress') ) : array( get_search_form_param('iclinic'), get_search_form_param('iaddress') );
?>

<div class="institutions-search">
  <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

<form class="row search-form-filter<?php if( $doctors ) : ?> doctors-search-form<?php else : ?> institutions-search-form<?php endif; ?>" data-input-init="<?php echo htmlentities( json_encode( $input_init ) ); ?>" data-dependency-init="<?php echo htmlentities( json_encode( $dependency_init ) ); ?>">

    <div class="form-group col-xs-3">
      <label class="sr-only" for="igroup"><?php pll_trans('Grupa usług'); ?></label>
      <select name="igroup" id="igroup" class="selectpicker" data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Grupa usług'); ?></option>
        <?php if( $groups ) : ?>
          <?php foreach($groups as $group) : ?>
            <option value="<?php echo absint( $group->term_id ); ?>"><?php echo esc_html( $group->name ); ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <?php if( $doctors ) : ?>
      <div class="form-group col-xs-3">
        <label class="sr-only" for="ispec"><?php pll_trans('Specjalizacja'); ?></label>
        <select name="ispec" id="ispec" class="selectpicker" disabled data-size="5">
          <option data-hidden="false" value="" class="first-option"><?php pll_trans('Specjalizacja'); ?></option>
        
        </select>
        <span class="sff-error"><?php pll_trans('Wybierz specjalizację'); ?></span>
      </div>
    <?php else : ?>
      <div class="form-group col-xs-3">
        <label class="sr-only" for="iclinic"><?php pll_trans('Poradnia'); ?></label>
        <select name="iclinic" id="iclinic" class="selectpicker" disabled data-size="5">
          <option data-hidden="false" value="" class="first-option"><?php pll_trans('Poradnia'); ?></option>
        
        </select>
        <span class="sff-error"><?php pll_trans('Wybierz poradnię'); ?></span>
      </div>
    <?php endif; ?>

    <div class="form-group col-xs-3">
      <label class="sr-only" for="icity"><?php pll_trans('Miasto'); ?></label>
      <select name="icity" id="icity" class="selectpicker" data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Miasto'); ?></option>
        <?php if( $cities ) : ?>
          <?php foreach($cities as $city) : ?>
            <option <?php if( get_search_form_param('icity') == $city->term_id ) : ?>selected<?php endif; ?> value="<?php echo absint( $city->term_id ); ?>"><?php echo esc_html( $city->name ); ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group col-xs-3">
      <?php if( $doctors ) : ?>
        <label class="sr-only" for="iaddress"><?php pll_trans('Adres'); ?></label>
        <select name="iaddress" id="iaddress" class="selectpicker" disabled data-size="5">
          <option data-hidden="false" value="" class="first-option"><?php pll_trans('Adres'); ?></option>
        </select>
        <span class="sff-error"><?php pll_trans('Wybierz adres'); ?></span>
      <?php else : ?>
        <label class="sr-only" for="itype"><?php pll_trans('Rodzaj placówki'); ?></label>
        <select name="itype" id="itype" class="selectpicker" data-size="5">
          <option data-hidden="false" value="" class="first-option"><?php pll_trans('Rodzaj placówki'); ?></option>
          <?php if( $types ) : ?>
            <?php foreach($types as $type) : ?>
              <option <?php if( get_search_form_param('itype') == $type ) : ?>selected<?php endif; ?> value="<?php echo esc_html( $type ); ?>"><?php echo esc_html( $type ); ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      <?php endif; ?>
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>