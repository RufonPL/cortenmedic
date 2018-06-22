<?php
/**
 * This function will return theme logo html, which is either an image or site name
 * @param n/a
 * @return html
*/
function _theme_logo() {
  if( function_exists( 'the_custom_logo' ) ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo           = wp_get_attachment_image_src( $custom_logo_id , 'theme-logo' );

    if( has_custom_logo() ) {
      $logo_alt   = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true);
      $theme_logo = '<img src="'.esc_url( $logo[0] ).'" alt="'.esc_attr( $logo_alt ).'">';
    }else {
      $theme_logo = '<h1>'.esc_html( get_bloginfo('name') ).'</h1>';
    }
  }else {
    $theme_logo = '<h1>'.esc_html( get_bloginfo('name') ).'</h1>';
  }

  return $theme_logo;
}

function _page_loader() {
  return '<div class="page-loader"><img src="'.esc_url( get_template_directory_uri() ).'/dist/assets/images/loader.svg" alt="Loading..."></div>';
}

/**
 * This function will render header links html
 * @param n/a
 * @return html
*/
function _header_links($limit = 999, $type = 'header') {
  $links = get_field('_header_links', 'option');
  $html = '';

  switch($type) {
    case 'side':
      $class = 'side-links';
      break;
    default:
      $class = 'nav-inline header-links';
  }

  if( $links ) {
    $html .= '<ul class="nav text-right '.$class.'">';
    $i=1; foreach($links as $h_link) {
      $icon       = $h_link['_icon'];
      $text       = $h_link['_text'];
      $link_type  = $h_link['_link_type'];
      $link_inner = $h_link['_link_inner'];
      $link_outer = $h_link['_link_outer'];

      $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;

      $html .= '
      <li class="text-center">
        <a href="'.esc_url( $link ).'" title="'.esc_html( $text ).'">
          <i class="fa '.esc_html( $icon ).'"></i>
          <p><strong>'.esc_html( $text ).'</strong></p>
        </a>
      </li>';
      
      if( $i == $limit ) break;
    $i++; }
    $html .= '</ul>';
  }

  return $html;
}

/**
 * This function will render social links html
 * @param n/a
 * @return html
*/
function social_links() {
	$social = get_field('_social_links', 'option');	
	$html 	= '';
	
	if($social) {
		$html .= '<ul class="social-links list-unstyled list-inline">';
		foreach($social as $links) {
			$icon  = $links['_icon'];
			$link  = $links['_link'];
			
			$html .= '<li class="social-link"><a target="_blank" class="social-'.esc_html($icon).'" href="'.esc_url($link).'"><i class="fa fa-'.esc_html($icon).'"></i></a></li>';
		}
		$html .= '</ul>';
	}
	return $html;
}

/**
 * This function will render font resizer html
 * @param n/a
 * @return html
*/
function font_resizer() {
  $html = '
  <div id="font-resizer">
    <p class="no-resize">'.pll_trans('Czcionka:', true).' <span class="font-size-item" id="font-size-default">A</span> <span class="font-size-item" id="font-size-md">A<sup>+</sup></span> <span class="font-size-item" id="font-size-lg">A<sup>++</sup></span></p>
  </div>
  ';

  return $html;
}

/**
 * This function will render footer menu html
 * @param $menu_location (string)
 * @return html
*/
function _get_footer_menu($menu_location) {
	$menu_exists = wp_nav_menu(array(
		'theme_location'  => $menu_location, 
		'echo'            => false,
		'fallback_cb'     => '__return_false'
	));

	if( empty($menu_exists) ) return false;

  $menus 	    = get_nav_menu_locations();
  $menu_id 	  = $menus[$menu_location] ;
  $menu_data 	= wp_get_nav_menu_object( $menu_id );
  $html       = '';

  $args = array(
    'theme_location' 	=> $menu_location, 
    'container_class' => 'navbar-footer', 
    'menu_class' 		  => 'footer-nav list-unstyled',
    'fallback_cb'		  => '',
    'menu_id' 			  => 'footer-'.$menu_id.'-menu',
    'echo'				    => false,
    'walker' 			    => new WP_Bootstrap_Navwalker()
  ); 

  $html .= '<div class="footer-menu">';
  $html .= '<h4 class="footer-nav-header text-uppercase">'.esc_html( $menu_data->name ).'</h4>';
  $html .= wp_nav_menu($args);
  $html .= '</div>';
  
  return $html;
}

