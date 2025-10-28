<?php
// Fallback static file handler for images placed inside api/secciones/imagenes
// If a request path starts with /images/ we try:
// 1) api/secciones/imagenes/<path>
// 2) ../public/images/<path>
// If found, return the file with appropriate Content-Type and exit.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
if (strpos($path, '/images/') === 0) {
  $rel = substr($path, strlen('/images/'));
  $candidate1 = __DIR__ . "/secciones/imagenes/" . $rel;
  if (is_file($candidate1)) {
    $mime = function_exists('mime_content_type') ? mime_content_type($candidate1) : null;
    if (!$mime) {
      $ext = pathinfo($candidate1, PATHINFO_EXTENSION);
      $map = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','svg'=>'image/svg+xml','gif'=>'image/gif'];
      $mime = $map[strtolower($ext)] ?? 'application/octet-stream';
    }
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=31536000');
    readfile($candidate1);
    exit;
  }
  $candidate2 = __DIR__ . "/../public/images/" . $rel;
  if (is_file($candidate2)) {
    $mime = function_exists('mime_content_type') ? mime_content_type($candidate2) : null;
    if (!$mime) {
      $ext = pathinfo($candidate2, PATHINFO_EXTENSION);
      $map = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','svg'=>'image/svg+xml','gif'=>'image/gif'];
      $mime = $map[strtolower($ext)] ?? 'application/octet-stream';
    }
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=31536000');
    readfile($candidate2);
    exit;
  }
  // Not found
  header("HTTP/1.1 404 Not Found");
  echo "Not found";
  exit;
}

?>