<?php 
$blog_id  = get_option('page_for_posts');
$cats     = get_categories();
$places   = get_terms( array('taxonomy' => 'institution-city') );
?>

<div class="posts-sidebar">

  <?php if( $cats ) : ?>
  <div class="posts-sidebar-section">
    <?php echo _section_header( pll_trans('Kategorie', true), true); ?>
    <ul class="list-unstyled posts-categories">
      <?php foreach($cats as $cat) : ?>
      <li><a href="<?php echo esc_url( get_permalink( $blog_id ).'?filter=1&pc='.$cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <div class="posts-sidebar-section">
    <?php //echo _section_header( pll_trans('Daty', true), true ); ?>
  </div>

  <?php if( $places ) : ?>
  <div class="posts-sidebar-section">
    <?php echo _section_header( pll_trans('Miejsce', true), true ); ?>
    <select name="pplace" id="pplace" class="selectpicker" data-size="5">
      <option data-hidden="false" value="" class="first-option"><?php pll_trans('Wybierz'); ?></option>
      <?php if( $places ) : ?>
        <?php foreach($places as $place) : ?>
          <option<?php if( isset( $_GET['pp'] ) && absint( $_GET['pp'] ) == absint( $place->term_id ) ) : ?> selected<?php endif; ?> value="<?php echo absint( $place->term_id ); ?>"><?php echo esc_html( $place->name ); ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
  <?php endif; ?>

  <div class="posts-sidebar-section">
    <?php echo _section_header( pll_trans('Wyszukiwarka', true), true ); ?>
    <?php get_search_form(); ?>
  </div>

</div>