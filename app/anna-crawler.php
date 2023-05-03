<?php
function getAnnaData($keyword, $ext) {
  $http_client = new \GuzzleHttp\Client();
  $response = $http_client->get("https://annas-archive.org/search?".$keyword);
  $urls = [];
  libxml_use_internal_errors(true);
  
  $html_string = (string)$response->getBody();
  $depth = 20;
  if(preg_match_all('/href="\/md5\/(.?)*" /', $html_string, $file_links)){
    // print($file_links[0][0]);
    foreach($file_links[0] as $file_link){
      if($depth == 0) {
        break;
      }else {
        $depth-=1;
      }
      $file_link = str_replace("href=","",$file_link);
      $file_link = str_replace("\"","",$file_link);
      $file_link = "https://annas-archive.org" . $file_link;
  
      $response = $http_client->get($file_link);
      $html_string = (string)$response->getBody();
      $doc = new DOMDocument();
      $doc->loadHTML($html_string);
      $xpath = new DOMXPath($doc);
      $url = $xpath->evaluate('//a[@class="js-download-link"]/@href')[2]->textContent;
      if(str_contains($url, '.'.$ext)){
          $urls[] = $url;
      }
    }
  }
  return $urls;
}
?>