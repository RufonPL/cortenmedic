<?php
/**
 * The template for displaying the header.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @author Rafał Puczel
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<?php if( $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) : ?>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-106831968-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag()
{dataLayer.push(arguments)}
;
gtag('js', new Date());
gtag('config', 'UA-106831968-1');
gtag('config', 'UA-114655257-1');
</script>
<?php endif; ?>
</head>

<?php
$menu_location  = 'primary';
$slogan         = get_bloginfo('description');
$invert         = false;
$slogan_size    = 'h3';

//  if( _is_career_page() ) {
//   $menu_location = 'career';
//   $slogan        = get_option('career_blog_description');
//   $invert        = true;
//   $slogan_size   = 'h4';
//  } 
if( _is_business_page() ) {
  $menu_location = 'business';
  $slogan        = get_option('career_blog_description');
  $invert        = true;
  $slogan_size   = 'h4';
}

if( _is_shop_page() ) {
  $menu_location = 'shop';
  $slogan        = get_option('career_blog_description');
  $invert        = true;
  $slogan_size   = 'h4';
}

$menu_args = array(	
	'theme_location'	=> $menu_location, 
	'container_class'	=> 'navbar-collapse collapse', 
	'menu_class'			=> 'nav navbar-nav',
  'after'           => '<span></span>',
	'fallback_cb'			=> '',
	'menu_id'					=> 'main-menu',
	'walker'					=> new WP_Bootstrap_Navwalker()
);
?>

<body <?php body_class(); ?>>

	<?php echo _page_loader(); ?>

	<div class="body-inner">

    <?php echo _header_links(4, 'side') ?>
		
    <header>
      <nav class="navbar navbar-default container-fluid">

        <div class="navbar-top">
          <div class="container">
            <div class="row text-right">
              <img class="eu-image" src="<?php echo esc_url( get_template_directory_uri().'/dist/assets/images/eu.png' ); ?>" alt="eu">
              <?php echo social_links(); ?>
              <?php echo font_resizer(); ?>
              <?php echo show_flags('', 1); ?>
              <button type="button" class="wahout aicon_link" accesskey="z" aria-label="Accessibility Helper sidebar" title="Accessibility Helper sidebar">
                <img src="/wp-content/plugins/wp-accessibility-helper/assets/images/accessibility-48.jpg" alt="Accessibility" class="aicon_image">
              </button>
            </div>
          </div>
        </div>

        <div class="navbar-middle">
          <div class="container">
            <div class="row">
              <div class="navbar-header">
                <div class="row">
                  <div class="col-sm-6 col-xs-4">
                    <div class="row">
                      <div class="col-sm-5">
                        <a href="<?php echo esc_url( get_bloginfo( 'url' ) ); ?>" class="navbar-brand">
                          <?php echo _theme_logo(); ?>
                        </a>
                      </div>
                      <div class="col-sm-7 text-center slogan-container">
                        <?php if( $slogan ) : ?>
                        <<?php echo $slogan_size; ?> class="slogan"><?php echo _last_word_highlight( pll_trans($slogan, true), $invert ); ?></<?php echo $slogan_size; ?>>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-8">
                    <?php if( CORTEN_SHOP::is_shop() ) : ?>
                      <?php _get_template_part( 'header-links', 'shop' ); ?>
                    <?php else : ?>
                      <?php echo _header_links(4, 'header'); ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="navbar-menu">
          <div class="container">
            <div class="row">
              <div class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <div class="pull-left">
                MENU
                </div>
                <div class="pull-right">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </div>
              </div>
              <?php wp_nav_menu($menu_args); ?>
            </div>
          </div>
        </div>

      </nav>
    </header>

    <div id="main" class="container-fluid">
      