/**
 * This function will return class for footer menu column
 * @param $menus_found (int)
 * @return string
*/
function _footer_menu_cols_class($menus_found) {
  switch($menus_found) {
    case 2:
        $class = 'col-sm-6';
      break;
    case 3:
        $class = 'col-sm-4';
      break;
    case 4:
        $class = 'col-sm-3';
      break;
    case 5:
        $class = 'col-sm-20';
      break;
    default:
        $class = 'col-sm-12';
      break;
  }

  return $class;
}

/**
 * This function will render section header html
 * @param $header (string)
 * @return html
*/
function _section_header($header, $sm = false, $ksesbrstrong = false) {
  $sm_class = $sm ? 'h4 page-section-header-sm' : '';
  $text = $ksesbrstrong ? wp_kses( $header, array( 'br' => array(), 'strong' => array() ) ) : esc_html( $header );
  return '<h3 class="page-section-header '.$sm_class.'">'.$text.'</h3>';
}

/**
 * This function will render institution contacts html
 * @param $option (string) - all | phones | emails
 * @param $contacts (array -  acf repeater)
 * @param $limit (int)
 * @return html
*/
function _institution_contacts($contacts, $option = 'all', $limit = 3) {
  $html = '';

  if( $contacts ) {
    $html .= '<ul class="institution-item-contacts list-unstyled">';
    $c=1; foreach($contacts as $contact) {
      $type     = $contact['_type'];
      $prefix   = $contact['_prefix_'.$type];
      $value    = $contact['_'.$type];
      $linked   = $contact['_linked'];

      $prefix   = $option == 'all' ? strtolower( $prefix ) : '<strong>'.$prefix.'</strong>';
      $link     = $type == 'email' ? 'mailto:'.antispambot( esc_html( $value ) ) : 'tel:'.esc_html( preg_replace( '/[-\s+]/', '', $value ) );
      $text     = $type == 'email' ? antispambot( esc_html( $value ) ) : esc_html( $value );

      if( $option == 'all' || ($option == 'phones' && $type == 'phone') || ($option == 'emails' && $type == 'email') ) {

        $html .= '<li>'.$prefix.' ';

        if( $linked ) {
          $html .= '<a href="'.$link.'">';
        }
        $html .= $text;
        if( $linked ) {
          $html .= '</a>';
        }

        $html .= '</li>';

      }
    
      if( $c == $limit ) break;
    $c++; }
    $html .= '</ul>';
  }

  return $html;
}

/**
 * This function will render institution contacts employees html
 * @param $contacts (array -  acf repeater)
 * @return html
*/
function _institution_contacts_employees($contacts) {
  $html = '';

  if( $contacts ) {
    $html .= '<ul class="institution-item-contacts list-unstyled">';
    $c=1; foreach($contacts as $contact) {
      $name     = $contact['_name'];
      $position = $contact['_position'];
      $data     = $contact['_contact'];

      if( $position || $name ) {
        $html .= '<p>';
        if( $position ) {
          $html .= '<strong>'.esc_html($position).'</strong><br>';
        }
        if( $name ) {
          $html .= esc_html( $name );
        }
        $html .= '</p>';
      }

      if( $data ) {
        foreach($data as $d) {
          $type     = $d['_type'];
          $prefix   = $d['_prefix_'.$type];
          $value    = $d['_'.$type];
          $linked   = $d['_linked'];

          $prefix   = '<strong>'.$prefix.'</strong>';
          $link     = $type == 'email' ? 'mailto:'.antispambot( esc_html( $value ) ) : 'tel:'.esc_html( preg_replace( '/[-\s+]/', '', $value ) );
          $text     = $type == 'email' ? antispambot( esc_html( $value ) ) : esc_html( $value );

          $html .= '<li>'.$prefix.' ';

          if( $linked ) {
            $html .= '<a href="'.$link.'">';
          }
          $html .= $text;
          if( $linked ) {
            $html .= '</a>';
          }

          $html .= '</li>';
        }
      }
    
    $c++; }
    $html .= '</ul>';
  }

  return $html;
}

