<?php $user_id = $params['user_id']; ?>
<h2><?php _e('Customer details', 'rfswp'); ?></h2>

<?php wp_nonce_field( '_customer_fields', '_customer_fields_nonce' ); ?>

<table class="form-table">
  <tbody>

    <tr>
      <th>
        <label for="_customer_type"><?php _e('Customer type', 'rfswp'); ?></label>
      </th>
      <td>
        <select name="_customer_type" id="_customer_type">
          <option value="individual"<?php if( CORTEN_SHOP::customer_data_item('type', $user_id) != 'company' ) : ?> selected<?php endif; ?>><?php _e('Natural person', 'rfswp'); ?></option>
          <option value="company"<?php if( CORTEN_SHOP::customer_data_item('type', $user_id) == 'company' ) : ?> selected<?php endif; ?>><?php _e('Company', 'rfswp'); ?></option>
        </select>
      </td>
    </tr>

    <tr class="customer-company-field">
      <th>
        <label for="_customer_company_name"><?php _e('Company name', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_company_name" id="_customer_company_name" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('company_name', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-company-field">
      <th>
        <label for="_customer_company_nip"><?php _e('Company NIP', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_company_nip" id="_customer_company_nip" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('company_nip', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label for="_customer_phone"><?php _e('Phone number', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_phone" id="_customer_phone" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('phone', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-personal-field">
      <th>
        <label for="_customer_personal_nip"><?php _e('NIP', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_personal_nip" id="_customer_personal_nip" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('personal_nip', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label><?php _e('Address details', 'rfswp'); ?></label>
      </th>
      <td></td>
    </tr>

    <tr>
      <th>
        <label for="_customer_address_street"><?php _e('Street', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_address_street" id="_customer_address_street" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_street', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label for="_customer_address_number"><?php _e('Number', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_address_number" id="_customer_address_number" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_number', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label for="_customer_address_city"><?php _e('City', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_address_city" id="_customer_address_city" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_city', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label for="_customer_address_postcode"><?php _e('Postcode', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_address_postcode" id="_customer_address_postcode" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_postcode', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label for="_customer_address_country"><?php _e('Country', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_address_country" id="_customer_address_country" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('address_country', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr>
      <th>
        <label><?php _e('Shipping address', 'rfswp'); ?></label>
      </th>
      <td></td>
    </tr>

    <tr>
      <th>
        <label for="_customer_shipment_different"><?php _e('Ship to different address', 'rfswp'); ?></label>
      </th>
      <td>
        <select name="_customer_shipment_different" id="_customer_shipment_different">
          <option value="yes"<?php if( CORTEN_SHOP::customer_data_item('shipment_different', $user_id) == 'yes' ) : ?> selected<?php endif; ?>><?php _e('Yes', 'rfswp'); ?></option>
          <option value="no"<?php if( CORTEN_SHOP::customer_data_item('shipment_different', $user_id) != 'yes' ) : ?> selected<?php endif; ?>><?php _e('No', 'rfswp'); ?></option>
        </select>
      </td>
    </tr>
    
    <tr class="customer-shipment-field">
      <th>
        <label for="_customer_shipment_street"><?php _e('Street', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_shipment_street" id="_customer_shipment_street" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_street', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-shipment-field">
      <th>
        <label for="_customer_shipment_number"><?php _e('Number', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_shipment_number" id="_customer_shipment_number" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_number', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-shipment-field">
      <th>
        <label for="_customer_shipment_city"><?php _e('City', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_shipment_city" id="_customer_shipment_city" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_city', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-shipment-field">
      <th>
        <label for="_customer_shipment_postcode"><?php _e('Postcode', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_shipment_postcode" id="_customer_shipment_postcode" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_postcode', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>

    <tr class="customer-shipment-field">
      <th>
        <label for="_customer_shipment_country"><?php _e('Country', 'rfswp'); ?></label>
      </th>
      <td>
        <input type="text" name="_customer_shipment_country" id="_customer_shipment_country" value="<?php echo esc_html( CORTEN_SHOP::customer_data_item('shipment_country', $user_id) ); ?>" class="regular-text">
      </td>
    </tr>
    
  </tbody>
</table>
