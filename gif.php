<?php
$url = isset($_GET['u']) ? $_GET['u'] : '';
if( empty($url) ) {
  return;
}
$path = parse_url($url, PHP_URL_PATH);
if( strtolower(substr($path, -4)) != '.gif' ) {
  return;
}
header("Content-Type: image/gif");
ob_clean();
flush();
readfile($url);
exit();
