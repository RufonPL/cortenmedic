<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main and all content after
 *
 * @author Rafał Puczel
 */
?>

</div>

<?php
$menus = array();
for($i=1; $i<=5; $i++) {
  if( _get_footer_menu('footer'.$i) ) {
    $menus[] = $i;
  }
}
$menus_found = count($menus);
?>

<footer>
<div class="container-fluid footer">
  <div class="container">
    <div class="row">
      <?php for($i=1; $i<=$menus_found; $i++) : ?>
      <div class="footer-menu-col <?php echo _footer_menu_cols_class($menus_found); ?>">
        <?php echo _get_footer_menu('footer'.$i); ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <a href="<?php bloginfo('url'); ?>" class="footer-logo">
            <?php echo _theme_logo(); ?>
          </a>
          <p class="copyrights">
            <?php pll_trans('Wszelkie prawa zastrzeżone'); ?> <strong><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></strong>
            <br>
            <?php pll_trans('Copyright'); ?> &copy; <?php echo current_time('Y'); ?>
          </p>
        </div>
        <div class="col-sm-6">
          
        </div>
      </div>
    </div>
  </div>
</div>
</footer>

<span class="rwd-size" id="s1366"></span><span class="rwd-size" id="s1280"></span><span class="rwd-size" id="s1152"></span><span class="rwd-size" id="s1024"></span><span class="rwd-size" id="s992"></span><span class="rwd-size" id="s860"></span><span class="rwd-size" id="s768"></span><span class="rwd-size" id="s640"></span><span class="rwd-size" id="s540"></span><span class="rwd-size" id="s480"></span><span class="rwd-size" id="s360"></span><span class="rwd-size" id="s320"></span>

<?php wp_footer(); ?> 

</div><!-- end body inner -->

</body>
</html>