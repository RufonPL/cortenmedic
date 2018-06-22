<?php 
$cats     = get_categories();
$latest = _run_wp_query('post', 'latest', 3, array(), array(), true, 1, array( get_the_ID() ) );
?>

<div class="posts-sidebar post-sidebar">

  <?php if( $latest->have_posts() ) : ?>
  <div class="posts-sidebar-section">
    <?php echo _section_header( pll_trans('Najnowsze wiadomości', true), true); ?>
    <ul class="list-unstyled posts-latest">
      <?php while($latest->have_posts()) : $latest->the_post(); ?>
        <?php 
        $thumbnail      = get_field('_thumbnail'); 
        $thumbnailsm    = get_field('_thumbnail_sm'); 
        $thumbnailxs    = get_field('_thumbnail_xs');
        $thumbnailSmall = $thumbnailxs ? $thumbnailxs : $thumbnailsm;
        $image          = $thumbnailSmall ? $thumbnailSmall : $thumbnail;
        ?>
        <li class="row">
          <a href="<?php the_permalink(); ?>">
            <?php if($image) : ?>
            <div class="posts-latest-image pull-left">
              <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
            </div>
            <?php endif; ?>
            <div class="posts-latest-content">
              <h5><strong><?php the_title(); ?></strong></h5>
            </div>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
  <?php endif; wp_reset_postdata(); ?>


  <div class="posts-sidebar-section">
    <?php echo _section_header( pll_trans('Wyszukiwarka', true), true ); ?>
    <?php get_search_form(); ?>
  </div>

</div>