<?php
/**
 * This function will return template page id
 * @param $template_name (string)
 * @param $single (bollean)
 * @return mixed
*/
function _page_template_id($template_name, $single = false) {
  $template_page = get_posts(array(
		'post_type'   =>'page',
		'meta_key'    => '_wp_page_template',
		'meta_value'  => 'page-templates/'.$template_name.'-template.php'
	));

  $ids = array();

  if( !$template_page ) return false;

  foreach($template_page as $page) {
    $ids[] = $page->ID;
  }

  if( is_admin() && !$single ) {
    return $ids;
  }else {
    return $ids[0];
  }

  return false;
}

/**
 * This function will serve as a wrapper for wp is_page_template function
 * @param $template_name (string)
 * @return boolean
*/
// function _is_page_template($template_name) {
//   return is_page_template( 'page-templates/'.$template_name.'-template.php' );
// }

/**
 * This function will replace p tags with br tags in a given text content
 * @param $content (html)
 * @return html
*/
function _p2br($content) {
	$new_content = preg_replace( "/<p[^>]*?>/", "", $content );
	$new_content = str_replace( "</p>", "<br />", $new_content );
	return preg_replace( '#(( ){0,}<br( {0,})(/{0,1})>){1,}$#i', '', $new_content );
}

/**
 * This function will check if given text is empty
 * @param $string (string)
 * @return boolean
*/
function _empty_content($text) {
  return trim( str_replace( '&nbsp;', '', strip_tags( $text ) ) ) == '';
}

function _excerpt($string, $limit = 16, $more = '', $exclude = false) {
	$words    = explode( ' ', $string );
	$excerpt  = implode( ' ', array_splice( $words, 0, $limit ) ); 	
	$excerpt  = trim( $excerpt, ',;' );

	return strip_tags( $excerpt, $exclude ).$more;
}

/**
 * This function will return $_GET parameter value
 * @param $param (string)
 * @return mixed - string/boolean
*/
function get_search_form_param($param) {
  if( isset( $_GET[$param] ) ) {
    return sanitize_text_field( $_GET[$param] );
  }

  return false;
}

/**
 * This function will return link data for acf link type field
 * @param $link (array)
 * @return array
*/
function _link_data( $link, $default_title = '' ) {
  $data = array(
    'url'     => '',
    'title'   => '',
    'target'  => ''
  );
  
  if( isset( $link['url'] ) ) {
    $data['url']    = esc_url( $link['url'] );
    $data['title']  = $link['title'] ? esc_html( $link['title'] ) : pll_trans($default_title, true);
    $data['target'] = $link['target'];
  }

  return $data;
}

/**
 * This function will highlight last word in a phrase with more than one words and return amended phrase
 * @param $text (string)
 * @return html
*/
function _last_word_highlight($text, $invert = false) {
  $words    = explode(' ', $text);
  $new_text = $text;

  if( $invert ) {
    if( count($words) > 0 ) {
      $first    = array_shift($words);
      
      $new_text = '<span class="highlight">'.$first.'</span><br>'.implode(' ', $words);
    }
  }else {
    if( count($words) > 1 ) {
      $last     = array_pop($words);
      
      $new_text = implode(' ', $words).' <span class="highlight">'.$last.'</span>';
    }
  }

  return $new_text;
}

/**
 * This function will get page layout by  its name
 * @param $layout_name (string)
 * @return n/a
*/
function _get_page_layout($layout_name, $index = false) {
  $layout = TEMPLATEPATH.'/page-layouts/layout-'.$layout_name.'_block.php';

  if( file_exists( $layout ) ) {
    include $layout;
  }
}

function _is_business_page() {
  $parent = wp_get_post_parent_id( get_the_ID() );

  if( !_page_template_id('business') ) {
    return false;
  }
  
  return is_page( _page_template_id('business', true) ) || _page_template_id('business', true) == $parent;
}

function _is_shop_page() {
  $parent = wp_get_post_parent_id( get_the_ID() );

  if( !_page_template_id('shop') ) {
    return false;
  }
  
  return is_page( _page_template_id('shop', true) ) || _page_template_id('shop', true) == $parent;
}

/**
 * This function will check if the career page is being viewed
 * @param n/a
 * @return boolean
*/
function _is_career_page() {
  $parent = wp_get_post_parent_id( get_the_ID() );
  //return is_page( _page_template_id('career-main', true) ) || is_page( _page_template_id('career', true) ) || is_page( _page_template_id('recruitment', true) ) || is_singular('job-offer');
  return is_page( _page_template_id('career-main', true) ) || _page_template_id('career-main', true) == $parent || is_singular('job-offer');
}

