<?php 
session_start();
$result = '';
if(isset($_POST['download_list'])) {
    $_SESSION['download_list'] = $_POST['download_list'];
    $result = $_SESSION['download_list'];
}else if($_POST['download'] == '1' and !empty($_SESSION['download_list'])) {
    $zipname = 'resources.zip';
    $zip = new ZipArchive();
    $zip->open($zipname, ZipArchive::CREATE);

    foreach ($_SESSION['download_list'] as $url) {
    $filename = basename($url);
    $content = file_get_contents($url);
    $zip->addFromString($filename, $content);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zipname);
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);
    $result = 'downloading';
}
echo json_encode(array('data' => $result))
?>