<?php

/**
 * ProcessWire Bundle Files Template File
 * 
 * This template file handles the output of the concatenated CSS or JS files.
 * This file should be placed in /site/templates/ if not already copied
 * by the module installer.
 *
 * Copyright (C) 2014 by Martijn Geerts
 *
 * @package Bundle Files Minify 
 *
 */


// load the controller, this handles the routing of the minify actions
require($config->paths->BundleFilesMinify . '/BundleFilesMinifyController.php');

// should be js or css
$ext = $input->urlSegment1;

// throw 404 exception
if($ext !== 'js' && $ext !== 'css') {
	throw new PageNotFoundException();
}



/**
 * Escape if there are no files in the FilenameArray
 *
 */

// get contains all file path parts of the files to bundle
$scripts = $input->get;

if(!count($scripts)) {

	if($ext === 'js') {
		header('content-type: application/javascript; charset: utf-8');
	} else {
		header('Content-type: text/css');
	}

	exit;
}



/**
 * Build output
 *
 */

// prevent notice
$output = '';
// one year in seconds
$year = 31449600;
// it's cached until we decide it's not
$is_cached = true;
// collect all modified time in array
$modified = array(); // for storing the minimum time.
// instanciate module
$file_cache = $modules->get("MarkupCache");

foreach($scripts as $script) { 

	$file = $config->paths->templates . $script . "." . $ext;

	// Someone messed with the get url.
	if(!is_file($file)) throw new PageNotFoundException();

	$name  = $ext . "-" . str_replace("/", ".", $script);
	$cache = $config->paths->cache . "MarkupCache/$name/$name.cache";
	$time  = is_file($cache) && filemtime($file) > filemtime($cache) ? 0 : $year;

	// if edited file is modified after the cache is created, we should not serve the cache.
	if(!$time) {
		$is_cached = false;
	} else {
		$modified[] = filemtime($file);
	}

	if(!$data = $file_cache->get($name, $time)) {
		$minify = new BundleFilesMinifyController;
		$minify->$ext(file_get_contents($file));
		$data = $minify->min();
	    $file_cache->save($data);
	}

	$output .= $data;
}



/**
 * Cache controll
 * 
 */

$etag = basename($input->urlSegment2); // http://nl3.php.net/basename
$modified = min($modified);
$etagHeader = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;

// let me be a javascript or stylesheet file
if($ext === 'js') {
	header('content-type: application/javascript; charset: utf-8');
} else {
	header('Content-type: text/css');
}

// set last-modified header
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
// set etag-header
header('Etag: $etag');
// make sure caching is turned on
header('Cache-Control: public');
// one year to live
header('Cache-Control: maxage=' . $year );
// one year to live
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $year) . ' GMT');

if($is_cached && (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $modified || $etagHeader == $etag)) {
	header('HTTP/1.1 304 Not Modified');
	exit;
}

echo $output;