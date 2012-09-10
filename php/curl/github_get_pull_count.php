<?php

// Get count of total number of pulls for a give githib repo
// E.g: https://api.github.com/repos/visionmedia/express
// total pulls = open pulls + closed pulls
// open pulls is got from: https://api.github.com/repos/visionmedia/express/pulls
// closed pull are got from: https://api.github.com/repos/visionmedia/express/pulls?state=closed 
// this gets only the first 30 items 
// more closed pulls can be got by paginating https://api.github.com/repos/visionmedia/express/pulls?state=closed&page=2 and parsing the response headers. 
// http://developer.github.com/v3/#pagination
// Requests that return multiple items will be paginated to 30 items by default
// It is important to follow these Link header values instead of constructing your own URLs. 
// Link: <https://api.github.com/user/repos?page=3&per_page=100>; rel="next",
//  <https://api.github.com/user/repos?page=50&per_page=100>; rel="last"

// I am doing a crude extraction of the last page no. and multiplying it by no. of pulls per page i.e 30 and adding the coutn of pulls found in the last page. 

$githubuser = "visionmedia";
$githubrepo = "express";
$githubAPIbase = "https://api.github.com";
$githubAPIpath = "/repos/" . $githubuser . "/" . $githubrepo . "/";
$githubAPIaction = "pulls";
$baseurl = $githubAPIbase . $githubAPIpath . $githubAPIaction;
$githubperpagepullcount = 30;

function get_response_header($url){
	$url = cleanup_url($url);
	$ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1" ); // Chrome 22 UA
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );  // required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt( $ch, CURLOPT_HEADER, 1 );  // required to get headers in response
    $response = curl_exec( $ch );
    if (strpos($response, "\r\n\r\n") > 0)  // has header
        list($header, $body) = explode("\r\n\r\n", $response);
    else
        $body = $response;
    curl_close ( $ch );
    return $header;
}

function get_closed_pulls_last_page_no($url){
	$header = get_response_header($url);
	$lastLinkText = '&state=closed>; rel="last"';
	$partsOfLinkText = explode($lastLinkText,$header);
	$lastPageNo = substr($partsOfLinkText[0], -1);
	return $lastPageNo;
}

function get_page_pull_count($url){
	$url = cleanup_url($url);
	$ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1" ); // Chrome 22 UA
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );  // required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt( $ch, CURLOPT_HEADER, false );  
    $response = curl_exec( $ch );
    curl_close ( $ch );
	$data = json_decode($response);
	if (isset($data))
		return count($data);
	else
		return -1;
}

function cleanup_url($url){
	return str_replace( "&amp;", "&", urldecode(trim($url)) );
}

function get_closed_pull_count($baseurl, $githubperpagepullcount){
	$closedstate="closed";
	$page=2;
	$ClosedPullsAPIURL = $baseurl . "?state=" . $closedstate . "&page=" . $page;
	$lastPageNo = get_closed_pulls_last_page_no($ClosedPullsAPIURL);
	$lastPageNo = (integer) $lastPageNo;
	$noOfPages = $lastPageNo - 1;
	$ClosedPullsLastPageURL = $baseurl . "?state=" . $closedstate . "&page=" . $lastPageNo;
	$lastPagePullCount = get_page_pull_count($ClosedPullsLastPageURL);
	$paginatedpullscount = $noOfPages * $githubperpagepullcount;
	$totalClosedPullCount = $paginatedpullscount + $lastPagePullCount;
	return $totalClosedPullCount;
}

function get_open_pull_count($baseurl){
	$openstate = "open";
	$OpenPullsAPIURL = $baseurl . "?state=" . $openstate;
	return get_page_pull_count($OpenPullsAPIURL);
}

$totalpullcount = get_open_pull_count($baseurl) + get_closed_pull_count($baseurl, $githubperpagepullcount);

var_dump($totalpullcount);



?>
