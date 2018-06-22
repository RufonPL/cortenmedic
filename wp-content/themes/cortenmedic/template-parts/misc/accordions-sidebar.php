<?php 
$blog_id  = get_option('page_for_posts');
$cats     = get_categories();
$places   = get_terms( array('taxonomy' => 'institution-city') );

$accordions = get_field('_accordion_page_accordions');
?>

<div class="posts-sidebar">

  <?php if( $accordions ) : ?>
  <div class="posts-sidebar-section accordions-sidebar-list">
    <?php echo _section_header( pll_trans('Kategorie', true), true); ?>
    <ul class="list-unstyled posts-categories">
      <?php $i=1; foreach($accordions as $accordion) : ?>
      <?php $header = get_field('_widget_header', $accordion); ?>
      <?php if( !_empty_content( $header ) ) : ?>
      <li><a href="#accordion<?php echo $i; ?>"><?php echo strip_tags( $header ); ?></a></li>
      <?php endif; ?>
      <?php $i++; endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

</div>