<?php  
$cities = get_terms(array(
  'taxonomy' => 'institution-city',
  'hide_empty' => true
));
?>
<?php if( $cities ) : ?>
<div class="pricelist-filter">
  <form class="row pricelist-search-form search-form-filter">

    <div class="form-group col-sm-12">
      <label class="sr-only" for="pcity"><?php pll_trans('Wybierz miasto z listy...'); ?></label>
      <select name="pcity" id="pcity" class="selectpicker" data-size="5" data-live-search="true">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Wybierz miasto z listy...'); ?></option>
        <?php foreach($cities as $city) : ?>
        <option <?php if( get_search_form_param('pcity') == $city->term_id ) : ?>selected<?php endif; ?> value="<?php echo esc_attr( $city->term_id ); ?>"><?php echo esc_html( $city->name ); ?></option>
      <?php endforeach; ?>
      </select>
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>
<?php endif; ?>