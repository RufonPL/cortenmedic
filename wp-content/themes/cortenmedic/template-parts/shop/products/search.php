<div class="row products-search">
  <form class="row search-form-filter products-search-form">

    <div class="form-group col-xs-12">
      <label class="sr-only" for="psearch"><?php pll_trans('Szukaj w sklepie...'); ?></label>
      <input type="text" name="psearch" id="psearch" class="form-control" value="<?php echo get_search_form_param('psearch'); ?>" placeholder="<?php pll_trans('Szukaj w sklepie...'); ?>">
    
      <input type="hidden" name="pprice_min" value="<?php echo get_search_form_param('pprice_min'); ?>">
      <input type="hidden" name="pprice_max" value="<?php echo get_search_form_param('pprice_max'); ?>">
    </div>

    <div class="form-search-submit">
      <button type="submit"><i class="fa fa-search"></i></button>
    </div>

  </form>
</div>