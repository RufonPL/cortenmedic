<form class="form-horizontal" action="<?php echo esc_url( get_permalink().get_query_var('account_tab') ); ?>" method="post" name="my-details-form" id="my-details-form">
  <div class="checkout-form row">

    <div class="row checkout-customer-type">
      <div class="form-group text-center">
        <label class="radio-inline">
          <input type="radio" name="customer-type" value="individual"<?php if( CORTEN_SHOP::customer_data_item('type') != 'company' ) : ?> checked<?php endif; ?>>
          <span><?php pll_trans('Osoba fizyczna'); ?></span>
        </label>
        <label class="radio-inline">
          <input type="radio" name="customer-type" value="company"<?php if( CORTEN_SHOP::customer_data_item('type') == 'company' ) : ?> checked<?php endif; ?>>
          <span><?php pll_trans('Firma'); ?></span>
        </label>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 checkout-form-left">

        <p class="form-group-header"><strong><?php pll_trans('Dane osobowe'); ?><span class="checkout-company-field"> / <?php pll_trans('Nazwa firmy'); ?></span></strong></p>

        <div class="form-group">
          <label for="customer-first-name" class="control-label col-sm-3"><?php pll_trans('Imię'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-first-name" name="customer-first-name" placeholder="<?php pll_trans('Podaj imię'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('first_name') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-last-name" class="control-label col-sm-3"><?php pll_trans('Nazwisko'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-last-name" name="customer-last-name" placeholder="<?php pll_trans('Podaj nazwisko'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('last_name') ); ?>">
          </div>
        </div>

        <div class="form-group checkout-personal-field">
          <label for="customer-personal-nip" class="control-label col-sm-3"><?php pll_trans('Numer NIP'); ?></label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="customer-personal-nip" name="customer-personal-nip" placeholder="<?php pll_trans('Podaj nr NIP'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('personal_nip') ); ?>">
          </div>
        </div>

        <div class="form-group checkout-company-field">
          <label for="customer-company-name" class="control-label col-sm-3"><?php pll_trans('Nazwa firmy'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-company-name" name="customer-company-name" placeholder="<?php pll_trans('Podaj nazwę firmy'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('company_name') ); ?>">
          </div>
        </div>

        <div class="form-group checkout-company-field">
          <label for="customer-company-nip" class="control-label col-sm-3"><?php pll_trans('NIP firmy'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-company-nip" name="customer-company-nip" placeholder="<?php pll_trans('Podaj nr NIP'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('company_nip') ); ?>">
          </div>
        </div>

      </div>
      <div class="col-sm-6 checkout-form-right">

        <p class="form-group-header"><strong><?php pll_trans('Dane kontaktowe'); ?></strong></p>

        <div class="form-group">
          <label for="customer-phone" class="control-label col-sm-3"><?php pll_trans('Telefon'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-phone" name="customer-phone" placeholder="<?php pll_trans('Podaj nr telefonu'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('phone') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-email" class="control-label col-sm-3"><?php pll_trans('Email'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-email" name="customer-email" placeholder="<?php pll_trans('Podaj adres email'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('email') ); ?>" readonly>
          </div>
        </div>

      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 checkout-form-left">
        
        <p class="form-group-header"><strong><?php pll_trans('Dane adresowe'); ?></strong></p>

        <div class="form-group">
          <label for="customer-address-street" class="control-label col-sm-3"><?php pll_trans('Ulica'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-address-street" name="customer-address-street" placeholder="<?php pll_trans('Podaj ulicę'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_street') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-address-number" class="control-label col-sm-3"><?php pll_trans('Numer lokalu'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-address-number" name="customer-address-number" placeholder="<?php pll_trans('Podaj nr lokalu / domu'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_number') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-address-city" class="control-label col-sm-3"><?php pll_trans('Miasto'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-address-city" name="customer-address-city" placeholder="<?php pll_trans('Podaj miasto'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_city') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-address-postcode" class="control-label col-sm-3"><?php pll_trans('Kod pocztowy'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-address-postcode" name="customer-address-postcode" placeholder="<?php pll_trans('Podaj kod pocztowy'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_postcode') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="customer-address-country" class="control-label col-sm-3"><?php pll_trans('Kraj'); ?><span>*</span></label>
          <div class="col-sm-9">
            <input type="text" class="form-control required" id="customer-address-country" name="customer-address-country" placeholder="<?php pll_trans('Podaj kraj'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_country') ); ?>">
          </div>
        </div>

      </div>
      <div class="col-sm-6 checkout-form-right">

        <p class="form-group-header"><strong><?php pll_trans('Adres dostawy'); ?></strong></p>

        <div class="form-group customer-shipment">
          <label for="customer-shipment-different"><input type="checkbox" id="customer-shipment-different" name="customer-shipment-different"<?php if( CORTEN_SHOP::customer_data_item('shipment_different') == 'yes' ) : ?> checked<?php endif; ?>> <span><?php pll_trans('Adres dostawy jest inny niż adres zamawiającego'); ?></span></label>
        </div>

        <div class="checkout-shipment-fields">

          <div class="form-group">
            <label for="customer-shipment-street" class="control-label col-sm-3"><?php pll_trans('Ulica'); ?><span>*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control required" id="customer-shipment-street" name="customer-shipment-street" placeholder="<?php pll_trans('Podaj ulicę'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_street') ); ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="customer-shipment-number" class="control-label col-sm-3"><?php pll_trans('Numer lokalu'); ?><span>*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control required" id="customer-shipment-number" name="customer-shipment-number" placeholder="<?php pll_trans('Podaj nr lokalu / domu'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_number') ); ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="customer-shipment-city" class="control-label col-sm-3"><?php pll_trans('Miasto'); ?><span>*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control required" id="customer-shipment-city" name="customer-shipment-city" placeholder="<?php pll_trans('Podaj miasto'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_city') ); ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="customer-shipment-postcode" class="control-label col-sm-3"><?php pll_trans('Kod pocztowy'); ?><span>*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control required" id="customer-shipment-postcode" name="customer-shipment-postcode" placeholder="<?php pll_trans('Podaj kod pocztowy'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_postcode') ); ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="customer-shipment-country" class="control-label col-sm-3"><?php pll_trans('Kraj'); ?><span>*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control required" id="customer-shipment-country" name="customer-shipment-country" placeholder="<?php pll_trans('Podaj kraj'); ?>" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_country') ); ?>">
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="row checkout-submit">
      <button type="submit" class="btn btn-primary btn-lg save-my-details" value="save"><?php pll_trans('Zapisz'); ?><?php echo CORTEN_SHOP::cart_loader(); ?></button>
    </div>

  </div>

  <div class="alert text-center alert alert-success"><?php pll_trans('Zmiany zostały zapisane'); ?></div>

</form>