<?php  
if( !class_exists('CORTEN_SHOP') ) {

  if(is_admin()) {
		global $shop_admin_page;
		$shop_admin_page = isset($_GET['page']) ? $_GET['page'] : null;
  }
  
  class CORTEN_SHOP {
    
    protected static $allowed_summary_types = array(
      'bank-transfer/completed',
      'payment-online/notification',
      'payment-online/completed',
      'payment-online/canceled',
    );

    public function __construct() {
      add_action( 'admin_init', array($this, 'shop_admin_view_redirect') );

      add_action( 'admin_menu', array($this, 'add_shop_menu_pages') );

      add_action( 'init', array($this, 'register_post_types') );

      add_action( 'admin_footer', array($this, 'js_scripts') );
      add_action( 'admin_head', array($this, 'shop_menu_nav_css') );
      add_action( 'admin_enqueue_scripts', array($this, 'shop_admin_scripts') );

      add_filter( 'manage_edit-corten-product-category_columns', array($this, 'modify_product_categories_columns') );
      add_action( 'corten-product-category_edit_form', array($this, 'hide_product_categories_description') );
      add_action( 'corten-product-category_add_form', array($this, 'hide_product_categories_description') );
      add_filter( 'manage_edit-corten-product-manufacturer_columns', array($this, 'modify_product_categories_columns') ); 
      add_action( 'corten-product-manufacturer_edit_form', array($this, 'hide_product_categories_description') );
      add_action( 'corten-product-manufacturer_add_form', array($this, 'hide_product_categories_description') );

      add_action( 'save_post_corten-product', array($this, 'check_parent_category'), 10, 2 );

      add_filter( 'manage_edit-corten-product_columns', array($this, 'edit_column_admin_table') );
      add_action( 'manage_corten-product_posts_custom_column', array($this, 'show_data_column_admin_table'), 10, 2 );
      
      add_filter( 'manage_edit-corten-shipment_columns', array($this, 'edit_shipment_column_admin_table') );
      add_action( 'manage_corten-shipment_posts_custom_column', array($this, 'show_shipment_data_column_admin_table'), 10, 2 );

      add_filter( 'post_row_actions', array($this, 'disable_quick_edit'), 10, 1 );

      add_filter( 'manage_edit-corten-product-order_columns', array($this, 'edit_product_order_column_admin_table') );
      add_action( 'manage_corten-product-order_posts_custom_column', array($this, 'show_product_order_data_column_admin_table'), 10, 2 );

      add_filter( 'manage_edit-corten-package-category_columns', array($this, 'modify_product_categories_columns') );

      add_action( 'init', array($this, 'shop_endpoints') );
      add_filter( 'query_vars', array($this, 'shop_queryvars') );

      add_action( 'admin_menu' , array($this, 'remove_product_order_meta_boxes') );
      add_action( 'add_meta_boxes_corten-product-order' , array($this, 'add_product_order_meta_boxes') );
      add_action( 'save_post_corten-product-order', array($this, 'save_product_order_settings') );
    }
    
    public function add_shop_menu_pages() {
      global $corten_shop;

      $corten_shop = add_menu_page(__('Woo', 'rfswp'), __('Woo', 'rfswp'), 'edit_posts', 'corten-shop', array($this, 'shop_view'), 'dashicons-cart', 21 );
      add_submenu_page('corten-shop', __('Products', 'rfswp'), __('Products', 'rfswp'), 'edit_posts', 'edit.php?post_type=corten-product' );
      add_submenu_page('corten-shop', __('Categories', 'rfswp'), __('Categories', 'rfswp'), 'edit_posts', 'edit-tags.php?taxonomy=corten-product-category&post_type=corten-product' );
      add_submenu_page('corten-shop', __('Manufacturers', 'rfswp'), __('Manufacturers', 'rfswp'), 'edit_posts', 'edit-tags.php?taxonomy=corten-product-manufacturer&post_type=corten-product' );
      //add_submenu_page('corten-shop', __('Orders', 'rfswp'), __('Orders', 'rfswp'), 'edit_posts', 'corten-products-orders', array($this, 'orders_view') );

      add_submenu_page('corten-shop', __('Packages', 'rfswp'), __('Packages', 'rfswp'), 'edit_posts', 'edit.php?post_type=corten-package' );
      add_submenu_page('corten-shop', __('Categories', 'rfswp'), __('Categories', 'rfswp'), 'edit_posts', 'edit-tags.php?taxonomy=corten-package-category&post_type=corten-package' );
      //add_submenu_page('corten-shop', __('Orders', 'rfswp'), __('Orders', 'rfswp'), 'edit_posts', 'edit.php?post_type=corten-package-order' );
      //add_submenu_page('corten-shop', __('Orders', 'rfswp'), __('Orders', 'rfswp'), 'edit_posts', 'corten-packages-orders', array($this, 'orders_view') );
      add_submenu_page('corten-shop', __('Orders', 'rfswp'), __('Orders', 'rfswp'), 'edit_posts', 'edit.php?post_type=corten-product-order' );
      add_submenu_page('corten-shop', __('Shipment methods', 'rfswp'), __('Shipment methods', 'rfswp'), 'edit_posts', 'edit.php?post_type=corten-shipment' );
    }

    public function shop_view() {
			// function shop_admin_view_redirect() is called via admin_init hook before the view is rendered
    }

    public function shop_admin_view_redirect() {
      global $shop_admin_page;
      global $pagenow;
			
			if($shop_admin_page == 'corten-shop') {
				wp_safe_redirect('edit.php?post_type=corten-product'); exit;
      }
      
      if( $pagenow == 'post-new.php' && ( $_GET['post_type'] == 'corten-package-order' || $_GET['post_type'] == 'corten-product-order' ) ) {
        wp_safe_redirect('edit.php?post_type='.$_GET['post_type']); exit;
      }
		}

    public function register_post_types() {
      register_post_type( 'corten-product', array(
        'labels'	=> array(
          'name'               => __('Products', 'rfswp'),
          'singular_name'      => __('Product', 'rfswp'),
          'add_new'            => __('Add product', 'rfswp'),
          'add_new_item'       => __('Add new product', 'rfswp'),
          'edit_item'          => __('Edit product', 'rfswp'),
          'new_item'           => __('New product', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Products:', 'rfswp'),
          'menu_name'          => __('Products', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> false, //show in admin menu - show_ui must be true
        'menu_position'       	=> 1,
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'produkt'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
      
      register_post_type( 'corten-product-order', array(
        'labels'	=> array(
          'name'               => __('Orders', 'rfswp'),
          'singular_name'      => __('Order', 'rfswp'),
          'add_new'            => __('Add order', 'rfswp'),
          'add_new_item'       => __('Add new order', 'rfswp'),
          'edit_item'          => __('Edit order', 'rfswp'),
          'new_item'           => __('New order', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Orders:', 'rfswp'),
          'menu_name'          => __('Orders', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> false,
        'exclude_from_search' 	=> true,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> false, //show in admin menu - show_ui must be true
        'menu_position'       	=> 1,
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );

      register_taxonomy('corten-product-category', 'corten-product', array(
				'labels'	=> array(
					'name'				      => __( 'Categories - Products', 'rfswp' ),
					'singular_name'		  => __( 'Category', 'rfswp'),
					'menu_name'			    => __( 'Categories', 'rfswp' ),
					'all_items'			    => __( 'All categories', 'rfswp' ),
					'edit_item'			    => __( 'Edit category', 'rfswp' ),
					'view_item'			    => __( 'View category', 'rfswp' ),
					'update_item'		    => __( 'Update category', 'rfswp' ),
					'add_new_item'		  => __( 'Add new category', 'rfswp' ),
					'new_item_name'		  => __( 'New category name', 'rfswp' ),
					'parent_item'		    => __( 'Parent category', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent category:', 'rfswp' ),
					'search_items'		  => __( 'Search categories', 'rfswp' ),
					'popular_items'		  => __( 'Popular categories', 'rfswp' ) 
				),
        'public'				      => true,
				'publicly_queryable'	=> true,
				'show_ui'				      => true,
				'show_in_menu'			  => false, //admin menu,
				'show_in_nav_menus'		=> true,
				'show_tag_cloud'	    => false,
        'hierarchical'		    => true,
        'show_admin_column'   => true,
        'rewrite' => array(
          'slug' => 'kategoria-produktu'
        )
      ));

      register_taxonomy('corten-product-manufacturer', 'corten-product', array(
				'labels'	=> array(
					'name'				      => __( 'Manufacturers', 'rfswp' ),
					'singular_name'		  => __( 'Manufacturer', 'rfswp'),
					'menu_name'			    => __( 'Manufacturers', 'rfswp' ),
					'all_items'			    => __( 'All manufacturers', 'rfswp' ),
					'edit_item'			    => __( 'Edit manufacturer', 'rfswp' ),
					'view_item'			    => __( 'View manufacturer', 'rfswp' ),
					'update_item'		    => __( 'Update manufacturer', 'rfswp' ),
					'add_new_item'		  => __( 'Add new manufacturer', 'rfswp' ),
					'new_item_name'		  => __( 'New manufacturer name', 'rfswp' ),
					'parent_item'		    => __( 'Parent manufacturer', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent manufacturer:', 'rfswp' ),
					'search_items'		  => __( 'Search manufacturers', 'rfswp' ),
          'popular_items'		  => __( 'Popular manufacturers', 'rfswp' ),
          'not_found'         => __( 'No manufacturers found', 'rfswp' ),
				),
        'public'				      => false,
				'publicly_queryable'	=> false,
				'show_ui'				      => true,
				'show_in_menu'			  => false, //admin menu,
				'show_in_nav_menus'		=> false,
				'show_tag_cloud'	    => false,
        'hierarchical'		    => true,
        'show_admin_column'   => true,
        'rewrite' => array(
          'slug' => 'producent-produktu'
        )
      ));
      
      register_post_type( 'corten-package', array(
        'labels'	=> array(
          'name'               => __('Packages', 'rfswp'),
          'singular_name'      => __('Package', 'rfswp'),
          'add_new'            => __('Add package', 'rfswp'),
          'add_new_item'       => __('Add new package', 'rfswp'),
          'edit_item'          => __('Edit package', 'rfswp'),
          'new_item'           => __('New package', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Packages:', 'rfswp'),
          'menu_name'          => __('Packages', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> false, //show in admin menu - show_ui must be true
        'menu_position'       	=> 1,
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'pakiet'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );

      register_taxonomy('corten-package-category', 'corten-package', array(
				'labels'	=> array(
					'name'				      => __( 'Categories - Packages', 'rfswp' ),
					'singular_name'		  => __( 'Category', 'rfswp'),
					'menu_name'			    => __( 'Categories', 'rfswp' ),
					'all_items'			    => __( 'All categories', 'rfswp' ),
					'edit_item'			    => __( 'Edit category', 'rfswp' ),
					'view_item'			    => __( 'View category', 'rfswp' ),
					'update_item'		    => __( 'Update category', 'rfswp' ),
					'add_new_item'		  => __( 'Add new category', 'rfswp' ),
					'new_item_name'		  => __( 'New category name', 'rfswp' ),
					'parent_item'		    => __( 'Parent category', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent category:', 'rfswp' ),
					'search_items'		  => __( 'Search categories', 'rfswp' ),
					'popular_items'		  => __( 'Popular categories', 'rfswp' ) 
				),
        'public'				      => false,
				'publicly_queryable'	=> false,
				'show_ui'				      => true,
				'show_in_menu'			  => false, //admin menu,
				'show_in_nav_menus'		=> false,
				'show_tag_cloud'	    => false,
        'hierarchical'		    => true,
        'show_admin_column'   => true,
        'rewrite' => array(
          'slug' => 'kategoria-pakietu'
        )
      ));
      
      // register_post_type( 'corten-package-order', array(
      //   'labels'	=> array(
      //     'name'               => __('Orders', 'rfswp'),
      //     'singular_name'      => __('Order', 'rfswp'),
      //     'add_new'            => __('Add order', 'rfswp'),
      //     'add_new_item'       => __('Add new order', 'rfswp'),
      //     'edit_item'          => __('Edit order', 'rfswp'),
      //     'new_item'           => __('New order', 'rfswp'),
      //     'view_item'          => __('View', 'rfswp'),
      //     'search_items'       => __('Search', 'rfswp'),
      //     'not_found'          => __('Nothing found', 'rfswp'),
      //     'not_found_in_trash' => __('Trash is empty', 'rfswp'),
      //     'parent_item_colon'  => __('Orders:', 'rfswp'),
      //     'menu_name'          => __('Orders', 'rfswp'),
      //   ),
      //   'description'         	=> '',
      //   'public'              	=> false,
      //   'exclude_from_search' 	=> true,
      //   'publicly_queryable'  	=> false,
      //   'show_ui'             	=> true, // show in admin
      //   'show_in_nav_menus'   	=> false,
      //   'show_in_menu'        	=> false, //show in admin menu - show_ui must be true
      //   'menu_position'       	=> 1,
      //   'capability_type'     	=> 'post', 
      //   'hierarchical'        	=> false,
      //   'supports'            	=> array(
      //     'title'
      //   ),
      //   'has_archive'         	=> false,
      //   'query_var'           	=> true,
      //   'can_export'          	=> true,
      // ) );
      
      register_post_type( 'corten-shipment', array(
        'labels'	=> array(
          'name'               => __('Shipment methods', 'rfswp'),
          'singular_name'      => __('Shipment method', 'rfswp'),
          'add_new'            => __('Add shipment method', 'rfswp'),
          'add_new_item'       => __('Add new shipment method', 'rfswp'),
          'edit_item'          => __('Edit shipment method', 'rfswp'),
          'new_item'           => __('New shipment method', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Shipment methods:', 'rfswp'),
          'menu_name'          => __('Shipment methods', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> false,
        'exclude_from_search' 	=> true,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> false, //show in admin menu - show_ui must be true
        'menu_position'       	=> 1,
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function orders_view() {
			echo 'Orders';
    }

    public function js_scripts() {
      global $pagenow;
      global $post_type;
      global $taxonomy;

      $scripts = '
      <script type="text/javascript">
        jQuery(document).ready( function($) {';

          $scripts .= "$('#toplevel_page_corten-shop').find('ul li.wp-first-item').hide();";

          // products icon
          $scripts .= "$('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product\"]').prepend('<span class=\"dashicons dashicons-products\" style=\"width:15px; height:15px; font-size:14px\"></span> ');";
          
          // packages icon
          $scripts .= "$('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-package\"]').prepend('<span class=\"dashicons dashicons-plus-alt\" style=\"width:15px; height:15px; font-size:14px; margin-top:1px\"></span> ');";

          // orders icon
          $scripts .= "$('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product-order\"]').prepend('<span class=\"dashicons dashicons-list-view\" style=\"width:15px; height:15px; font-size:14px; margin-top:1px\"></span> ');";

          // shipment icon
          $scripts .= "$('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-shipment\"]').prepend('<span class=\"dashicons dashicons-share-alt2\" style=\"width:15px; height:15px; font-size:14px; margin-top:1px\"></span> ');";

          if( 
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-product-category' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-product-manufacturer' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-product-category' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-product-manufacturer' ) ||
            ( $pagenow == 'post-new.php' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'post-new.php' && $post_type == 'corten-product-order' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-product-order' ) ||
            ( $pagenow == 'post-new.php' && $post_type == 'corten-shipment' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-shipment' ) ||
            ( $pagenow == 'post-new.php' && $post_type == 'corten-package' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-package' ) ||
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-package-category' && $post_type == 'corten-package' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-package-category' )
            // ( $pagenow == 'post-new.php' && $post_type == 'corten-package-order' ) ||
            // ( $pagenow == 'post.php' && $post_type == 'corten-package-order' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').addClass('current wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');
            $('#toplevel_page_corten-shop').find('a').first().addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');
            ";
          }

          if( 
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-product-category' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-product-category' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-product-category&post_type=corten-product\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-product-category&post_type=corten-product\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-product-manufacturer' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-product-manufacturer' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-product-manufacturer&post_type=corten-product\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-product-manufacturer&post_type=corten-product\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'post-new.php' && $post_type == 'corten-product' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-product' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'post-new.php' && $post_type == 'corten-product-order' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-product-order' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product-order\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-product-order\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'post-new.php' && $post_type == 'corten-shipment' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-shipment' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-shipment\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-shipment\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'edit-tags.php' && $taxonomy == 'corten-package-category' && $post_type == 'corten-package' ) ||
            ( $pagenow == 'term.php' && $taxonomy == 'corten-package-category' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-package-category&post_type=corten-package\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit-tags.php?taxonomy=corten-package-category&post_type=corten-package\"]').parent().addClass('current');
            ";
          }

          if( 
            ( $pagenow == 'post-new.php' && $post_type == 'corten-package' ) ||
            ( $pagenow == 'post.php' && $post_type == 'corten-package' )
          ) {
            $scripts .= "
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-package\"]').addClass('current');
            $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-package\"]').parent().addClass('current');
            ";
          }

          // if( 
          //   ( $pagenow == 'post-new.php' && $post_type == 'corten-package-order' ) ||
          //   ( $pagenow == 'post.php' && $post_type == 'corten-package-order' )
          // ) {
          //   $scripts .= "
          //   $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-package-order\"]').addClass('current');
          //   $('#toplevel_page_corten-shop').find('a[href=\"edit.php?post_type=corten-package-order\"]').parent().addClass('current');
          //   ";
          // }

          // hide add new for orders
          if( ( $pagenow == 'edit.php' || $pagenow == 'post.php' ) && ( $post_type == 'corten-package-order' || $post_type == 'corten-product-order' ) ) {
            $scripts .= "
            $('.page-title-action').hide();
            ";
          }

          // hide orders title
          if( $pagenow == 'post.php' && ( $post_type == 'corten-package-order' || $post_type == 'corten-product-order' ) ) {
            $scripts .= "
            $('#titlediv').hide();
            ";
          }

      $scripts .= '
        });
      </script>
      ';

      echo $scripts;
    }

    public function shop_menu_nav_css() {
      echo '
			<style>
      #edittag {max-width: none !important;}
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-product-category&post_type=corten-product"],
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-product-manufacturer&post_type=corten-product"],
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-package-category&post_type=corten-package"] {text-indent: 5px;}
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-product-category&post_type=corten-product"]:before,
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-product-manufacturer&post_type=corten-product"]:before,
      #toplevel_page_corten-shop a[href="edit-tags.php?taxonomy=corten-package-category&post_type=corten-package"]:before {content: "- ";}
			</style>';
    }

    public function shop_admin_scripts() {
      global $pagenow;
      global $post_type;

      if( $pagenow == 'post.php' && $post_type == 'corten-product-order' ) {
        wp_enqueue_style( 'shop_order_details', get_template_directory_uri().'/template-parts/shop/admin/css/product-order.css', array(), '1.0' );
      }
    }
    
    public function modify_product_categories_columns($columns) {
      if( isset( $columns['description'] ) ) {
        unset( $columns['description'] ); 
      }

      return $columns;
    }

    public function hide_product_categories_description() { 
      echo '<style>.term-description-wrap, #wp-description-wrap{display:none;}</style>';
    }

    /**
     * This function will set parent category if a subcategory is selected
     * @param $post_id (int)
     * @param $post (object)
     * @return n/a
    */
    public function check_parent_category($post_id, $post) {
      $terms = wp_get_post_terms($post_id, 'corten-product-category' );
      
      if( $terms ) {
        foreach($terms as $term) {
          while( $term->parent > 0 && !has_term( $term->parent, 'corten-product-category', $post ) ) {
            wp_set_post_terms( $post_id, array($term->parent), 'corten-product-category', true );
            $term = get_term( $term->parent, 'corten-product-category' );
          }
        }
      }
    }
    
    /**
      * This function will add custom column in admin list table
      * @param $columns
      * @return array
    */
    public function edit_column_admin_table($columns) {
      $new_order = array();
      
      foreach($columns as $key => $title) {
        if ($key=='date') {
          $new_order['product_recommended'] = __('Recommended', 'press');
        }
        $new_order[$key] = $title;
      }
      return $new_order;
    }

    /**
      * This function will display value for custom column in admin list table
      * @param $columns
      * @param $post_id
      * @return html
    */
    public function show_data_column_admin_table($columns, $post_id) {
      switch($columns) {
        case 'product_recommended':
          $recommended = absint( get_field('_product_recommended', $post_id) );
          if( $recommended == 1 ) {
            echo '<span class="dashicons dashicons-yes"></span>';
          }
          break;
      }
    }

    /**
      * This function will add custom column in admin shipment methods list table
      * @param $columns
      * @return array
    */
    public function edit_shipment_column_admin_table($columns) {
      $new_order = array();
      
      foreach($columns as $key => $title) {
        if ($key=='date') {
          $new_order['shipment_active']   = __('Active', 'rfswp');
          $new_order['shipment_cost']     = __('Cost (PLN)', 'rfswp');
          $new_order['shipment_default']  = __('Default', 'rfswp');
        }
        $new_order[$key] = $title;
      }
      return $new_order;
    }

    /**
      * This function will display value for custom column in admin shipment methods list table
      * @param $columns
      * @param $post_id
      * @return html
    */
    public function show_shipment_data_column_admin_table($columns, $post_id) {
      switch($columns) {
        case 'shipment_active':
          $active = absint( get_field('_sm_active', $post_id) );
          if( $active == 1 ) {
            echo '<span class="dashicons dashicons-yes"></span>';
          }
          break;
        case 'shipment_cost':
          $cost = get_field('_sm_cost', $post_id);
          
          echo self::format_price( $cost );
          break;
        case 'shipment_default':
          $default = absint( get_field('_shipment_default', 'option') );
          if( $default == $post_id ) {
            echo '<span class="dashicons dashicons-yes"></span>';
          }
          break;
      }
    }

    public function disable_quick_edit( $actions ){
      if( get_post_type() === 'corten-product-order' || get_post_type() === 'corten-package-order' ) {
        unset( $actions['inline hide-if-no-js'] );
      }
      return $actions;
    }

    /**
      * This function will add custom column in admin shipment methods list table
      * @param $columns
      * @return array
    */
    public function edit_product_order_column_admin_table($columns) {
      $new_columns = array();
      
      unset( $columns['title'] );
      unset( $columns['date'] );
      
      foreach($columns as $key => $title) {
        
        if ($key=='cb') {
          $new_columns['order_title']           = __('Order', 'rfswp');
          $new_columns['order_date']            = __('Order date', 'rfswp');
          $new_columns['order_total']           = __('Order total', 'rfswp');
          $new_columns['order_paid']            = __('Paid', 'rfswp');
          $new_columns['order_payment_method']  = __('Payment method', 'rfswp');
          $new_columns['order_status']          = __('Order status', 'rfswp');
        }
        $new_columns[$key] = $title;
      }

      $all_columns = array_merge($columns, $new_columns);

      return $all_columns;
    }

    /**
      * This function will display value for custom column in admin shipment methods list table
      * @param $columns
      * @param $post_id
      * @return html
    */
    public function show_product_order_data_column_admin_table($columns, $post_id) {
      switch($columns) {
        case 'order_title':
          $order_number         = self::order_data_item( $post_id, 'number' );
          $order_number         = self::order_data_item( $post_id, 'number' );
          $customer_first_name  = self::order_data_item( $post_id, 'customer_first_name' );
          $customer_last_name   = self::order_data_item( $post_id, 'customer_last_name' );
          $customer_email       = self::order_data_item( $post_id, 'customer_email' );

          $html = '';
          $html .= '<a href="post.php?post='.absint( $post_id ).'&action=edit">#'.$order_number.'</a><br>';
          $html .= esc_html( $customer_first_name ).' '.esc_html( $customer_last_name );

          if( $customer_email ) {
            $html .= ' - <a href="mailto:'.antispambot( $customer_email ).'">'.antispambot( $customer_email ).'</a>';
          }

          $html .= '
          <div class="row-actions">
            <span class="edit">
              <a href="'.get_edit_post_link( $post_id ).'" aria-label="'.__('Edit '.esc_html( get_the_title( $post_id ) ), 'rfswp').'">'.__('Edit', 'rfswp').'</a>
            | </span>
            <span class="trash">
              <a href="'.get_delete_post_link( $post_id ).'" class="submitdelete" aria-label="'.__('Move '.esc_html( get_the_title( $post_id ) ), 'rfswp').' '.__('to trash '.esc_html( get_the_title( $post_id ) ), 'rfswp').'">'.__('Trash', 'rfswp').'</a>
            </span>
          </div>
          ';

          echo $html;
          break;
        case 'order_date':
          $order_date = self::order_data_item( $post_id, 'date' );
          echo date('d.m.Y H:i', $order_date);
          break;
        case 'order_total':
          $order_totals = self::order_data_item( $post_id, 'totals' );
          echo self::format_price( $order_totals['to_pay'] ).' '.pll_trans('zł', true);
          break;
        case 'order_paid':
          $status         = self::order_data_item( $post_id, 'status' );
          $transaction_id = self::order_data_item( $post_id, 'transaction_id' );
          $paid_amount    = self::order_data_item( $post_id, 'paid_amount' );
          $paid_status    = self::order_data_item( $post_id, 'paid_status' );
          $order_totals   = self::order_data_item( $post_id, 'totals' );
          
          $paid_amount    = $paid_amount ? $paid_amount : 0;
          $paid_status    = $paid_status ? $paid_status : 'none';
          
          $color          = $paid_status == 'less' || $paid_status == 'more' ? '#c00' : '#555';
          
          $paid_amount    = !$transaction_id && $status == 3 ? $order_totals['to_pay'] : $paid_amount;

          echo '<span style="color:'.$color.'">'.self::format_price( $paid_amount ).' '.pll_trans('zł', true).'</span>';
          break;
        case 'order_payment_method':
          $payment_method = self::order_data_item( $post_id, 'customer_payment_type' );
          switch($payment_method) {
            case 'transfer':
              echo __('Bank trasfer', 'rfswp');
              break;
            case 'online':
              echo __('Online payment', 'rfswp');
              break;
          }
          break;
        case 'order_status':
          $order_status = self::order_data_item( $post_id, 'status' );
          echo self::order_status($order_status);
          break;
      }
    }

    public function shop_endpoints() {
      add_rewrite_endpoint( 'podsumowanie', EP_PAGES | EP_PERMALINK );
    }

    public function shop_queryvars($query_vars) {
      $query_vars[] = 'podsumowanie';
      return $query_vars;
    }

    public function remove_product_order_meta_boxes() {
      remove_meta_box( 'submitdiv', 'corten-product-order', 'side' );
    }

    public function add_product_order_meta_boxes($post) {
      add_meta_box( 
        'products_order_details', 
        __('Order details', 'rfswp'), 
        array($this, 'products_order_details_html'), 
        'corten-product-order', 
        'normal',
        'high'
      );

      add_meta_box( 
        'products_order_summary', 
        __('Order', 'rfswp'), 
        array($this, 'products_order_summary_html'), 
        'corten-product-order', 
        'normal',
        'low'
      );

      add_meta_box( 
        'products_order_settings', 
        __('Settings', 'rfswp'), 
        array($this, 'products_order_settings_html'), 
        'corten-product-order', 
        'side',
        'high'
      );

      add_meta_box( 
        'products_order_transaction', 
        __('Payment details', 'rfswp'), 
        array($this, 'products_order_transaction_html'), 
        'corten-product-order', 
        'side',
        'high'
      );
    }

    public function products_order_details_html($post) {
      _get_template_part( 'details', 'shop/admin/product-order', array('post_id' => absint( $post->ID )) );
    }

    public function products_order_summary_html($post) {
      _get_template_part( 'summary', 'shop/admin/product-order', array('post_id' => absint( $post->ID )) );
    }

    public function products_order_settings_html($post) {
      _get_template_part( 'settings', 'shop/admin/product-order', array('post_id' => absint( $post->ID )) );
    }
    
    public function products_order_transaction_html($post) {
      _get_template_part( 'transaction', 'shop/admin/product-order', array('post_id' => absint( $post->ID )) );
    }

    public function save_product_order_settings($post_id) {
      $is_autosave     = wp_is_post_autosave( $post_id );
      $is_revision      = wp_is_post_revision( $post_id );
      $is_valid_nonce   = isset( $_POST['product_order_status_nonce'] ) && wp_verify_nonce( $_POST['product_order_status_nonce'], 'check_product_order_status_nonce' ) ? 'true' : 'false';
   
      if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
      }
   
      if( isset( $_POST['product-order-statuses'] ) ) {
        update_post_meta( $post_id, '_order_status', sanitize_text_field( $_POST['product-order-statuses'] ) );

        if( $_POST['product-order-statuses'] == 3 ) {
          $customer_email = CORTEN_SHOP::order_data_item($post_id, 'customer_email');

          $message_customer = CORTEN_EMAIL::email_template(
            'order-completed', 
            array(
              'send_to'   => 'customer',
              'order_id'  => $post_id
            ) 
          );
    
          $subject = get_bloginfo('name').' - '.__('Order Completed', 'rfswp');
    
          $send_to_customer = wp_mail( $customer_email, $subject, $message_customer );
        }
      }
    }

    // HELPERS //
    public static function products_available_init_query( $relation = 'AND' ) {
      $meta_query = array(
        'relation' => $relation,
        array(
        'key'     => '_product_available',
        'value'   => 1,
        'type'    => 'NUMERIC',
        'compare' => '='
        ),
        array(
          'key'     => '_product_stock',
          'value'   => 0,
          'type'    => 'NUMERIC',
          'compare' => '>'
        )
      );

      return $meta_query;
    }

    public static function is_product_available( $product_id ) {
      $product_type = get_post_type( $product_id );

      switch( $product_type ) {
        case 'corten-product':
          $available  = get_field('_product_available', $product_id);
          $stock      = get_field('_product_stock', $product_id);
    
          return $available == 1 && $stock > 0;
          break;
        case 'corten-package':
          return true;
          break;
        default:
          return false;
          break;
      }
    }

    public static function cart_product_types($products_array = false) {
      if( $products_array ) {
        $products = $products_array;
      }else {
        global $cortenCart;
        $cart_content = $cortenCart->get_cart_content();

        $products = isset($cart_content['products'] ) ? $cart_content['products'] : array();
      }
      
      $product_types_in_cart = array();

      if( $products ) {
        foreach($products as $product) {
          $product_type = get_post_type( $product['id'] );
          $product_types_in_cart[] = $product_type;
        }

        return $product_types_in_cart;
      }

      return array();
    }

    public function package_prefix($product_id) {
      $prefix_text = get_field('_package_prefix', 'option');
      $prefix = '';

      if( $prefix_text ) {
        $product_type = get_post_type( $product_id );
        $prefix       = $product_type == 'corten-package' ? $prefix_text.' - ' : '';
      }

      return $prefix;
    }

    public static function products_count() {
      $products = new WP_Query( array(
         'post_type'       => 'corten-product',
         'posts_per_page'  => -1,
         'post_status'     => 'publish',
         'meta_query'      => array(
            array(
              'key'     => '_product_available',
              'value'   => 1,
              'type'    => 'NUMERIC',
              'compare' => '='
            ),
            array(
              'key'     => '_product_stock',
              'value'   => 0,
              'type'    => 'NUMERIC',
              'compare' => '>'
            )
          )
      ) );

      if( $products ) {
        return $products->found_posts;
      }

      return false;
    }

    public static function shop_ppp( $type, $default = 9 ) {
      $ppp = get_field('_'.$type.'_ppp', 'option');

      return absint( $ppp ) > 0 ? absint( $ppp ) : $default;
    }

    public static function format_price( $price_raw, $dot = false ) {
      $sep = $dot ? '.' : ',';
      return number_format( $price_raw, 2, $sep, '' );
    }

    /**
     * This function will get product prices raw and formatted
     * @param $product_id (int)
     * @return 
    */
    public static function product_prices( $product_id ) {
      $price           = get_field('_product_price', $product_id);
      $sale_price      = get_field('_product_sale_price', $product_id);
      $show_prices_net = get_field('_shop_show_prices', 'option');

      $price_raw      = $price ? $price : 0;
      $sale_price_raw = $sale_price ? $sale_price : 0;
      $to_pay_raw     = $sale_price_raw > 0 ? $sale_price_raw : $price_raw;
      $tax            = get_field('_product_tax', $product_id) / 100;
      $tax_amount     = 0;

      if( absint( $show_prices_net ) != 1 ) {
        $price_raw = self::gross_price( $price_raw, $tax );
        $sale_price_raw = self::gross_price( $sale_price_raw, $tax );
        $to_pay_raw = self::gross_price( $to_pay_raw, $tax );
      }

      $tax_amount = $tax * $to_pay_raw;

      $data = array(
        'base' => array(
          'raw'       => $price_raw,
          'formatted' => self::format_price( $price_raw ),
        ),
        'sale' => array(
          'raw'       => $sale_price_raw,
          'formatted' => self::format_price( $sale_price_raw )
        ),
        'to_pay' => array(
          'raw'       => $to_pay_raw,
          'formatted' => self::format_price( $to_pay_raw )
        ),
        'tax'   => array(
          'raw'       => $tax_amount,
          'formatted' => self::format_price( $tax_amount )
        )
      );

      return $data;
    }

    public static function product_price_html( $product_id, $prepend_text = '' ) {
      $html = '';

      $prices     = self::product_prices( $product_id );
      $main_price = $prices['base']['formatted'];
      $old_price  = '';
      $suffix     = ' zł';
      $text       = $prepend_text ? '<strong>'.pll_trans($prepend_text, true).'</strong>' : '';

      if( $prices['sale']['raw'] > 0 ) {
        $main_price = $prices['sale']['formatted'];
        $old_price  = ' <span class="old-price">'.$prices['base']['formatted'].$suffix.'</span>';
      }

      $html .= '<p class="product-price">'.$text.$old_price.' <strong class="main-price">'.$main_price.$suffix.'</strong></p>';

      return $html;
    }

    public static function gross_price($price, $tax) {
      return $price + ($price * $tax);
    }

    public static function clear_products_filter( $clear = 'price' ) {
      $url = '';

      switch($clear) {
        case 'price':
          $url = '?psearch='.esc_attr( get_search_form_param('psearch') );
          break;
        case 'search':
          $url = '?pprice_min='.esc_attr( get_search_form_param('pprice_min') ).'&pprice_max='.esc_attr( get_search_form_param('pprice_max') );
          break;
      }

      return $url;
    }

    public static function is_shop() {
      global $rfs_login_page_id;
      global $rfs_profile_page_id;
      $parent = wp_get_post_parent_id( get_the_ID() );

      return is_page( _page_template_id('shop', true) ) || _page_template_id('shop', true) == $parent || is_singular('corten-product')  || is_singular('corten-package') || is_page( $rfs_login_page_id ) || is_page( $rfs_profile_page_id );
    }

    public static function shop_links($type) {
      global $rfs_login_page_id;
      global $rfs_profile_page_id;
      
      switch($type) {
        case 'login':
          return get_permalink( $rfs_login_page_id );
          break;
        case 'register':
          return get_permalink( $rfs_login_page_id ).'#register';
          break;
        case 'profile';
          return get_permalink( $rfs_profile_page_id );
          break;
        case 'cart';
          return get_permalink( _page_template_id('cart', true) );
          break;
        case 'products';
          return get_permalink( _page_template_id('products', true) );
          break;
        case 'packages';
          return get_permalink( _page_template_id('packages', true) );
          break;
        case 'checkout';
          return get_permalink( _page_template_id('checkout', true) );
          break;
        case 'summary':
          return get_permalink( _page_template_id('checkout', true) ).'podsumowanie';
        case 'profile-main':
          return get_permalink( $rfs_profile_page_id ).'szczegoly-konta';
        case 'profile-personal':
          return get_permalink( $rfs_profile_page_id ).'dane-osobowe';
        case 'profile-orders':
          return get_permalink( $rfs_profile_page_id ).'moje-zamowienia';
        break;
      }

      return $type;
    }

    public static function cart_loader() {
      return '<div class="cart-loader"><img src="'.esc_url( get_template_directory_uri() ).'/dist/assets/images/cart-loader.svg" alt="Loading..."></div>';
    }

    public static function cart_content() {
      global $cortenCart;

      $content = $cortenCart->get_cart_content(false, true);

      return array(
        'content' => $content,
        'count'   => count( $content )
      );
    }

    public static function shop_header_cart( $cart_content = false ) {
      if( !$cart_content ) {
        global $cortenCart;
        $cart_content = $cortenCart->get_cart_content(false, true);
      }

      $count = count( $cart_content );
      $text   = pll_trans('produkt', true);
      $cart_url = self::shop_links('cart');

      if( $count > 1 ) {
        if( ($count % 10 == 2 || $count % 10 == 3 || $count % 10 == 4) && ($count % 100 < 11 || $count % 100 > 19) ) {
          $text = pll_trans('produkty', true);
        }else {
          $text = pll_trans('produktów', true);
        }
      }

      if( $count == 0 ) {
        $text = pll_trans('produktów', true);
      }

      $html = '
      <a href="'.esc_url( $cart_url ).'" class="shop-header-cart">
        <i class="fa fa-shopping-cart"></i>
        <p><strong>'.absint( $count ).'</strong> <span>'.esc_html( $text ).'</span></p>
      </a>
      ';

      return array(
        'count' => absint( $count ),
        'text'  => esc_html( $text ),
        'html'  => $html
      );
    }

    public static function cart_summary( $cart_content = false, $formatted = false, $shipment_id = 0 ) {
      if( !$cart_content ) {
        global $cortenCart;
        $cart_content =  $cortenCart->get_cart_content(false, true);
      }

      $shipment_cost 		= get_field('_sm_cost', $shipment_id);
      $show_prices_net  = get_field('_shop_show_prices', 'option');
      
      $total  = 0;
      $tax    = 0;

      if( $cart_content ) {
        foreach($cart_content as $product) {
          $prices       = self::product_prices( $product['id'] );
          $tax_amount   = $product['qty'] * $prices['tax']['raw'];
          $price_raw    = $prices['to_pay']['raw'];
          $price_total  = $product['qty'] * $price_raw;

          $total += $price_total;
          $tax   += $tax_amount;
        }
      }
      
      $data   = array();
			
      $tax_in_total = absint( $show_prices_net ) != 1 ? 0 : $tax;

      if( $formatted ) {
        $data = array(
          'total'     => self::format_price( $total ),
          'tax'       => self::format_price( $tax ),
          'shipment'  => self::format_price( $shipment_cost ),
          'to_pay'    => self::format_price( $total + $shipment_cost + $tax_in_total )
        );
      }else {
        $data = array(
          'total'     => $total,
          'tax'       => $tax,
          'shipment'  => $shipment_cost,
          'to_pay'    => $total + $shipment_cost + $tax_in_total
        );
      }

      return $data;
    }

    public static function cart_products_prices( $products, $formatted = false ) {

      $data = array();

      if( is_array( $products ) ) {
        if( is_array( $products[0] ) ) { // is multidimentional array
          
          foreach($products as $product) {
            $prices       = self::product_prices( $product['id'] );
            $price_raw    = $prices['to_pay']['raw'];
            $price_total  = $product['qty'] * $price_raw;

            $data[] = array(
              'id'  => $product['id'],
              'sum' => $formatted ? self::format_price( $price_total ) : $price_total,
              'qty' => absint( $product['qty'] )
            );
          }
        }else {
          foreach($products as $id => $qty) { // is flat pairs array - [key] => [value]
            $prices       = self::product_prices( $id );
            $price_raw    = $prices['to_pay']['raw'];
            $price_total  = $qty * $price_raw;

            $data[] = array(
              $id => $formatted ? self::format_price( $price_total ) : $price_total
            );
          }
        }
      }

      return $data;
    }

    public static function shipment_methods( $default = false ) {
      global $cortenCart;
      $selected_shipment_id = $cortenCart->get_cart_content(false, false, true);
      $default_shipment_id  = absint( get_field('_shipment_default', 'option') );

      if( $selected_shipment_id > 0 ) {
        $default_method_id = $selected_shipment_id;
      }else {
        $default_method = new WP_Query( array(
          'post_type'      => 'corten-shipment',
          'posts_per_page' => 1,
          'post_status'    => 'publish',
          'post__in'       => array( $default_shipment_id ),
          'meta_query'     => array(
            array(
              'key'      => '_sm_active',
              'value'    => 1,
              'type'     => 'NUMERIC',
              'compare'  => '='
            )
          )
        ) );
        
        if( $default_method->have_posts()) {
          $default_method_id = $default_method->posts[0]->ID;
        }else {
          $default_method_id = 0;
        }; wp_reset_postdata();
      }

      $shipment_methods = new WP_Query( array(
         'post_type'      => 'corten-shipment',
         'posts_per_page' => -1,
         'post_status'    => 'publish',
         'post__in'       => $post_in,
         'meta_query'     => array(
            array(
              'key'      => '_sm_active',
              'value'    => 1,
              'type'     => 'NUMERIC',
              'compare'  => '='
            )
          ),
      ) );

      $methods = array();
      
      if( $shipment_methods->have_posts()) {
        $i=1; while($shipment_methods->have_posts()) { $shipment_methods->the_post();
          $name             = get_the_title();
          $cost             = get_field('_sm_cost');
          $is_default       = get_the_ID() == $default_method_id;
          $list_active      = get_field('_sm_add_list');
          $list_placeholder = get_field('_sm_list_placeholder');
          $list             = get_field('_sm_list');

          if( $default_method_id == 0 ) {
            $is_default = $i == 1;
            $default_method_id = $shipment_methods->posts[0]->ID;
          }

          $methods[] = array(
            'id'                => absint( get_the_ID() ),
            'name'              => esc_html( $name ),
            'cost'              => $cost,
            'default'           => $is_default,
            'list_active'       => absint( $list_active == 1 ) && $list,
            'list_placeholder'  => esc_html( $list_placeholder ),
            'list'              => $list
          );
        }
      $i++; }; wp_reset_postdata();
      
      // if( $default_method_id != $selected_shipment_id) {
      //   $cortenCart->update_shipment_method($default_method_id);
      // }

      return $default ? $default_method_id : $methods;
    }

    public static function order_data_item($post_id, $item) {
      return get_post_meta( $post_id, '_order_'.$item, true );
    }

    public static function customer_data_item($item, $user_id = false) {
      $user_id = $user_id ? $user_id : get_current_user_id();

      if( $user_id == 0 ) {
        return;
      }

      if( $item == 'email' ) {
        if( get_user_meta( $user_id, '_customer_'.$item, true ) ) {
          return get_user_meta( $user_id, '_customer_'.$item, true );
        }else {
          $userdata = get_userdata( $user_id );
          return $userdata->user_email;
        }
      }else {
        return get_user_meta( $user_id, '_customer_'.$item, true );
      }
    }

    public static function order_status($status_number) {
      switch($status_number) {
        case 1:
          return '<span style="color:#e6880e">'.__('New', 'rfswp').'</span>';
          break;
        case 2:
          return '<span style="color:#2b93fc">'.__('In progress', 'rfswp').'</span>';
          break;
        case 3:
          return '<span style="color:#00cd00">'.__('Completed', 'rfswp').'</span>';
          break;
        case 4:
          return '<span style="color:#cc0404">'.__('Canceled', 'rfswp').'</span>';
          break;
        case 5:
          return '<span style="color:#9e9d9d">'.__('Withheld (waiting for payment)', 'rfswp').'</span>';
          break;
        case 6:
          return '<span style="color:#00cd00">'.__('Paid', 'rfswp').'</span>';
          break;
        case 7:
          return '<span style="color:#00cd00">'.__('Sent', 'rfswp').'</span>';
          break;
      }

      return false;
    }

    public static function order_summary_page_url($type) {
      $base = self::shop_links('summary');

      return in_array( $type, self::$allowed_summary_types ) ? esc_url( $base.'/'.$type ) : false;
    }

    // public static function is_order_summary($type = false) {
    //   if( $type ) {
    //     return get_query_var( 'podsumowanie' ) == $type && in_array( $type, self::$allowed_summary_types );
    //   }else {
    //     foreach(self::$allowed_summary_types as $type) {
    //       if( get_query_var( 'podsumowanie' ) == $type ) {
    //         return true;
    //       }
    //     }
    //   }

    //   return false;
    // }

    public static function is_order_summary_any_page() {
      $data = explode('/', get_query_var( 'podsumowanie' ) );

      if( $data && count( $data ) == 3 ) {
        $type = $data[0].'/'.$data[1];

        return in_array($type, self::$allowed_summary_types);
      }

      return false;
    }

    public static function is_order_summary_page($payment_method, $status = 'completed') {
      $data = explode('/', get_query_var( 'podsumowanie' ) );
      
      if( $data && count( $data ) == 3 ) {
        if( $data[0] == $payment_method && $data[1] == $status ) {
          return $data[2];
        }
      }

      return false;
    }

    public static function order_exists($order_code = false) {
      global $wpdb;
      $code = $order_code;

      if( !$order_code ) {
        $data = explode('/', get_query_var( 'podsumowanie' ) );

        if( $data && count( $data ) == 3 ) {
          $code = $data[2];
        }
      }

      if( $code ) {
        $exists = new WP_Query( array(
           'post_type'      => 'corten-product-order',
           'posts_per_page' => 1,
           'post_status'    => 'publish',
           'meta_query'     => array(
              'key'   => '_order_code',
              'value' => $code
           )
        ) );

        if( $exists->have_posts() ) {
          return $exists->posts[0]->ID;
        }; wp_reset_postdata();
      }

      return false;
    }

    public static function update_order_status($status, $post_id) {
      update_post_meta( $post_id, '_order_status', $status );
    }
		
		public static function update_stock( $products, $type = 'remove', $order_id = false ) {
			foreach( $products as $product ) {
				$product_id 	= absint( $product['id'] );
				$product_qty 	= absint( $product['qty'] );

				$current_stock 	= get_field('_product_stock', $product_id);
				$new_stock 			= $current_stock;

				if( $type == 'remove' ) {
					$new_stock = $current_stock - $product_qty;
				}

				if( $type == 'add' && $order_id ) {
          $updated = get_post_meta( $order_id, '_stock_updated', true );
          
          if( absint($updated) == 0 ) {
            $new_stock = $current_stock + $product_qty;
            update_post_meta( $order_id, '_stock_updated', 1 );
          }
				}

				update_field( '_product_stock', $new_stock, $product_id );
			}
		}

  }

  new CORTEN_SHOP;

}
?>