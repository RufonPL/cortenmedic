<?php
$posts        = _institutions_list_query();
$list_header  = get_field('_institutions_list_header');
?>
<div class="institutions-list">
  <?php if($list_header) : ?><?php echo _section_header( $list_header ); ?><?php endif; ?>
  <?php if( $posts->have_posts() ) : ?>
  <?php $found = $posts->found_posts ?>
    <div class="row institutions-row">
      <?php $i=1; while($posts->have_posts()) : $posts->the_post(); ?>
        <?php  
        $image    = get_field('_thumbnail');
        $cities   = wp_get_post_terms( get_the_ID(), 'institution-city' );
        $city     = $cities ? $cities[0]->name : '';
        $contacts = get_field('_institution_contacts');
        ?>
        <div class="col-sm-6 institution-item">
          <div class="institution-item-left col-sm-6">
            <?php if($image) : ?>
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url( $image['sizes']['post-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
            </a>
            <?php endif; ?>
          </div>
          <div class="institution-item-right col-sm-6">
            <h4><a href="<?php the_permalink(); ?>"><?php echo esc_html( $city ); ?><br><?php the_title(); ?></a></h4>
            <?php echo _institution_contacts($contacts); ?>
            <a class="institution-item-link" href="<?php the_permalink(); ?>"><?php pll_trans('Więcej'); ?> <i class="fa fa-angle-double-right"></i></a>
          </div>
        </div>
      <?php if( $i%2 == 0 && $i!=$found ) : ?>
      </div>
      <div class="row institutions-row">
      <?php endif; ?>
      <?php $i++; endwhile; ?>
    </div>
  <?php else : ?>
  <p><?php pll_trans('Nie znaleziono placówek'); ?></p>
  <?php endif; wp_reset_postdata(); ?>
</div>