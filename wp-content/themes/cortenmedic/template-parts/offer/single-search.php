<?php  
$term_id  = isset( $params['term_id'] ) ? absint( $params['term_id'] ) : 0;
$header   = get_field('_offer_search_header', _page_template_id('offer'));
$groups   = get_terms( array(
  'taxonomy'    => 'services-groups',
  'post_types'  => 'service',
  'hide_empty'  => true
) );

$input_init = array( $term_id );
$dependency_init = array( get_search_form_param('oservice') );
?>

<div class="single-offer-search">

  <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

  <form class="row offer-search-form search-form-filter" data-input-init="<?php echo htmlentities( json_encode( $input_init ) ); ?>" data-dependency-init="<?php echo htmlentities( json_encode( $dependency_init ) ); ?>">

    <div class="form-group col-xs-6">
      <label class="sr-only" for="ogroup"><?php pll_trans('Grupa usług'); ?></label>
      <select name="ogroup" id="ogroup" class="selectpicker" data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Grupa usług'); ?></option>
        <?php if( $groups ) : ?>
          <?php foreach($groups as $group) : ?>
            <option<?php if( get_search_form_param('ogroup') == $group->term_id || $group->term_id == $term_id ) : ?> selected<?php endif; ?> value="<?php echo absint( $group->term_id ); ?>"><?php echo esc_html( $group->name ); ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group col-xs-6">
      <label class="sr-only" for="oservice"><?php pll_trans('Usługa'); ?></label>
      <select name="oservice" id="oservice" class="selectpicker" disabled data-size="5">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Usługa'); ?></option>
        
      </select>
      <span class="sff-error"><?php pll_trans('Wybierz usługę'); ?></span>
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>