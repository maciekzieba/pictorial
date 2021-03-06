<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
/*if (!preg_match('/^192\.168\.[0-9]{1,3}\.[0-9]{1,3}$/', @$_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']!=='127.0.0.1') {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. ('.$_SERVER['REMOTE_ADDR'].')');
}*/

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

