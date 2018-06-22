<?php
function get_map_data() {
  if( !wp_verify_nonce(  $_POST['nonce'], 'check_map_nonce' ) ) {
		wp_send_json( array('error', 'error') );
	}

  $map_id = absint( $_POST['map_id'] );
  $type   = sanitize_text_field( $_POST['type'] );
  $coords = array();

  switch($type) {
    case 'single':
      if( get_post_status( $map_id ) != 'publish' ) {
        wp_send_json( array('error', 'error') );
      }

      $location = get_field('_institution_location', $map_id);

      if( $location ) {
        $lng = $location['lng'];
        $lat = $location['lat'];

        $coords[] = array(
          'lng' => $lng,
          'lat' => $lat
        );

        wp_send_json( array('ok', $coords) );
      }
      break;
    case 'multi':
      $posts = _run_wp_query( 'institution', 'list');
      if( $posts->have_posts() ) {
        while($posts->have_posts()) { $posts->the_post();
          $institution_id = get_the_ID();
          $location = get_field('_institution_location', $institution_id);
          $address  = get_field('_institution_address', $institution_id);
          $contacts = get_field('_institution_contacts', $institution_id);

          $info = '<div class="map-info-content"><h4><strong>'.esc_html( get_the_title() ).'</strong></h4>';

          if( !_empty_content( $address ) ) {
            $info .= '<p class="map-info-address"><strong>'.pll_trans('Adres:', true).'</strong><br>'.wp_kses($address, array( 'br' => array() ) ).'</p>';
          }

          $info .= '<div class="map-info-contacts">'._institution_contacts($contacts).'</div>';

          $info .= '<div class="text-center"><a class="btn btn-small btn-primary" href="'.get_permalink( $institution_id ).'">'.pll_trans('Przejdź do placówki', true).'</a></div></div>';

          if( $location ) {
            $lng = $location['lng'];
            $lat = $location['lat'];

            $coords[] = array(
              'lng'   => $lng,
              'lat'   => $lat,
              'info'  => $info,
              'boundsExclude' => stripos( $location['address'], 'Warszawa' ) || stripos( $location['address'], 'Warsaw' ) !== false ? 0 : 1,
              'id'    => absint( get_the_ID() )
            );
          }
        }
        wp_send_json( array('ok', $coords) );
      }; wp_reset_postdata();
      break;
  }

}
add_action('wp_ajax_get_map_data', 'get_map_data');
add_action('wp_ajax_nopriv_get_map_data', 'get_map_data');

function get_form_data() {
  if( !wp_verify_nonce(  $_POST['nonce'], 'check_form_nonce' ) ) {
		wp_send_json( array('error', 'error') );
	}

  $form_type  = sanitize_text_field( $_POST['type'] );
  $field_id   = sanitize_text_field( $_POST['fieldId'] );
  $value      = absint( $_POST['value'] );

  $tax_query  = array();
  $post_in    = array();
  $html       = '';

  switch( $form_type ) {
    case 'institutions':
      $option_text = pll_trans('Poradnia', true);
      $post_type = 'clinic';
      $tax_query = array(
        array(
          'taxonomy'  => 'services-groups',
          'field'     => 'id',
          'terms'     => array( $value ),
          'operator'  => 'IN'
        )
      );
      break;
    case 'doctors':
      switch( $field_id ) {
        case 'igroup':
          $option_text = pll_trans('Specjalizacja', true);

          $specs = get_terms(array(
            'taxonomy'    => 'specialization',
            'meta_query'  => array(
              array(
                'key' => '_specialization_group',
                'value' => '"'.$value.'"',
                'type'  => 'CHAR',
                'compare' => 'LIKE'
              )
            )
          ));

          $html .= '<option data-hidden="false" value="" class="first-option">'.pll_trans($option_text, true).'</option>';
          if( $specs ) {
            foreach($specs as $spec) {
              $html .= '<option value="'.absint( $spec->term_id ).'">'.esc_html( $spec->name ).'</option>';
            }
          };

          wp_send_json( array('ok', $html) );

          $post_type = 'clinic';
          $tax_query = array(
            array(
              'taxonomy'  => 'services-groups',
              'field'     => 'id',
              'terms'     => array( $value ),
              'operator'  => 'IN'
            )
          );
          break;
        case 'icity':
          $option_text = 'Adres';
          $post_type = 'institution';
          $tax_query = array(
            array(
              'taxonomy'  => 'institution-city',
              'field'     => 'id',
              'terms'     => array( $value ),
              'operator'  => 'IN'
            )
          );
          break;
      }
      break;
    case 'offer':
      $option_text = 'Usługa';
      $post_type = 'service';
      $tax_query = array(
        array(
          'taxonomy'  => 'services-groups',
          'field'     => 'id',
          'terms'     => array( $value ),
          'operator'  => 'IN'
        )
      );
      break;
    case 'institutions-map':
      $option_text = 'Centrum medyczne';
      $post_type = 'institution';
      $tax_query = array(
        array(
          'taxonomy'  => 'institution-city',
          'field'     => 'id',
          'terms'     => array( $value ),
          'operator'  => 'IN'
        )
      );
      break;
    default:
      wp_send_json( array('error', 'error') );
      break;
  }

  $posts = new WP_Query( array(
     'post_type'      => $post_type,
     'posts_per_page' => -1,
     'post_status'    => 'publish',
     'tax_query'      => $tax_query,
     'post__in'       => $post_in
  ) );

  $html .= '<option data-hidden="false" value="" class="first-option">'.pll_trans($option_text, true).'</option>';
  if( $posts->have_posts() ) {
    while($posts->have_posts()) { $posts->the_post();
      $html .= '<option value="'.absint( get_the_ID() ).'">'.esc_html( get_the_title() ).'</option>';
    }
  }; wp_reset_postdata();

  wp_send_json( array('ok', $html) );
}
add_action('wp_ajax_get_form_data', 'get_form_data');
add_action('wp_ajax_nopriv_get_form_data', 'get_form_data');

function get_contact_branches() {
  if( !wp_verify_nonce(  $_POST['nonce'], 'check_form_nonce' ) ) {
		wp_send_json( array('error', 'error') );
	}

  $branches = isset( $_POST['value'] ) ? unserialize( $_POST['value'] ) : false;
  $html = '';

  if( !$branches ) {
    wp_send_json( array('error', 'error') );
  }

  if( !is_array( $branches ) ) {
    wp_send_json( array('error', 'error') );
  }

  $html .= '<option data-hidden="false" value="" class="first-option">'.pll_trans('- Wybierz z listy -', true).'</option>';
  foreach($branches as $branch_id) {
    $html .= '<option value="'.absint( $branch_id ).'">'.esc_html( get_the_title( $branch_id ) ).'</option>';
  }

  wp_send_json( array('ok', $html) );
}
add_action('wp_ajax_get_contact_branches', 'get_contact_branches');
add_action('wp_ajax_nopriv_get_contact_branches', 'get_contact_branches');

function get_branch_content() {
  if( !wp_verify_nonce(  $_POST['nonce'], 'check_form_nonce' ) ) {
		wp_send_json( array('error', 'error') );
	}

  $branch = absint( $_POST['value'] );
  $html   = _get_template_part('branch', 'contact', array('branch_id' => $branch), true);

  wp_send_json( array('ok', $html) );
}
add_action('wp_ajax_get_branch_content', 'get_branch_content');
add_action('wp_ajax_nopriv_get_branch_content', 'get_branch_content');
?>