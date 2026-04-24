<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'functions.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$json = file_get_contents('php://input');
$data = json_decode($json);

$contenido ="
<style>
  body {
    width: 100%;
    margin: 0;
    padding: 0;
    font-size: 10pt; /* Los puntos son más precisos para impresión que los px */
  }
  
  h1 {
    font-size: 10pt;
  }
</style>
".$data->contrato;

// https://pub-546a2d9d368f4b87a457962de1d77955.r2.dev/1001/products_images/thumbnails/file_69eae008ab6b9.avif

$token = $data->token;

$options = new Options();
    $options->set('isRemoteEnabled', true); // Vital para cargar el LOGO desde la URL
    $options->set('isHtml5ParserEnabled', true);    
    $options->set('isPhpEnabled', true);
    $options->set('chroot', __DIR__);
    $options->set('defaultFont', 'Helvetica');
    $options->set('defaultDpi', 200); //

$dompdf = new Dompdf($options);

$dompdf->loadHtml($contenido);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfOutput = $dompdf->output();
file_put_contents($token.".pdf", $pdfOutput);

$data = json_encode([
    "UUID" => $token,
    "PDF" => base64_encode($pdfOutput)
]);

$api_url = URL_API."sendbook";
$data = json_decode(API($jwt,$api_url,$data,'POST'), true);  

http_response_code(200);
echo json_encode(array(
    "data" => 'Ok',
    "document" => $token.".pdf",
    "UUID" => $token
));

?>