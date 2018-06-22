<?php 
$header = get_sub_field('_header');
$cities = get_terms(array(
  'taxonomy' => 'institution-city',
  'hide_empty' => true
));
?>

<div class="page-block page-block-map">
  <div class="container">

    <?php if( $cities ) : ?>
    <div class="institutions-map-search">
      <h2><?php pll_trans('Znajdź placówkę!'); ?></h2>

      <div class="institutions-map-search-form search-form-filter">
        <div class="form-group">
          <label class="sr-only" for="mcity"><?php pll_trans('Miasto'); ?></label>
          <select name="mcity" id="mcity" class="selectpicker" data-size="5">
            <option data-hidden="false" value="" class="first-option"><?php pll_trans('Miasto'); ?></option>
            <?php if( $cities ) : ?>
              <?php foreach($cities as $city) : ?>
                <option value="<?php echo absint( $city->term_id ); ?>"><?php echo esc_html( $city->name ); ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <span class="sff-error"><?php pll_trans('Wybierz miasto'); ?></span>
        </div>

        <div class="form-group">
          <label class="sr-only" for="minstitution"><?php pll_trans('Centrum medyczne'); ?></label>
          <select name="minstitution" id="minstitution" class="selectpicker" disabled data-size="5">
            <option data-hidden="false" value="" class="first-option"><?php pll_trans('Centrum medyczne'); ?></option>
            
          </select>
          <span class="sff-error"><?php pll_trans('Wybierz centrum medyczne'); ?></span>
        </div>
        
        <div class="form-search-submit">
          <button class="btn btn-primary"><?php pll_trans('Szukaj na mapie'); ?></button>
        </div>
      </div>

    </div>
    <?php endif; ?>
    
    <div id="google-map" class="institutions-map" data-map-type="multi"></div>

  </div>
</div>