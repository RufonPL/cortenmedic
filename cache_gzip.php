<?php  
$file=$_GET['file'];
 
  if (!is_file($file.'.gz') or filemtime($file.'.gz') < filemtime($file)) {
    copy($file, 'compress.zlib://'.$file.'.gz');
  }
  if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
    header("cache-control: must-revalidate");
    if (strrchr($file, '.') == '.css') {
     header('Content-type:text/css');
    } elseif (strrchr($file, '.') == '.js') {
     header('Content-type:text/javascript');
    }
    header('Content-Encoding: gzip');
    header('Vary: Accept-Encoding');
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + 2592000) . " GMT";
    header($expire);
    readfile($file.'.gz');
  } else {
    readfile($file);
  }
?>