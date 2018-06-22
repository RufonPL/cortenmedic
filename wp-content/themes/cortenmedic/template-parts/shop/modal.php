<div class="modal fade shop-modal" id="shop-modal" tabindex="-1" role="dialog" aria-labelledby="shop-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Zamknij</span></button>
        <h5 class="modal-title" id="shop-modal-label"></h5>
      </div>
      <div class="modal-body text-center">
        <a href="#" class="btn btn-info btn-wide" data-dismiss="modal"><?php pll_trans('Kontynuuj zakupy'); ?></a>
        <a href="<?php echo CORTEN_SHOP::shop_links('cart'); ?>" class="btn btn-primary btn-wide"><?php pll_trans('Przejdź do koszyka'); ?></a>
      </div>
    </div>
  </div>
</div>