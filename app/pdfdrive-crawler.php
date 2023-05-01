<?php

require 'vendor/autoload.php';

function get_data() {
    $BASE_URL = "https://www.pdfdrive.com";
    $http_client = new \GuzzleHttp\Client();

    $url_queue = [];
    array_push($url_queue, $BASE_URL);

    $visited_url = [];
    $download_urls = [];

    libxml_use_internal_errors(true);
    $digCount = 200;
    $count = 1;

    while (!empty($url_queue)) {
        if ($count > $digCount) {
            break;
        }
        echo  "<div>count: ".$count ."</div>";

        $count += 1;
        $current_url = array_shift($url_queue);

        echo  "<div>Current Url: ".$current_url ."</div>";

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
                        $download_btn = $respXpath->query("//a[count(*)=1 and self::i and @type='button' and @href]")->item(0);
                        // $download_urls[] = $download_btn->getAttribute("href");

                        echo "Download ".$download_btn;
                    }
                }
            }
        }

        $links = $xpath->query("//a[@href]");

        var_dump($links);

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
}

get_data()

?>