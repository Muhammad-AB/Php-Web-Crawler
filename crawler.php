<?php

set_time_limit(300);

// Function to crawl a website and extract html
function extractHtmlContent($url)
{
    $html = file_get_contents($url); // Get the HTML content of the page
    return $html;
}

// Function to extract urls from html
function extractUrls($html)
{
    // Use a regular expression to find all the links in the page
    preg_match_all('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i', $html, $matches);

    $links = $matches[1]; // Extracted links
    // echo "The Urls on the page: $url : are<br>";
    // echo "<pre>";
    // print_r($links);
    // echo "</pre>";
    return $links;
}

// Function to deal with relative Urls
function makeUrlFromRelativeUrl($startUrl, $rootUrl)
{
    // If the URL is relative, append it to the base URL
    if (strpos($startUrl, '/') === 0) {
        $startUrl = $rootUrl . $startUrl;
    }
    return $startUrl;
}

// Function to filter URLs based on the root URL, handling cases where the host is not set
function isSameDomain($url, $rootUrl)
{
    $parsedUrl = parse_url($url);
    $parsedRootUrl = parse_url($rootUrl);

    // Check if the host is set in the parsed URL
    $urlHost = isset($parsedUrl['host']) ? $parsedUrl['host'] : "";
    $rootUrlHost = isset($parsedRootUrl['host']) ? $parsedRootUrl['host'] : '';

    return $urlHost === $rootUrlHost;
}


// Function to start crawling from a given URL with filtering and content saving
function startCrawling($startUrl, $rootUrl, $depth)
{
    static $visited = array(); // To keep track of visited URLs

    // If the URL is relative, append it to the base URL
    $startUrl = makeUrlFromRelativeUrl($startUrl, $rootUrl);

    if ($depth === 0 || in_array($startUrl, $visited) || !isSameDomain($startUrl, $rootUrl)) {
        return;
    }

    $visited[] = $startUrl;
    echo "Crawling: $startUrl<br>";
    $html = extractHtmlContent($startUrl);
    $links = extractUrls($html);

    foreach ($links as $link) {
        startCrawling($link, $startUrl, $depth - 1);
    }

    // echo "The Visited Urls are:<br>";
    // echo "<pre>";
    // print_r($visited);
    // echo "</pre>";
}

$startUrl = "https://www.forbes.com/";
// $rootUrl = "https://www.aljazeera.com/";
$depth = 2;

startCrawling($startUrl, $startUrl, $depth);
