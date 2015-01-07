<?php

define('GRIDFIELDPAGES_DIR',basename(dirname(__FILE__)));

// add an arrow overlay to page icons indicating they're children of the GridHolder
LeftAndMain::require_css(GRIDFIELDPAGES_DIR.'/css/gridfieldpages.css');

// Add this line to _config to use provide your own gridfield implementaion on a subclass
//GridFieldPageHolder::$add_default_gridfield = false;