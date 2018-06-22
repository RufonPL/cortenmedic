<?php  
$data = isset( $params['data'] ) ? $params['data'] : array();
?>
<?php if( $data && get_search_form_param('pcity') ) : ?>
<div class="pricelist-table-container">

  <div class="panel-group" id="groups">
    <?php foreach($data as $group_id => $group_data) : ?>
      <?php $services = $group_data['services']; ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#groups" href="#group-<?php echo absint( $group_id ); ?>">
              <?php echo esc_html( $group_data['name'] ); ?>
            </a>
          </h4>
        </div>
        <div id="group-<?php echo absint( $group_id ); ?>" class="panel-collapse collapse">
          <div class="panel-body">
            <?php if( $services ) : ?>
            <div class="panel-group" id="group-<?php echo absint( $group_id ); ?>-services">
              <?php foreach($services as $service) : ?>
                <?php $pricelist = $service['pricelist']; ?>
                <?php //if( $pricelist ) : ?>
                <div class="panel panel-default inner-panel">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#group-<?php echo absint( $group_id ); ?>-services" href="#group-<?php echo absint( $group_id ); ?>-service-<?php echo absint( $service['id'] ); ?>">
                          <?php echo esc_html( $service['name'] ); ?>
                        </a>
                      </h4>
                    </div>
                    <div id="group-<?php echo absint( $group_id ); ?>-service-<?php echo absint( $service['id'] ); ?>" class="panel-collapse collapse">
                      <div class="panel-body">
                        <?php if( $pricelist ) : ?>
                        <div class="pricelist-table">
                          <?php foreach($pricelist as $item) : ?>
                            <?php 
                            $name  = $item['name']; 
                            $price = $item['price']; 
                            ?>
                            <div class="pricelist-table-row row">
                              <div class="pricelist-table-col col-xs-8 pricelist-item-name">
                                <p><?php echo esc_html( $name ); ?></p>
                              </div>
                              <div class="pricelist-table-col col-xs-4 text-right pricelist-item-price">
                                <?php if( $price ) : ?><p><?php echo esc_html( $price ); ?><?php pll_trans('zł'); ?></p><?php endif; ?>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>
                </div>
                <?php //endif; ?>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>

</div>
<?php endif; ?>