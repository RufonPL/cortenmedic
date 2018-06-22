<?php  
$style        = get_field('_widget_style', $widget_id);
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$posts_type   = get_field('_widget_posts_types', $widget_id);
$badge_shows  = get_field('_widget_badge_shows_'.$posts_type, $widget_id);
$show_posts   = get_field('_widget_posts', $widget_id);
$custom_posts = get_field('_widget_posts_custom_'.$posts_type, $widget_id);
$btn_all      = get_field('_widget_all_posts_btn', $widget_id);
$btn_all_link = get_field('_widget_all_posts_btn_link', $widget_id);
$btn_all_text = get_field('_widget_all_posts_btn_text', $widget_id);
$btn_all_text = $btn_all_text ? $btn_all_text : pll_trans('Zobacz wszystkie', true);

$content_type = get_field('_widget_posts_content_type', $widget_id);

$custom_posts = $show_posts == 'custom' ? $custom_posts : false;
?>

<?php if( $content_type == 'custom' ) : ?>

  <?php $custom_content = get_field('_widget_posts_carousel_custom_content', $widget_id); ?>

  <?php if( $custom_content ) : ?>
  <?php 
    $center_content = get_field('_widget_posts_custom_content_center', $widget_id);
    $found = count($custom_content); 
    switch($found) {
      case 1:
        $col = 'col-sm-12';
        break;
      case 2:
        $col = 'col-sm-6';
        break;
      case 3:
        $col = 'col-sm-4';
        break;
      default:
        $col = 'col-sm-3';
        break;
    }
    ?>
  <div class="container">
    <div class="wiget-item widget-posts-box">
      <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

      <div class="widget-pb-posts row">
        <?php $i=1; foreach($custom_content as $item) : ?>
          <?php 
          $thumbnail  = $item['_image'];
          $text       = $item['_text'];
          $content    = $item['_content'];
          $link_type  = $item['_link_type'];
          $link_inner = $item['_link_inner'];
          $link_outer = $item['_link_custom'];
          $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;
          ?>
          <div class="<?php echo $col; ?> widget-pb-post">
            <div class="widget-pb-post-inner<?php if( $center_content ) : ?> text-center<?php endif; ?>">
              <?php if($thumbnail) : ?>
              <div class="widget-pb-post-image">
                <a href="<?php echo esc_url( $link ); ?>">
                  <?php if( $style == 'style3' ) : ?><div class="widget-pb-post-image-mask"></div><?php endif; ?>
                  <img src="<?php echo esc_url( $thumbnail['sizes']['post-image'] ); ?>" alt="<?php echo esc_attr( $thumbnail['alt'] ); ?>">
                </a>
              </div>
              <?php endif; ?>
              <a class="widget-pb-post-title widget-pb-pt-<?php echo esc_html( $style ); ?>" href="<?php echo esc_url( $link ); ?>">
                <?php if( $text ) : ?><h4><?php echo esc_html( $text ); ?></h4><?php endif; ?>
                <?php if( $style == 'style2' ) : ?><i class="fa fa-angle-double-right"></i><?php endif; ?>
              </a>
              <?php if( !_empty_content( $content ) && ( $style == 'style1' || $style == 'style2' ) ) : ?>
              <a class="widget-pb-post-content widget-pb-pt-<?php echo esc_html( $style ); ?>" href="<?php echo esc_url( $link ); ?>">
                <p><?php echo wp_kses( $content, array( 'br' => array() ) ); ?></p>
              </a>
              <?php endif; ?>
            </div>
          </div>
          <?php if( $i == 4 ) : ?><?php break; ?><?php endif; ?>
        <?php $i++; endforeach; ?>
      </div>
      <?php if( $btn_all ) : ?>
      <div class="widget-bp-all text-center <?php if( $style != 'style1' ) : ?>widget-bp-all-space<?php endif; ?>">
        <a href="<?php echo esc_url( get_permalink( $btn_all_link ) ); ?>" class="btn <?php if( is_page( _page_template_id('recruitment-main', true) ) ) : ?>btn-primary<?php else : ?>btn-info<?php endif; ?>"><?php echo esc_html( $btn_all_text ); ?></a>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

