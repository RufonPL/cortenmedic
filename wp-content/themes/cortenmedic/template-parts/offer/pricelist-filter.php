<?php  
$data = isset( $params['data'] ) ? $params['data'] : array();
?>
<?php if( $data ) : ?>
<div class="pricelist-filter">
  <form class="row pricelist-search-form search-form-filter">

    <div class="form-group col-sm-12">
      <label class="sr-only" for="pfilter"><?php pll_trans('Szukaj usługi...'); ?></label>
      <select name="pfilter" id="pfilter" class="selectpicker" data-size="5" data-live-search="true">
        <option data-hidden="false" value="" class="first-option"><?php pll_trans('Szukaj usługi...'); ?></option>
        <?php foreach($data as $group_id => $group_data) : ?>
          <?php $services = $group_data['services']; ?>
          <?php if( $services ) : ?>
            <?php foreach($services as $service) : ?>
              <?php $pricelist = $service['pricelist']; ?>
              <?php if( $pricelist ) : ?>
                <?php $i=1; foreach($pricelist as $item) : ?>
                <?php 
                $option_name  = $group_data['name'].' | '.$service['name'].' | '.$item['name']; 
                $option_id    = absint( $group_id ).'-'.absint( $service['id'] ).'-'.$i;
                ?>
                <option<?php if( get_search_form_param('pfilter') == $option_id ) : ?> selected<?php endif; ?> value="<?php echo $option_id; ?>"><?php echo esc_html( $option_name ); ?></option>
                <?php $i++; endforeach; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>
<?php endif; ?>