function _institutions_list_query() {
  $meta_query = array('relation' => 'AND');
  $tax_query  = array();
  $clear      = false;
  $transient  = 'list';

  if( get_search_form_param('igroup') > 0 || get_search_form_param('iclinic') > 0 || get_search_form_param('icity') > 0 || get_search_form_param('itype') != '' ) {
    $clear      = true;
    $transient  = 'list_search';
  }

  if( get_search_form_param('iclinic') > 0 ) {
    $clinic_query = array(
      'key'     => '_institutions_clinics',
      'value'   => '"'.get_search_form_param('iclinic').'"',
      'type'    => 'CHAR',
      'compare' => 'LIKE'
    );
    $meta_query[] = $clinic_query;
  }
  if( get_search_form_param('itype') != '' ) {
    $type_query = array(
      'key'     => '_institution_type',
      'value'   => get_search_form_param('itype'),
      'type'    => 'CHAR',
      'compare' => '='
    );
    $meta_query[] = $type_query;
  }
  if( get_search_form_param('icity') > 0 ) {
    $city_query = array(
      array(
        'taxonomy'  => 'institution-city',
        'field'     => 'id',
        'terms'     => array( get_search_form_param('icity') ),
        'operator'  => 'IN'
      )
    );
    $tax_query[] = $city_query;
  }

  $posts = _run_wp_query( 'institution', $transient, -1, $meta_query, $tax_query, $clear);

  return $posts;
}

/**
 * This function will return array of pricelists services by groups
 * @param n/a
 * @return array
*/
function _get_pricelist_data() {
  //$data = get_transient('_offer_pricelist_data');
  $data = false;

  if( $data === false ) {

    $data = array();
  
    $groups   = get_terms( array(
      'taxonomy'    => 'services-groups',
      'post_types'  => 'service',
      'hide_empty'  => true
    ) );

    if( $groups ) {
      foreach($groups as $group) {
        $group_name = get_field('_sg_header', $group->taxonomy.'_'.$group->term_id);
        $group_name = $group_name ? $group_name : $group->name;

        $city = esc_attr( get_search_form_param('pcity') );

        $services = new WP_Query( array(
          'post_type'        => 'service',
          'posts_per_page'   => -1,
          'post_status'      => 'publish',
          'tax_query'        => array(
            array(
              'taxonomy' => 'services-groups',
              'field'    => 'id',
              'terms'    => array( $group->term_id ),
              'operator' => 'IN'
            )
          ),
          'meta_query'  => array(
            array(
              'key'     => '_job_location_city',
              'value'   => $city,
              'type'    => 'CHAR',
              'compare' => '='
            )
          )
        ) );

        if( $services->have_posts() ) {
          
          $data[$group->term_id] = array(
            'name'      => esc_html( $group_name ),
            'services'  => array()
          );
          
          while( $services->have_posts() ) { $services->the_post();
            $pricelist          = get_field('_service_pricelist');
            $service_name       = get_the_title();
            $service_pricelist  = array();

            if( $pricelist ) {
              foreach($pricelist as $item) {
                $name   = $item['_name'];
                $price  = $item['_price'];
                $service_pricelist[] = array(
                  'name' => esc_html( $name ),
                  'price' => esc_html( $price )
                );
              }
            }
            
            $data[$group->term_id]['services'][] = array(
              'id'    => absint( get_the_ID() ),
              'name'  => esc_html( $service_name ),
              'pricelist' => $service_pricelist
            );
          }
        }; wp_reset_postdata();
      }
    }
    
    //set_transient( '_offer_pricelist_data', $data, 86400 );
  }
  
  return $data;
}

/**
 * This function will return an array of meta values for given key
 * @param $key (string)
 * @return array
*/
function _get_meta_values($key) {
  global $wpdb;

  $transient = $key.'_data';

  $data   = get_transient($transient);
  $result = array();

  if( $data === false ) {
    $data = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '%s'", $key ) );

    set_transient( $transient, $data, 86400 );
  }

  if( $data ) {
    foreach($data as $type) {
      $result[] = $type->meta_value;
    }
  }

  return $result;
}

