<ul>

<?php

set_time_limit(0);

require 'vendor/autoload.php';

// header('Content-Type: application/json; charset=utf-8');
// header("Connection: keep-alive");

$BASE_URL = "https://www.pdfdrive.com";
$http_client = new \GuzzleHttp\Client();

$url_queue = [];
array_push($url_queue, $BASE_URL);

$visited_url = [];

libxml_use_internal_errors(true);
$digCount = 15;
$count = 1;

$download_urls = [];
while (!empty($url_queue)) {
    if ($count > $digCount) {
        break;
    }
    $count += 1;
    $current_url = array_shift($url_queue);

    $visited_url[] = $current_url;

    // if the url is a book url
    if (preg_match('/-e\d+\.html$/', $current_url)) {
        $download_site_url = preg_replace('/-e(\d+)\.html$/', '-d$1.html', $current_url);
        $visited_url[] = $download_site_url;
        array_unshift($url_queue, $download_site_url);
    }
    // handle url
    try {
        $response = $http_client->get($current_url);
    }
    catch (Exception $e) {
        continue;
    }

    $html_string= (string)$response->getBody();

    $doc = new DOMDocument();
    $doc->loadHTML($html_string);
    $xpath = new DOMXPath($doc);

    if (preg_match('/-d\d+\.html$/', $current_url)) {
        $scripts = $xpath->query("//script[string-length(normalize-space(text())) > 0 and not(@src)]");

        foreach ($scripts as $script) {
            $script_content = $script->nodeValue;
            if ($script_content !== null) {
                preg_match('/\\$.get\("\\/ebook\\/broken",\\{id:\'(?P<id>[^\\\']*)\',session:\'(?P<session>[^\\\']*)\',r:\'(?P<r>[^\\\']*)\'\\}\\)/', $script_content, $matches);
                if (!empty($matches)){
                    $check_broken_url = $BASE_URL."/ebook/broken?id=".$matches["id"]."&session=".$matches["session"]."&r=".$matches["r"];
                    try {
                        $response = $http_client->get($check_broken_url);
                    }
                    catch (Exception $e) {continue;}
                    $Resphtml_string = (string)$response->getBody();
                    $respDoc = new DOMDocument();
                    $respDoc->loadHTML($Resphtml_string);
                    $respXpath = new DOMXPath($respDoc);
                    $download_btn = $respXpath->query("//a[@type='button'][1]");

                    if ($download_btn->length > 0) {
                        $download_url =  $download_btn->item(0)->getAttribute("href");
                        echo '<li style="flex">
                            <h3>'.$current_url.'</h3>
                            <div> Download: <a class="pdf_download" download="'.$current_url.'" href="'.$download_url.'"target="blank" >'.$download_url.'</a>
                            </div>
                            </li>';
                    }
                }
            }
        }
    }

    $links = $xpath->query("//a[@href]");

    foreach($links as $link) {
        $url = rtrim($link->getAttribute("href"), "/");
        if (str_starts_with($url, "/")) {
            $url = $BASE_URL . $url;
        }
        if (!in_array($url, $visited_url)
            && !in_array($url, iterator_to_array($url_queue)))
            // && preg_match('/-d\d+\.html$/', $current_url))
        {
            array_push($url_queue, $url);
        }
    }

}

?>
</ul>