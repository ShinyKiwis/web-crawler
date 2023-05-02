<?php 
session_start();
$result = '';
if(isset($_POST['download_list'])) {
    $_SESSION['download_list'] = $_POST['download_list'];
    $result = $_SESSION['download_list'];
    echo json_encode(array('data' => $result));
}else if($_POST['download'] == '1' and !empty($_SESSION['download_list'])) {
    $zip = new ZipArchive();

    // Create a temporary file to store the zip data
    $tmp_file = tempnam(sys_get_temp_dir(), 'zip');

    // Open the temporary file for writing
    $zip->open($tmp_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Add each URL as a new file in the zip archive
    foreach ($_SESSION['download_list'] as $url) {
      $contents = file_get_contents($url);
      $filename = basename($url);
      $zip->addFromString($filename, $contents);
    }

    // Close the zip archive
    $zip->close();

    // Set the appropriate headers for downloading the zip file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="files.zip"');
    header('Content-Length: ' . filesize($tmp_file));

    // Output the contents of the temporary file
    readfile($tmp_file);

    // Delete the temporary file
    unlink($tmp_file);
}
?>