/**
 * This function will return either doctor specializations html or ids array data
 * @param $post_id (int)
 * @param $data (boolean) - optional
 * @return mixed
*/
function doctor_specializations($post_id, $data = false) {
  $cats = wp_get_post_terms( $post_id, 'specialization');
  $html = '';
  $array = array();

  if( $cats ) {
    
    if( $data ) {
      foreach($cats as $cat) {
        $array[] = $cat->term_id;
      }
      return $array;
    }

    foreach($cats as $cat) {
      $html .= $cat->name.', ';
    }

    return rtrim( $html, ', ' );

  }
  
  return false;
}

function _groups_services_redirect() {
  if( is_tax('services-groups') && get_search_form_param('ogroup') && get_search_form_param('oservice') ) {
    $group = get_queried_object();
    if( $group->term_id != get_search_form_param('ogroup') ) {
      $ogroup   = absint( get_search_form_param('ogroup') );
      $oservice = absint( get_search_form_param('oservice') );
      $link     = get_term_link( $ogroup ).'?ogroup='.$ogroup.'&oservice='.$oservice;

      if( !is_wp_error($link) ) { 
        wp_safe_redirect( $link ); exit();
      }
    }
  }
}
add_action('template_redirect', '_groups_services_redirect');

function _get_pricelist_filter_params($json = true) {
  if( isset($_GET['pfilter'] ) ) {
    $data = sanitize_text_field( $_GET['pfilter'] );
    $params = explode('-', $data);

    return $json ? json_encode( $params ) : $params;
  }

  return false;
}

/**
 * This function will render post terms html
 * @param 
 * @return html
*/
function _get_post_terms_html($post_id, $taxonomy, $links = false, $categories = false, $link_with_params = false) {
  $html = '';

  $cats = $categories ? wp_get_post_categories( $post_id, array('fields' => 'all') ) : wp_get_post_terms( $post_id, $taxonomy );

  if($cats) {
    foreach($cats as $cat) {
      $link = $categories ? get_category_link( $cat->term_id ) : get_term_link( $cat->term_id );
      $link = $link_with_params ? $link_with_params : $link;
      $param = $link_with_params ? $cat->term_id : '';
      
      if( $links ) {
        $html .= '<a href="'.esc_url( $link.$param ).'">';
      }

      $html .= $cat->name;

      if( $links ) {
        $html .= '</a>';
      }

      $html .= ', ';
    }
  }

  return rtrim( $html, ', ' );
}

function _filter_news($query) {
  if( $query->is_main_query() && $query->is_home() && !is_admin() && isset( $_GET['filter'] ) && absint( $_GET['filter'] ) == 1 ) {
    $cat    = isset( $_GET['pc'] ) ? absint( $_GET['pc'] ) : 0;
    $place  = isset( $_GET['pp'] ) ? absint( $_GET['pp'] ) : 0;
    $date   = isset( $_GET['pd'] ) ? absint( $_GET['pd'] ) : 0;
    $search = isset( $_GET['ps'] ) ? sanitize_text_field( $_GET['ps'] ) : '';

    if( $cat > 0 ) {
      $tax_query = array(
        array(
          'taxonomy'  => 'category',
          'field'     => 'id',
          'terms'     => array( $cat ),
          'operator'  => 'IN'
        )
      );
      $query->set('tax_query', $tax_query);
    }

    if( $place > 0 ) {
      $tax_query = array(
        array(
          'taxonomy'  => 'institution-city',
          'field'     => 'id',
          'terms'     => array( $place ),
          'operator'  => 'IN'
        )
      );
      $query->set('tax_query', $tax_query);
    }

    if( $date > 0 ) {
      $datestamp = strtotime($date);
      $year   = date('Y', $datestamp);
      $month  = date('m', $datestamp);
      $day    = date('d', $datestamp);

      $date_query = array(
        'year'  => absint( $year ),
        'month' => absint( $month ),
        'day'   => absint( $day )
      );
      $query->set('date_query', $date_query);
    }

    if( $search != '' ) {
      $query->set('s', $search);
    }
  }
}
add_action('pre_get_posts', '_filter_news');

