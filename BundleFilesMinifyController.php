<?php

/**
 * ProcessWire Bundle Files Minify Controller
 * 
 * This file wil switch the compression library for Javascript or Stylesheets
 *
 * Copyright (C) 2014 by Martijn Geerts
 *
 * ProcessWire 2.x 
 * Copyright (C) 2010 by Ryan Cramer 
 * Licensed under GNU/GPL v2, see LICENSE.TXT
 * 
 * http://www.processwire.com
 * http://www.ryancramer.com
 *
 * @package Bundle Files Minify
 *
 */

Class BundleFilesMinifyController {


	/**
	 * Load the libraries 
	 *
	 */

	public function __construct() {

		// load the Javascript minify class
		require_once(dirname(__FILE__) . "/libs/JSMin.php");

		// load the Javascript minify class
		require_once(dirname(__FILE__) . "/libs/CSSmin.php");
	}


	/**
	 * $minified, string containing the minified scripts or styles 
	 *
	 */

	protected $minified = '';


	/**
	 * Apply compression for stylesheet string
	 *
	 */

	public function css($string) {
		$styles = new CSSmin;
		$this->minified = $styles->run($string);
	}


	/**
	 * Apply compression for javascript string
	 * 
	 */

	public function js($string) {
		$this->minified = JSMin::minify($string);
	}


	/**
	 * Return the minified string
	 *
	 */

	public function min() {
		return $this->minified;
	}

}