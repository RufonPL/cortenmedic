<?php
/**
 * The Template for displaying all single posts.
 *
 * @author Rafał Puczel
 */
get_header(); ?>

<?php  
$image            = get_field('_doctor_image');
$title            = get_field('_doctor_title');
$name             = get_the_title();
$specializacions  = doctor_specializations( get_the_ID() );
$content          = get_field('_doctor_content');
?>

<main>
  <div class="container-fluid page-container doctor-single">
    <div class="container">
      <div class="row doctor-content">
        <div class="col-md-6 col-sm-5 doctor-single-left">
          <?php if($image) : ?>
          <img class="doctor-image" src="<?php echo esc_url($image['sizes']['doctor-image-lg']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
          <?php endif; ?>
        </div>
        <div class="col-md-6 col-sm-7 doctor-single-right">
          <div class="doctor-top">
            <h1 class="h3"><strong><?php if( $title ) : ?><?php echo esc_html( $title ); ?> <?php endif; ?><?php echo esc_html( $name ); ?></strong></h1>
            <?php if( $specializacions ) : ?><p class="doctor-specialization"><?php echo esc_html( $specializacions ); ?></p><?php endif; ?>
          </div>
          <div class="doctor-btm">
            <?php if( $content ) : ?>
            <div class="doctor-text">
              <?php foreach($content as $group) : ?>
                <?php  
                $header = $group['_header'];
                $text   = $group['_text'];
                ?>
                <div class="doctor-text-group">
                  <?php if( $header ) : ?><p class="doctor-text-header"><strong><?php echo $header; ?></strong></p><?php endif; ?>
                  <?php if( !_empty_content( $text ) ) : ?>
                    <?php echo wp_kses( _p2br( $text ), array( 'br' => array(), 'strong' => array(), 'ul' => array(), 'li' => array() ) ); ?>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
