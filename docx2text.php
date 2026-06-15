<?php
// Recibe un .docx por POST y devuelve el texto plano
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    http_response_code(400);
    die('Enviar archivo como "file"');
}
$tmp = $_FILES['file']['tmp_name'];
$zip = new ZipArchive;
if ($zip->open($tmp) !== true) {
    http_response_code(422);
    die('No es un docx valido');
}
$xml = $zip->getFromName('word/document.xml');
$zip->close();
$xml = str_replace('</w:p>', "\n", $xml);
$texto = strip_tags($xml);
header('Content-Type: text/plain; charset=utf-8');
echo $texto;
