<?php

include_once 'Request.php';
include_once 'Router.php';

$router = new Router(new Request);

/**
 * Load view/home.php file 
 */
$router->get('/', function($request) {
  	return $request->view('home');
});

/**
 * Just print simple profile
 */
$router->get('/profile', function($request) {
  	return <<<HTML
  		<h1>Profile</h1>
HTML;
});

/**
 * Return json encode response of post data
 */
$router->post('/data', function($request) {
  return json_encode($request->getBody());
});
