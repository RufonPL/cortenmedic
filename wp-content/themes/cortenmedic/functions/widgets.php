<?php
if( !class_exists('RFS_WIDGETS') ) {

  class RFS_WIDGETS {

    /**
     * This function will get widget depending on its type
     * @param $type (string)
     * @param $widget_id (int)
     * @return 
    */
    public static function show_widget($type, $widget_id, $index = false) {
      $widget = TEMPLATEPATH.'/widgets/widget-'.$type.'.php';

      if( file_exists( $widget ) ) {
        $grey_bg  = get_field('_widget_grey_bg', $widget_id);
        $bg_class = $grey_bg ? ' widget-bg-grey' : '';
        echo '<div class="containe-fluid widget-bg'.$bg_class.' widget-container-'.$type.'">';
        include $widget;
        echo '</div>';
      }
    }

    public static function loop_widgets($widgets) {
      if( !is_array( $widgets) ) return false;

      if( $widgets ) {
        $i=1; foreach($widgets as $widget_id) {
          $widget_type 	= get_field('_widget_type', $widget_id);

          if( $widget_type ) {
            self::show_widget($widget_type['value'], $widget_id, $i);
          }
        $i++; }
      }
    }

    /**
     * This function will return wp query object for given post type and/or chosen posts for use in a widget
     * @param $widget_id (int)
     * @param $type (string)
     * @param $limit (int)
     * @param $cutom_posts (array) - optional
     * @return object
    */
    public static function get_widget_posts($widget_id, $type, $limit, $cutom_posts = false, $orderby_other = '') {
      $posts    = get_transient('widget_'.$widget_id.'_posts');
      $post_in  = $cutom_posts ? $cutom_posts : array();
      $orderby  = $cutom_posts ? 'post__in' : 'menu_order';

      if( $orderby_other ) {
        $orderby = $orderby_other;
      }
      
      if( $posts === false ) {
        $posts = new WP_Query( array(
          'post_type'       => $type,
          'posts_per_page'  => $limit,
          'post_status'     => 'publish',
          'post__in'        => $post_in,
          'orderby'         => $orderby,
        ) );

      set_transient( 'widget_'.$widget_id.'_posts', $posts, 86400 );
     }

      return $posts;
    }

    /**
     * This function will return wp terms object for given taxonomy and/or chosen terms for use in a widget
     * @param $widget_id (int)
     * @param $axonomy (string)
     * @param $limit (int)
     * @param $cutom_terms (array) - optional
     * @return object
    */
    public static function get_widget_terms($widget_id, $taxonomy, $limit, $custom_terms = false) {
      $terms = get_transient('widget_'.$widget_id.'_terms');
      
      if( $terms === false ) {
        $terms = get_terms( array(
          'taxonomy'    => 'services-groups',
          'include'     => $custom_terms ? $custom_terms : array(),
          'hide_empty'  => false,
          'number'      => $limit
        ) );

        set_transient( 'widget_'.$widget_id.'_terms', $terms, 86400 ); 
      }

      return $terms;
    }

    /**
     * This function will render widget type header html
     * @param $text (string / html)
     * @return html
    */
    public static function widget_header($text) {
      $html = '';

      if( $text ) {
        $html .= '<div class="widget-item-header text-center">
          <span></span>
          <h5>'.wp_kses( _p2br( $text ), array( 'br' => array(), 'strong' => array() ) ).'</h5>
        </div>';
      }

      return $html;
    }

    /**
     * This function will render widget header html depending on chosen style
     * @param $text (string / html)
     * @return html
    */
    public static function widget_header_by_style($text, $style = 'style1', $sm = false, $ksesbrstrong = false) {
      return $style == 'style2' ? _section_header( _p2br( $text ), $sm, $ksesbrstrong ) : self::widget_header( $text );
    }

    /**
     * This function will render widget badge depending on chosen option
     * @param $post_id (int)
     * @param $badge_shows (string)
     * @return html
    */
    public static function widget_badge($post_id, $badge_shows) {
      $html = '';
      $cats = '';

      switch($badge_shows) {
        case 'date':
          $html .= esc_html( get_the_time('d.m.Y') );
          break;
        case 'cat':
          $post_type  = get_post_type( $post_id );

          switch( $post_type ) {
            case 'institution':
                $taxonomy = 'institution-city';
                $cat_link = false;
              break;
            default:
              $cat_link = true;
          }

          $categories = $post_type == 'post' ? get_the_category($post_id) : wp_get_post_terms($post_id, $taxonomy);

          if( $categories ) {
            foreach($categories as $category) {
              $link = $post_type == 'post' ? get_category_link( $category->term_id ) : get_term_link( $category->term_id );
              $cats .= $cat_link ? '<a href="'.esc_url( $link ).'">'.esc_html( $category->name ).'</a> | ' : esc_html( $category->name ).' | ';
            }
            $html .= rtrim( $cats, '| ' );
          }else {
            $html .= esc_html( get_the_time('d.m.Y') );
          }
          break;
      }
      
      return '<span class="widget-badge">'.$html.'</span>';
    }

    /**
     * This function will render html for bigbox btns
     * @param $btns (acf repeater)
     * @return html
    */
    public static function widget_bigbox_btns($btns) {
      $html = '';

      if( $btns ) {
        $html .= '<div class="row widget-bb-btns">';
          $i=1 ;foreach($btns as $btn) {
            $link_type  = $btn['_link_type'];
            $link_inner = $btn['_link_inner'];
            $link_outer = $btn['_link_outer'];
            $link_text  = $btn['_text'];
            $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;
            $btn_class  = $i == 1 ? 'btn-success' : 'btn-warning';
            $btn_align  = $i == 1 ? 'text-right' : 'text-left';

            if( $link_text ) {
              $html .= '<div class="col-sm-6 '.$btn_align.'"><a href="'.esc_url( $link ).'" class="btn '.$btn_class.'">'.esc_html( $link_text ).'</a></div>';
            }
          $i++; }
        $html .= '</div>';
      }

      return $html;
    }

  }

}
?>