function _news_filter_header() {
  $html = '';

  if( isset( $_GET['filter'] ) && absint( $_GET['filter'] ) == 1 ) {
    $cat    = isset( $_GET['pc'] ) ? absint( $_GET['pc'] ) : 0;
    $place  = isset( $_GET['pp'] ) ? absint( $_GET['pp'] ) : 0;
    $date   = isset( $_GET['pd'] ) ? absint( $_GET['pd'] ) : 0;
    $search = isset( $_GET['ps'] ) ? sanitize_text_field( $_GET['ps'] ) : '';

    if( $cat > 0 ) {
      $html = pll_trans('Kategoria:', true).' <span>'.esc_html( get_cat_name($cat) ).'</span>';
    }

    if( $place > 0 ) {
      $place = get_term($place, 'institution-city');
      $html = pll_trans('Miejsce:', true).' <span>'.esc_html( $place->name ).'</span>';
    }

    if( $date > 0 ) {
      $html = pll_trans('Data:', true).' <span>'.date('d.m.Y', strtotime($date) ).'</span>';
    }

    if( $search != '' ) {
      $html = pll_trans('Wyszukano:', true).' <span>'.$search.'</span>';
    }
  }

  return $html == '' ? $html : '<p class="news-flter-header">'.$html.'</p>';
}

function filter_wpcf7_form_tag( $tag, $replace ) {
  switch($tag['name']) {
    case 'your-clinic':
      $clinics = _run_wp_query('clinic', 'wpcf7', -1, array(), array(), false, 1, array(), 'name', 'ASC');

      $tag['raw_values'][] = '-- '.pll_trans('Wybierz poradnię', true).' --';  
      $tag['values'][] = '-- '.pll_trans('Wybierz poradnię', true).' --';  
      $tag['labels'][] = '-- '.pll_trans('Wybierz poradnię', true).' --'; 

      if( $clinics->have_posts() ) {
        while($clinics->have_posts()) { $clinics->the_post();
          $name = get_the_title();
          $tag['raw_values'][] = esc_html( $name );  
          $tag['values'][] = esc_html( $name );  
          $tag['labels'][] = esc_html( $name );  
        }
      }; wp_reset_postdata();
      return $tag;
      break;
    case 'job-position':
      if( _is_job_apply_page() ) {
        $job_id       = absint( $_GET['apply'] );
        $job_position = get_field('_job_position', $job_id) ? get_field('_job_position', $job_id) : get_the_title($job_id);

        $tag['raw_values'][] = $job_position;  
        $tag['values'][] = $job_position;  
        $tag['labels'][] = $job_position; 
      }
      break;
    case 'job-ref-no':
      if( _is_job_apply_page() ) {
        $job_id = absint( $_GET['apply'] );
        $ref_no = get_field('_job_ref_no', $job_id);

        $tag['raw_values'][] = $ref_no;  
        $tag['values'][] = $ref_no;  
        $tag['labels'][] = $ref_no; 
      }
      break;
    case 'job-company':
      if( _is_job_apply_page() ) {
        $job_id   = absint( $_GET['apply'] );
        $company  = get_field('_job_company', $job_id);

        $tag['raw_values'][] = $company;  
        $tag['values'][] = $company;  
        $tag['labels'][] = $company; 
      }
      break;
  }
  return $tag; 
};
add_filter( 'wpcf7_form_tag', 'filter_wpcf7_form_tag', 10, 2 ); 



function _register_setting_fields() {
  register_setting( 'general', 'career_blog_description', 'esc_attr' );

  add_settings_field(
    'career_blog_desc_id',
    '<label for="career_blog_desc_id">' . __( 'Blog description - Career' , 'rfswp' ) . '</label>',
    '_setting_fields_html',
    'general'
  );
}
add_action( 'admin_init' , '_register_setting_fields' );

function _setting_fields_html() {
  $value = get_option( 'career_blog_description', '' );

  echo '<input class="regular-text" type="text" id="career_blog_desc_id" name="career_blog_description" value="' . esc_attr( $value ) . '" />';
}


?>