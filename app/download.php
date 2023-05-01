<?php 
session_start();
$result = '';
if(isset($_POST['download_list'])) {
    $_SESSION['download_list'] = $_POST['download_list'];
    $result = $_SESSION['download_list'];
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

    header('Content-disposition: attachment; filename="data.zip"');
    header('Content-type: application/zip');
    header('Content-Length: ' . filesize($tmp_file));
    readfile($tmp_file);
    unlink($tmp_file);
}
echo json_encode(array('data' => $result));
?>