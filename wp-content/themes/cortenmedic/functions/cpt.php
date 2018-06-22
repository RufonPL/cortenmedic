<?php  
if( !class_exists('RFS_CPT') ) {

  class RFS_CPT {

    public function __construct() {
      add_action( 'init', array($this, 'register_advice_post_type') ); 
			add_action( 'init', array($this, 'register_advice_taxonomies'), 9 );

      add_action( 'init', array($this, 'register_institution_post_type') ); 
			add_action( 'init', array($this, 'register_institution_taxonomies') );
      
      add_action( 'init', array($this, 'register_services_post_type') ); 
      add_action( 'init', array($this, 'register_clinic_post_type') ); 
			add_action( 'init', array($this, 'register_services_groups_taxonomy') );

      add_action( 'init', array($this, 'register_doctor_post_type') );
			add_action( 'init', array($this, 'register_doctor_taxonomies') );

      add_action( 'init', array($this, 'register_widget_post_type') );

      add_action( 'init', array($this, 'register_contact_branch_post_type') );
      
      add_action( 'init', array($this, 'register_job_post_type') ); 
			add_action( 'init', array($this, 'register_job_taxonomies') );

      add_filter( 'manage_edit-widget_columns', array($this, 'widgets_edit_column_admin_table') );
			add_action( 'manage_widget_posts_custom_column', array($this, 'widgets_show_data_column_admin_table'), 10, 2 );
			add_filter( 'manage_edit-widget_sortable_columns', array($this, 'widgets_sortable_columns') );
			add_action( 'admin_head', array($this, 'widgets_columns_css') );

      add_filter( 'manage_edit-services-groups_columns', array($this, 'modify_services_groups_columns') ); 
      add_action( 'services-groups_edit_form', array($this, 'hide_services_groups_description') );
      add_action( 'services-groups_add_form', array($this, 'hide_services_groups_description') );
      
      add_filter( 'manage_edit-specialization_columns', array($this, 'modify_specialization_columns') ); 
      add_action( 'specialization_edit_form', array($this, 'hide_specialization_description') );
      add_action( 'specialization_add_form', array($this, 'hide_specialization_description') );
      
      add_filter( 'manage_edit-job-type_columns', array($this, 'modify_job_type_columns') ); 
      add_action( 'job-type_edit_form', array($this, 'hide_job_type_description') );
      add_action( 'job-type_add_form', array($this, 'hide_job_type_description') );
      
      add_filter( 'terms_clauses', array($this, 'allow_get_terms_by_post_type'), 99999, 3);
    }

    public function register_advice_post_type() {
      register_post_type( 'advice', array(
        'labels'	=> array(
          'name'               => __('Advice', 'rfswp'),
          'singular_name'      => __('Advice', 'rfswp'),
          'add_new'            => __('Add advice', 'rfswp'),
          'add_new_item'       => __('Add new advice', 'rfswp'),
          'edit_item'          => __('Edit advice', 'rfswp'),
          'new_item'           => __('New advice', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Advice:', 'rfswp'),
          'menu_name'          => __('Advice', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 6,
        'menu_icon'           	=> 'dashicons-lightbulb',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'editor',
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'porada'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_advice_taxonomies() {
			register_taxonomy('advice-category', 'advice', array(
				'labels'	=> array(
					'name'				      => __( 'Categories - Advice', 'rfswp' ),
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
				'public'				=> true,
				'show_tag_cloud'		=> false,
				'hierarchical'			=> true,
			));
		}

    public function register_institution_post_type() {
      register_post_type( 'institution', array(
        'labels'	=> array(
          'name'               => __('Institutions', 'rfswp'),
          'singular_name'      => __('Institution', 'rfswp'),
          'add_new'            => __('Add institution', 'rfswp'),
          'add_new_item'       => __('Add new institution', 'rfswp'),
          'edit_item'          => __('Edit institution', 'rfswp'),
          'new_item'           => __('New institution', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Institutions:', 'rfswp'),
          'menu_name'          => __('Institutions', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 7,
        'menu_icon'           	=> 'dashicons-building',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'placowka'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_services_post_type() {
      register_post_type( 'service', array(
        'labels'	=> array(
          'name'               => __('Services', 'rfswp'),
          'singular_name'      => __('Service', 'rfswp'),
          'add_new'            => __('Add service', 'rfswp'),
          'add_new_item'       => __('Add new service', 'rfswp'),
          'edit_item'          => __('Edit service', 'rfswp'),
          'new_item'           => __('New service', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Services:', 'rfswp'),
          'menu_name'          => __('Services', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> true,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 8,
        'menu_icon'           	=> 'dashicons-plus-alt',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'usluga'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_clinic_post_type() {
      register_post_type( 'clinic', array(
        'labels'	=> array(
          'name'               => __('Clinics', 'rfswp'),
          'singular_name'      => __('Clinic', 'rfswp'),
          'add_new'            => __('Add clinic', 'rfswp'),
          'add_new_item'       => __('Add new clinic', 'rfswp'),
          'edit_item'          => __('Edit clinic', 'rfswp'),
          'new_item'           => __('New clinic', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Clinics:', 'rfswp'),
          'menu_name'          => __('Clinics', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 9,
        'menu_icon'           	=> 'dashicons-plus-alt',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'poradnia'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_institution_taxonomies() {
			register_taxonomy('institution-city', array('institution', 'post'), array(
				'labels'	=> array(
					'name'				      => __( 'Cities', 'rfswp' ),
					'singular_name'		  => __( 'City', 'rfswp'),
					'menu_name'			    => __( 'Cities', 'rfswp' ),
					'all_items'			    => __( 'All cities', 'rfswp' ),
					'edit_item'			    => __( 'Edit city', 'rfswp' ),
					'view_item'			    => __( 'View city', 'rfswp' ),
					'update_item'		    => __( 'Update city', 'rfswp' ),
					'add_new_item'		  => __( 'Add new city', 'rfswp' ),
					'new_item_name'		  => __( 'New city name', 'rfswp' ),
					'parent_item'		    => __( 'Parent city', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent city:', 'rfswp' ),
					'search_items'		  => __( 'Search cities', 'rfswp' ),
					'popular_items'		  => __( 'Popular cities', 'rfswp' ) 
				),
				'public'				      => false,
        'publicly_queryable'  => false,
        'show_ui'             => true, // show in admin
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true, //show in admin menu - show_ui must be true
				'show_tag_cloud'		  => false,
        'show_admin_column'   => true,
				'hierarchical'			  => true,
			));
		}

    public function register_services_groups_taxonomy() {
			register_taxonomy('services-groups', array('clinic', 'service'), array(
				'labels'	=> array(
					'name'				      => __( 'Services Groups', 'rfswp' ),
					'singular_name'		  => __( 'Services Group', 'rfswp'),
					'menu_name'			    => __( 'Services Groups', 'rfswp' ),
					'all_items'			    => __( 'All groups', 'rfswp' ),
					'edit_item'			    => __( 'Edit group', 'rfswp' ),
					'view_item'			    => __( 'View group', 'rfswp' ),
					'update_item'		    => __( 'Update group', 'rfswp' ),
					'add_new_item'		  => __( 'Add new group', 'rfswp' ),
					'new_item_name'		  => __( 'New group name', 'rfswp' ),
					'parent_item'		    => __( 'Parent group', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent group:', 'rfswp' ),
					'search_items'		  => __( 'Search groups', 'rfswp' ),
					'popular_items'		  => __( 'Popular groups', 'rfswp' ) 
				),
				'public'				      => true,
				'show_tag_cloud'		  => false,
				'hierarchical'			  => true,
        'show_admin_column'   => true,
        'rewrite'             => array(
          'slug' => 'grupa-uslug'
        ),
			));
		}

    public function register_doctor_post_type() {
      register_post_type( 'doctor', array(
        'labels'	=> array(
          'name'               => __('Doctors', 'rfswp'),
          'singular_name'      => __('Doctor', 'rfswp'),
          'add_new'            => __('Add doctor', 'rfswp'),
          'add_new_item'       => __('Add new doctor', 'rfswp'),
          'edit_item'          => __('Edit doctor', 'rfswp'),
          'new_item'           => __('New doctor', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Doctors:', 'rfswp'),
          'menu_name'          => __('Doctors', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 11,
        'menu_icon'           	=> 'dashicons-businessman',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'lekarz'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_doctor_taxonomies() {
			register_taxonomy('specialization', 'doctor', array(
				'labels'	=> array(
					'name'				      => __( 'Specializations', 'rfswp' ),
					'singular_name'		  => __( 'Specialization', 'rfswp'),
					'menu_name'			    => __( 'Specializations', 'rfswp' ),
					'all_items'			    => __( 'All specializations', 'rfswp' ),
					'edit_item'			    => __( 'Edit specialization', 'rfswp' ),
					'view_item'			    => __( 'View specialization', 'rfswp' ),
					'update_item'		    => __( 'Update specialization', 'rfswp' ),
					'add_new_item'		  => __( 'Add new specialization', 'rfswp' ),
					'new_item_name'		  => __( 'New specialization name', 'rfswp' ),
					'parent_item'		    => __( 'Parent specialization', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent specialization:', 'rfswp' ),
					'search_items'		  => __( 'Search specializations', 'rfswp' ),
					'popular_items'		  => __( 'Popular specializations', 'rfswp' ) 
				),
				'public'				      => false,
        'publicly_queryable'  => false,
        'show_ui'             => true, // show in admin
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true, //show in admin menu - show_ui must be true
				'show_tag_cloud'		  => false,
        'show_admin_column'   => true,
				'hierarchical'			  => true,
			));
		}

    public function register_widget_post_type() {
      register_post_type( 'widget', array(
        'labels'	=> array(
          'name'               => __('Widgets', 'rfswp'),
          'singular_name'      => __('Widget', 'rfswp'),
          'add_new'            => __('Add widget', 'rfswp'),
          'add_new_item'       => __('Add new widget', 'rfswp'),
          'edit_item'          => __('Edit widget', 'rfswp'),
          'new_item'           => __('New widget', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Widgets:', 'rfswp'),
          'menu_name'          => __('Widgets', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> true,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 12,
        'menu_icon'           	=> 'dashicons-feedback',
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

    public function register_contact_branch_post_type() {
      register_post_type( 'contact-branch', array(
        'labels'	=> array(
          'name'               => __('Contact branches', 'rfswp'),
          'singular_name'      => __('Contact branch', 'rfswp'),
          'add_new'            => __('Add branch', 'rfswp'),
          'add_new_item'       => __('Add new branch', 'rfswp'),
          'edit_item'          => __('Edit branch', 'rfswp'),
          'new_item'           => __('New branch', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Contact branches:', 'rfswp'),
          'menu_name'          => __('Contact branches', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> false,
        'exclude_from_search' 	=> true,
        'publicly_queryable'  	=> false,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> false,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 13,
        'menu_icon'           	=> 'dashicons-testimonial',
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

    public function register_job_post_type() {
      register_post_type( 'job-offer', array(
        'labels'	=> array(
          'name'               => __('Job offers', 'rfswp'),
          'singular_name'      => __('Job offer', 'rfswp'),
          'add_new'            => __('Add job offer', 'rfswp'),
          'add_new_item'       => __('Add new job offer', 'rfswp'),
          'edit_item'          => __('Edit job offer', 'rfswp'),
          'new_item'           => __('New job offer', 'rfswp'),
          'view_item'          => __('View', 'rfswp'),
          'search_items'       => __('Search', 'rfswp'),
          'not_found'          => __('Nothing found', 'rfswp'),
          'not_found_in_trash' => __('Trash is empty', 'rfswp'),
          'parent_item_colon'  => __('Job offers:', 'rfswp'),
          'menu_name'          => __('Job offers', 'rfswp'),
        ),
        'description'         	=> '',
        'public'              	=> true,
        'exclude_from_search' 	=> false,
        'publicly_queryable'  	=> true,
        'show_ui'             	=> true, // show in admin
        'show_in_nav_menus'   	=> true,
        'show_in_menu'        	=> true, //show in admin menu - show_ui must be true
        'menu_position'       	=> 14,
        'menu_icon'           	=> 'dashicons-clipboard',
        'capability_type'     	=> 'post', 
        'hierarchical'        	=> false,
        'supports'            	=> array(
          'title', 
          'page-attributes', 
          'revisions'
        ),
        'rewrite'               => array(
          'slug'                => 'oferta-pracy'
        ),
        'has_archive'         	=> false,
        'query_var'           	=> true,
        'can_export'          	=> true,
      ) );
    }

    public function register_job_taxonomies() {
			register_taxonomy('job-type', array('job-offer'), array(
				'labels'	=> array(
					'name'				      => __( 'Job types', 'rfswp' ),
					'singular_name'		  => __( 'Job type', 'rfswp'),
					'menu_name'			    => __( 'Job types', 'rfswp' ),
					'all_items'			    => __( 'All job types', 'rfswp' ),
					'edit_item'			    => __( 'Edit job type', 'rfswp' ),
					'view_item'			    => __( 'View job type', 'rfswp' ),
					'update_item'		    => __( 'Update job type', 'rfswp' ),
					'add_new_item'		  => __( 'Add new job type', 'rfswp' ),
					'new_item_name'		  => __( 'New job type name', 'rfswp' ),
					'parent_item'		    => __( 'Parent job type', 'rfswp' ),
					'parent_item_colon'	=> __( 'Parent job type:', 'rfswp' ),
					'search_items'		  => __( 'Search job types', 'rfswp' ),
					'popular_items'		  => __( 'Popular job types', 'rfswp' ) 
				),
				'public'				      => false,
        'publicly_queryable'  => false,
        'show_ui'             => true, // show in admin
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true, //show in admin menu - show_ui must be true
				'show_tag_cloud'		  => false,
        'show_admin_column'   => true,
				'hierarchical'			  => true,
			));
		}

    public function widgets_edit_column_admin_table($columns) {
			$new_order = array();
			foreach($columns as $key => $title) {
        if ($key=='title') {
					$new_order['widget_thumbnail'] = 'Podgląd';
				}
				if ($key=='date') {
					$new_order['widget_type'] = 'Rodzaj widgetu';
				}
				$new_order[$key] = $title;
			}
			return $new_order;
		}

		public function widgets_show_data_column_admin_table($columns, $post_id) {
			switch($columns) {
				case 'widget_type':
					$type = get_field('_widget_type', $post_id);
					if( $type ) {
            echo esc_html( $type['label'] );
          }else {
            echo 'Nie wybrano';
          }
					break;
        case 'widget_thumbnail':
					$thumbnail = get_field('_widget_thumbnail', $post_id);
					if( $thumbnail ) {
            echo '<img src="'.esc_url( $thumbnail['sizes']['thumbnail'] ).'" alt="'.esc_attr( $thumbnail['alt'] ).'">';
          }
					break;
			}
		}

		public function widgets_sortable_columns( $columns ) {
			$columns['widget_type'] = 'widget_type';

			return $columns;
		}

    public function widgets_columns_css() {
      global $pagenow;

      if( !is_admin() || $pagenow != 'edit.php' ) return;

      echo '
      <style>
      #widget_thumbnail {width: 120px;}
      .widget_thumbnail img {width: 100px !important;}
      </style>
      ';
    }
 
    public function modify_services_groups_columns($columns) {
      if( isset( $columns['description'] ) ) {
        unset( $columns['description'] ); 
      }

      return $columns;
    }

    public function hide_services_groups_description() { 
      echo '<style>.term-description-wrap{display:none;}</style>';
    }
 
    public function modify_specialization_columns($columns) {
      if( isset( $columns['description'] ) ) {
        unset( $columns['description'] ); 
      }

      return $columns;
    }

    public function hide_specialization_description() { 
      echo '<style>.term-description-wrap, #wp-description-wrap{display:none;}</style>';
    }
 
    public function modify_job_type_columns($columns) {
      if( isset( $columns['description'] ) ) {
        unset( $columns['description'] ); 
      }

      return $columns;
    }

    public function hide_job_type_description() { 
      echo '<style>.term-description-wrap, #wp-description-wrap{display:none;}</style>';
    }

    public function allow_get_terms_by_post_type( $clauses, $taxonomy, $args ) {
      global $wpdb;
      if( isset( $args['post_types'] ) ) {
        $post_types = $args['post_types'];
        // allow for arrays
        if( is_array($args['post_types']) ) {
          $post_types = implode("','", $args['post_types']);
        }
        $clauses['join'] .= " INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id";
        $clauses['where'] .= " AND p.post_type IN ('". esc_sql( $post_types ). "') GROUP BY t.term_id";
      }
      return $clauses;
    }

  }

  new RFS_CPT;
}
?>