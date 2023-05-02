<?php 
session_start();
$result = '';
if(isset($_POST['download_list'])) {
    $_SESSION['download_list'] = $_POST['download_list'];
    $result = $_SESSION['download_list'];
    echo json_encode(array('data' => $result));
}else if($_POST['download'] == '1' and !empty($_SESSION['download_list'])) {
    $zip = new ZipArchive();
    $tmp_file = tempnam('/tmp', 'data.zip');
    $zip->open($tmp_file, ZipArchive::OVERWRITE);
    foreach ($_SESSION['download_list'] as $url) {
        $filename = basename($url);
        $content = file_get_contents($url);
        $zip->addFromString($filename, $content);
    }
    
    $zip->close();
    $result = $tmp_file;

    // header('Content-Disposition: attachment; filename="data.zip"');
    // header('Content-Type: application/zip');
    // // header('Content-Length: ' . filesize($tmp_file));
    // // header('Access-Control-Allow-Origin: *');
    // // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    // // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    // header('Content-Transfer-Encoding: binary');
    // header('Pragma: public');
    // header('Content-Description: File Transfer');
    // readfile($tmp_file);
    // unlink($tmp_file);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($tmp_file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($tmp_file));
    readfile($tmp_file);
    exit;
}
?>