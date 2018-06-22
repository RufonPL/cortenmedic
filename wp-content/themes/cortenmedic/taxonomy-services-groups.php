<?php
/**
 * The template for displaying services groups single page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php 
$service_group  = get_queried_object();
$id             = $service_group->taxonomy.'_'.$service_group->term_id;
$name           = $service_group->name;
$term_id        = $service_group->term_id;
?>

<?php  
require_once get_template_directory().'/vendor/autoload.php';
use GuzzleHttp\Client;
//$client = new GuzzleHttp\Client();
use GuzzleHttp\Exception\ConnectException;

//try {

//   $client = new Client([
//     'base_uri'  => 'https://192.168.2.240/OptimedTest/api',
//     'timeout'   => 0,
//   ]);

//   // $response = $client->request('GET', 'https://jsonplaceholder.typicode.com', [
//   //   'query' => ['posts' => '1']
//   // ]);

//   $response = $client->get('eportal/jednostki/pobierz?AuthToken=ce81dc42123527e067ba7ec227f89705');

//   $data = $response->getBody();
//   $data = json_decode($data, true);

//   echo '<pre style="margin-left:200px">';
//   print_r($data);
//   echo '</pre>';

// } catch( ConnectException $ex) {
//   echo '<pre style="margin-left:200px">';
//   print_r($ex->getMessage());
//   echo '</pre>';
// }

// try {

//   $client = new Client([
//     'base_uri'  => 'http://192.168.2.240:8080/OptimedTest/api/',
//     'timeout'   => 0,
//   ]);
  
//   $response = $client->get('heartbeat');
//   $data = $response->getBody();
//   $data = json_decode($data, true);

// } catch( ConnectException $ex ) {
//   echo '<pre style="margin-left:200px">';
//   print_r($ex->getMessage());
//   echo '</pre>';
//   switch ( $ex->getMessage() ) {
//       case '7': // to be verified
//           // handle your exception in the way you want,
//           // maybe with a graceful fallback
//           break;
//   }
// }
?>

<main>
  <div class="container-fluid page-container offer-single-page">

    <?php _get_template_part( 'single-image', 'offer', array( 'id' => $id, 'name' => $name, 'term_id' => $term_id, 'show_link' => false ) ); ?>

    <div class="offer-single-content">
      <div class="container">
          <?php _get_template_part( 'single-search', 'offer', array( 'term_id' => $term_id ) ); ?>

          <?php _get_template_part( 'single-content', 'offer', array( 'term_id' => $term_id ) ); ?>
      </div>
    </div>

  </div>
</main>

<?php get_footer(); ?>
