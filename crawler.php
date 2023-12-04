<?php

set_time_limit(300);

// Function to crawl a website and extract html
function extractHtmlContent($url)
{
    $html = file_get_contents($url);
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
        $startUrl = rtrim($rootUrl, '/') . $startUrl;
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

// Function to fetch the dissallowed URLs from robots.txt
function getDisallowedUrls($baseUrl)
{
    // Fetch the robots.txt content
    $robotsTxtContent = @file_get_contents($baseUrl . '/robots.txt');
    $disallowedUrls = [];

    $lines = explode("\n", $robotsTxtContent);
    foreach ($lines as $line) {

        // Check for "Disallow" directives
        if (strpos(trim($line), 'Disallow:') !== false) {
            // Extract the disallowed path
            $disallowedPath = trim(str_replace('Disallow:', '', $line));

            // Check if the path is an absolute URL
            if (filter_var($disallowedPath, FILTER_VALIDATE_URL)) {
                // Absolute URL, add to the array
                $disallowedUrls[] = $disallowedPath;
            } else {
                // Relative path, append to the base URL
                $disallowedUrls[] = rtrim($baseUrl, '/') . $disallowedPath;
            }
        }
    }
    return $disallowedUrls;
}


// Function to Check if the given URL is in the array of disallowed URLs
function isUrlDisallowed($url, $disallowedUrls)
{
    return in_array($url, $disallowedUrls);
}


function parseHtml($htmlContent)
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlContent);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Directly use item(0) result, which will be null if no title tag is found
    $pageTitle = $dom->getElementsByTagName('title')->item(0);

    // Use a more concise XPath expression for text nodes (excluding comments)
    $textNodes = $xpath->query('//h1|//h2|//p/descendant-or-self::text()');

    // Extract page title
    $extractedTitle = $pageTitle ? $pageTitle->nodeValue : null;

    // Concatenate all text data into a single string
    $allTextData = '';
    foreach ($textNodes as $node) {
        $allTextData .= ' ' . $node->nodeValue;
    }

    // Return page title and all text data
    return [
        'pageTitle' => $extractedTitle,
        'allTextData' => $allTextData,
    ];
}


// Function to save the content of a web page to a file with formatted filename
function savePageContent($url, $title, $textContent)
{
    // Format the base URL for the filename
    // $filename = basename($url);
    $formattedBaseUrl = preg_replace('/[^a-zA-Z0-9]/', '_', $url);
    $filename = $formattedBaseUrl . '.txt';

    // Save the content and the URL to the file
    file_put_contents("scraped-files/" . $filename, "URL: $url\nTITLE: $title\n".trim($textContent));
    echo "Saved: $filename<br><br>";
}


// Function to start crawling from a given URL with filtering and content saving
function startCrawling($startUrl, $rootUrl, $depth, $disallowedUrls)
{
    static $visited = array(); // To keep track of visited URLs

    // If the URL is relative, append it to the base URL
    $startUrl = makeUrlFromRelativeUrl($startUrl, $rootUrl);

    if ($depth === 0 || in_array($startUrl, $visited) || !isSameDomain($startUrl, $rootUrl) || isUrlDisallowed($startUrl, $disallowedUrls)) {
        return;
    }


    $visited[] = $startUrl;
    echo "Crawling: $startUrl<br>";

    // Scraping html from the Url
    $html = extractHtmlContent($startUrl);
    // Extracting Urls from the scraped html
    $links = extractUrls($html);

    $content = parseHtml($html);
    $title = $content["pageTitle"];
    $textContent = $content["allTextData"];
    echo "Title: $title<br>Content:<br>$textContent<br>";

    // Save the content of the current page
    savePageContent($startUrl, $title, $textContent);

    foreach ($links as $link) {
        startCrawling($link, $startUrl, $depth - 1, $disallowedUrls);
    }
}

$startUrl = "https://fortune.com/";

$disallowedUrls = getDisallowedUrls($startUrl);


foreach ($disallowedUrls as $key => $url) {
    if ($url == $startUrl) {
        unset($disallowedUrls[$key]);
    }
}

echo "The Disallowed Urls are<br>";
echo "<pre>";
print_r($disallowedUrls);
echo "</pre>";
$depth = 2;

startCrawling($startUrl, $startUrl, $depth, $disallowedUrls);
