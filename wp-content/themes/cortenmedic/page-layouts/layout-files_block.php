<?php 
$header = get_sub_field('_header');
$files  = get_sub_field('_files');
?>
<?php if($files) : ?>
<?php $count = count( $files ); ?>
<div class="page-block page-block-files">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>
    
    <ul class="page-block-files-list row list-unstyled">
    <?php foreach($files as $file) : ?>
      <?php  
      $name = $file['_name'];
      $file = $file['_file'];
      
      if( $file ) {
        $filename = $name ? $name : $file['filename'];
      }
      ?>
      <?php if( $file ) : ?>
      <li><i class="fa fa-download"></i><a target="_blank" href="<?php echo esc_url( $file['url'] ); ?>"><?php echo esc_html( $filename ); ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>