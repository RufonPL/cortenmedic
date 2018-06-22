<?php if( isset( $_GET['ogroup'] ) && isset( $_GET['oservice'] ) ) : ?>
  <?php 
  $term_id    = isset( $params['term_id'] ) ? absint( $params['term_id'] ) : 0;
  $group_id   = absint( $_GET['ogroup'] ); 
  $service_id = absint( $_GET['oservice'] ); 
  $pricelist_url  = _page_template_id('offer-pricelist') ? get_permalink( _page_template_id('offer-pricelist') ) : '#';
  $pricelists = _service_pricelists($service_id);
  ?>

  <?php if( $term_id == $group_id && get_post_status( $service_id ) == 'publish' ) : ?>
    <?php  
    $header   = get_the_title( $service_id );
    $details  = get_field('_service_detalis', $service_id);
    ?>
    <div class="single-offer-details">
      <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

      <?php if( $details ) : ?>
      <?php $count = count($details); ?>
      <div class="single-offer-details-content">
        <?php $i=1; foreach($details as $item) : ?>
          <?php  
          $header = $item['_name'];
          $text   = $item['_text'];
          ?>
          <?php if( $i == 3 ) : ?>
            <div class="sod-content-slide">
          <?php endif; ?>
            <div class="sod-item">
              <h4 class="sod-item-header"><?php echo esc_html( $header ); ?></h4>
              <?php if( $text ) : ?><?php echo ( $text ); ?><?php endif; ?>
            </div>
        <?php $i++; endforeach; ?>
          <?php if( $count > 2 ) : ?>
            </div>
          <?php endif; ?>
          <div class="sod-actions">
            <?php if( $count > 2 ) : ?><span class="btn btn-info btn-wide sod-slide-toggle"><?php pll_trans('Rozwiń'); ?></span><?php endif; ?>
            <?php if( $pricelists ) : ?>
              <?php foreach($pricelists as $city) : ?>
                <a href="<?php echo esc_url( $pricelist_url.'?pcity='.absint( $city['id'] ).'&pfilter='.$group_id.'-'.$service_id ); ?>" class="btn btn-primary btn-wide"><?php pll_trans('Cennik - '.esc_html( $city['name'] )); ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
      </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

