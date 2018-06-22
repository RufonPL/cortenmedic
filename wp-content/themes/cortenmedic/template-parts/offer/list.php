<?php  
$offers   = get_terms( array(
  'taxonomy'    => 'services-groups',
  'post_types'  => 'service',
  'hide_empty'  => true
) );
?>

<?php if( $offers ) : ?>
<div class="offers-list">
  <?php foreach($offers as $offer) : ?>
    <?php  
    $id       = $offer->taxonomy.'_'.$offer->term_id;
    $name     = $offer->name;
    $term_id  = $offer->term_id;
    ?>
    
    <?php _get_template_part( 'single-image', 'offer', array( 'id' => $id, 'name' => $name, 'term_id' => $term_id, 'show_link' => true ) ); ?>

  <?php endforeach; ?>
</div>
<?php endif; ?>