function _is_job_apply_page() {
  if( is_singular('job-offer') && isset( $_GET['apply'] ) ) {
    $post_id = absint( $_GET['apply'] );

    if( get_post_type( $post_id ) == 'job-offer' ) {
      return true;
    }
  }

  return false;
}

/**
 * This function will get template part
 * @param $template_name (string)
 * @param $folder (string)
 * @param $params (array) - optional
 * @return 
*/
function _get_template_part($template_part_name, $folder = false, $params = array(), $return = false) {
	$subfolder = $folder ? $folder.'/' : '';

  $template_part = TEMPLATEPATH.'/template-parts/'.$subfolder.$template_part_name.'.php';

	if( file_exists( $template_part ) ) {
    if( $return ) {
      ob_start();
    }
		include $template_part;
    if( $return ) {
      return ob_get_clean();
    }
	}

	return false;
}

/**
 * This function will render pagination html for global wp queries
 * @param 
 * @return html
*/
function _pagination($pages = '', $range = 2, $custom_query = false, $paged = false, $format = 'page/%#%') {
  $html = '';

  if($custom_query) {
    $pages = $custom_query->max_num_pages;
    
		$args = paginate_links(array(
      'base'        => get_pagenum_link(1) . '%_%',
			'format'  		=> $format,
			'current' 		=> $paged,
			'total'   		=> $pages,
			'prev_next' 	=> false,
			'type'  		=> 'array',
			'end_size'		=> 2,
			'mid_size'		=> 1,
			'prev_next'   	=> false
    ));

		if( is_array($args) ) { 
    
      $html .= '<div class="clearfix"></div>';
  
      $html .= '<div class="pagination-container">';

      $html .= '<ul class="pagination">';

        $html .= '<li>';
          $html .= $paged == 1 ? '<span class="prev-page"><i class="fa fa-angle-left"></i></span>' : '<a class="prev-page" href="'.get_pagenum_link( $paged - 1 ).'"><i class="fa fa-angle-left"></i></a>';
        $html .= '</li>';

      foreach($args as $page) {
        $html .= '<li>'.$page.'</li>';
      }

        $html .= '<li>';
          $html .= $paged == $pages ? '<span class="next-page"><i class="fa fa-angle-right"></i></span>' : '<a class="next-page text-uppercase" href="'.get_pagenum_link( $paged + 1 ).'"><i class="fa fa-angle-right"></i></a>';
        $html .= '</li>';

      $html .= '</ul>';

      $html .= '</div>';
      
		}
		
	}else {
    global $paged;

    $showitems = ($range * 2) + 1;

    if( empty( $paged ) ) {
      $paged = 1;
    }

    if( $pages == '' ) {
      global $wp_query;

      $pages = $wp_query->max_num_pages;

      if( !$pages ) {
        $pages = 1;
      }
    }

    $html .= '<div class="clearfix"></div>';

    if( $pages != 1 ) {
      $html .= '<div class="pagination-container">';

      $html .= '<ul class="pagination">';

        $html .= '<li>';
          $html .= $paged == 1 ? '<span class="prev-page"><i class="fa fa-angle-left"></i></span>' : '<a class="prev-page" href="'.get_pagenum_link( $paged - 1 ).'"><i class="fa fa-angle-left"></i></a>';
        $html .= '</li>';

      for ($i=1; $i <= $pages; $i++) {
        if( $pages != 1 &&( !($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems ) ) {
          $html .= $paged == $i ? '<li class="active"><span>'.$i.'</span></li>' : '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
        }
      }

        $html .= '<li>';
          $html .= $paged == $pages ? '<span class="next-page"><i class="fa fa-angle-right"></i></span>' : '<a class="next-page text-uppercase" href="'.get_pagenum_link( $paged + 1 ).'"><i class="fa fa-angle-right"></i></a>';
        $html .= '</li>';

      $html .= '</ul>';

      $html .= '</div>';
    }
  }

  return $html;
}

/**
 * This function will return wp query powered with transients
 * @param $post_type (string)
 * @param $transient_affix (string)
 * @param $post_type (int)
 * @return wp query object
*/
function _run_wp_query($post_type, $transient_affix, $limit = -1, $meta_query = array(), $tax_query = array(), $clear = false, $page = 1, $exclude = array(), $orderby = 'date', $order = 'DESC', $search = '', $post_in = array()) {

  $posts = get_transient('_'.$post_type.'_'.$transient_affix.'_query_p'.$page);

  if( $posts === false ) {
    $posts = new WP_Query( array(
      'post_type'       => $post_type,
      'posts_per_page'  => $limit,
      'post_status'     => 'publish',
      'meta_query'      => $meta_query,
      'tax_query'       => $tax_query,
      'paged'           => $page,
      'post__not_in'    => $exclude,
      'post__in'        => $post_in,
      'order'           => $order,
      'orderby'         => $orderby,
      's'               => $search
    ) );
    set_transient( '_'.$post_type.'_'.$transient_affix.'_query_p'.$page, $posts, 86400 );
  }

  if( $clear ) {
    delete_transient( '_'.$post_type.'_'.$transient_affix.'_query_p'.$page );
  }

  return $posts;
}

/**
 * This function will delete widget transients on posts save in admin panel
 * @param n/a
 * @return n/a
*/
function _clear_widget_posts_transient($post_id) {
  global $post_type;
  global $pagenow;
  global $wpdb;

  $widget_types = array('posts_box', 'posts_carousel', 'posts_list', 'slider', 'products');

  $posts = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_widget_type' AND meta_value IN ('".implode("', '", $widget_types)."')" );

  if( $posts && $pagenow == 'post.php' ) {
    foreach($posts as $post) {
      $widget_post_types = get_field('_widget_posts_types', $post->post_id);
      
      if( $post_type == $widget_post_types || $post_type == 'widget' ) {
        delete_transient( 'widget_'.$post->post_id.'_posts' );
        delete_transient( 'widget_'.$post->post_id.'_terms' );
      }
    }
  }
}
add_action( 'save_post', '_clear_widget_posts_transient' );

function _clear_widget_terms_transient($term_id, $tt_id, $taxonomy) {
  global $wpdb;

  if( $taxonomy == 'services-groups' ) {
    $widget_types = array('posts_box', 'posts_carousel');

    $posts = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_widget_type' AND meta_value IN ('".implode("', '", $widget_types)."')" );

    foreach($posts as $post) {
      $widget_post_types = get_field('_widget_posts_types', $post->post_id);
      
      if( $taxonomy == $widget_post_types ) {
        delete_transient( 'widget_'.$post->post_id.'_terms' );
      }
    }

    delete_transient( '_offer_pricelist_data' );
    
  }
}
add_action( 'edit_term', '_clear_widget_terms_transient', 10, 3 );

function _clear_wp_queries_transients($post_id) {
  global $pagenow;

  if( $pagenow == 'post.php' || $pagenow == 'edit.php' || $pagenow == 'post-new.php' ) {
    switch( get_post_type($post_id) ) {
      case 'post':
        delete_transient( '_post_latest_query_p1' );
        break;
      case 'institution':
        delete_transient( '_institution_list_query_p1' );
        delete_transient( '_institution_type_data' );
        delete_transient( '_institution_wpcf7_query_p1' );
        break;
      case 'doctor':
        $doctors        = wp_count_posts('doctor');
        $doctors_pages  = ceil( $doctors->publish/12 );
  
        for($i=1; $i<=$doctors_pages; $i++) {
          delete_transient( '_doctor_list_query_p'.$i );
        }
        break;
      case 'job-offer':
        $offers        = wp_count_posts('job-offer');
        $offers_pages  = ceil( $offers->publish/12 );
  
        for($i=1; $i<=$offers_pages; $i++) {
          delete_transient( '_job-offer_list_query_p'.$i );
        }
        delete_transient( '_job_location_city_data' );
        break;
      case 'clinic':
        delete_transient( '_clinic_wpcf7_query_p1' );
        break;
      case 'service':
        delete_transient( '_offer_pricelist_data' );
        break;
      case 'corten-product':
        $products_count  = CORTEN_SHOP::products_count();
        $ppp             = CORTEN_SHOP::shop_ppp( 'products' );
        $products_pages  = ceil( $products_count / $ppp );
  
        for($i=1; $i<=$products_pages; $i++) {
          delete_transient( '_corten-product_list_query_p'.$i );
        }
        delete_transient( '_corten-product_recommended_query_p1' );
        break;
      default:
        break;
    }
  }else if( $pagenow == 'admin.php' && isset( $_POST['acf'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'theme-shop-settings' ) {
    $products_count  = CORTEN_SHOP::products_count();
    $ppp             = CORTEN_SHOP::shop_ppp( 'products' );
    $products_pages  = ceil( $products_count / $ppp );

    for($i=1; $i<=$products_pages; $i++) {
      delete_transient( '_corten-product_list_query_p'.$i );
    }

    delete_transient( '_corten-product_recommended_query_p1' );
  }
}
add_action( 'save_post', '_clear_wp_queries_transients' );
add_action( 'acf/save_post', '_clear_wp_queries_transients' );
?>