<?php else : ?>

  <?php if( $posts_type == 'services-groups' ) : ?>

    <?php $terms = RFS_WIDGETS::get_widget_terms( $widget_id, $posts_type, 4, $custom_posts ); ?>

    <?php if( $terms ) : ?>
    <?php 
    $found = count($terms); 
    switch($found) {
      case 1:
        $col = 'col-sm-12';
        break;
      case 2:
        $col = 'col-sm-6';
        break;
      case 3:
        $col = 'col-sm-4';
        break;
      default:
        $col = 'col-sm-3';
        break;
    }
    ?>
    <div class="container">
      <div class="wiget-item widget-posts-box">
        <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

        <div class="widget-pb-posts row">
          <?php foreach($terms as $term) : ?>
            <?php 
            $id         = $term->taxonomy.'_'.$term->term_id;
            $thumbnail = get_field('_sg_image', $id); 
            ?>
            <div class="<?php echo $col; ?> widget-pb-post">
              <div class="widget-pb-post-inner">
                <?php if($thumbnail) : ?>
                <div class="widget-pb-post-image">
                  <a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">
                    <?php if( $style == 'style3' ) : ?><div class="widget-pb-post-image-mask"></div><?php endif; ?>
                    <img src="<?php echo esc_url( $thumbnail['sizes']['post-image'] ); ?>" alt="<?php echo esc_attr( $thumbnail['alt'] ); ?>">
                  </a>
                </div>
                <?php endif; ?>
                <a class="widget-pb-post-title widget-pb-pt-<?php echo esc_html( $style ); ?>" href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">
                  <h4><?php echo esc_html( $term->name ); ?></h4>
                  <?php if( $style == 'style2' ) : ?><i class="fa fa-angle-double-right"></i><?php endif; ?>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if( $btn_all ) : ?>
        <div class="widget-bp-all text-center <?php if( $style != 'style1' ) : ?>widget-bp-all-space<?php endif; ?>">
          <a href="<?php echo esc_url( get_permalink( $btn_all_link ) ); ?>" class="btn <?php if( is_page( _page_template_id('recruitment-main', true) ) ) : ?>btn-primary<?php else : ?>btn-info<?php endif; ?>"><?php echo esc_html( $btn_all_text ); ?></a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  <?php else : ?>

    <?php $posts = RFS_WIDGETS::get_widget_posts( $widget_id, $posts_type, 4, $custom_posts ); ?>
    <?php if( $posts->have_posts() ) : ?>
    <?php 
    $found = $posts->found_posts;
    switch($found) {
      case 1:
        $col = 'col-sm-12';
        break;
      case 2:
        $col = 'col-sm-6';
        break;
      case 3:
        $col = 'col-sm-4';
        break;
      default:
        $col = 'col-sm-3';
        break;
    }
    ?>
    <div class="container">
      <div class="wiget-item widget-posts-box">
        <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

        <div class="widget-pb-posts row">
          <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
            <?php 
            $thumbnail = get_field('_thumbnail'); 
            
            $thumbnailsm    = get_field('_thumbnail_sm'); 
            $image          = $thumbnailsm ? $thumbnailsm : $thumbnail;
            ?>
            <div class="<?php echo $col; ?> widget-pb-post">
              <div class="widget-pb-post-inner">
                <?php if($image) : ?>
                <div class="widget-pb-post-image">
                  <a href="<?php the_permalink(); ?>">
                    <?php if( $style == 'style3' ) : ?><div class="widget-pb-post-image-mask"></div><?php endif; ?>
                    <img src="<?php echo esc_url( $image['sizes']['post-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
                  </a>
                  <?php if( $style == 'style1' ) : ?>
                    <?php echo RFS_WIDGETS::widget_badge( get_the_ID(), $badge_shows ); ?>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
                <a class="widget-pb-post-title widget-pb-pt-<?php echo esc_html( $style ); ?>" href="<?php the_permalink(); ?>">
                  <h4><?php the_title(); ?></h4>
                  <?php if( $style == 'style2' ) : ?><i class="fa fa-angle-double-right"></i><?php endif; ?>
                </a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
        <?php if( $btn_all ) : ?>
        <div class="widget-bp-all text-center <?php if( $style != 'style1' ) : ?>widget-bp-all-space<?php endif; ?>">
          <a href="<?php echo esc_url( get_permalink( $btn_all_link ) ); ?>" class="btn <?php if( is_page( _page_template_id('recruitment-main', true) ) ) : ?>btn-primary<?php else : ?>btn-info<?php endif; ?>"><?php echo esc_html( $btn_all_text ); ?></a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; wp_reset_postdata(); ?>

  <?php endif; ?>

<?php endif; ?>