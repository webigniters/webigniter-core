<?php

namespace Home\Config;

use Config\Services;
use Webigniter\Controllers\Ajax;
use Webigniter\Controllers\Authentication;
use Webigniter\Controllers\Categories;
use Webigniter\Controllers\Content;
use Webigniter\Controllers\Dashboard;
use Webigniter\Controllers\FrontendController;
use Webigniter\Controllers\Navigations;
use Webigniter\Controllers\Views;
use Webigniter\Models\ContentModel;

$routes = Services::routes();

/* FRONTEND ROUTES */
$contentModel = new ContentModel();

$allContent = $contentModel->where('published', 1)->findAll();

foreach($allContent as $content)
{
    $routes->match(['get', 'post'], '/'.$content->getFullUrl(), [FrontendController::class, 'index/'.$content->getId()]);
}


/* CMS ROUTES */

$routes->match(['get', 'post'], '/cms/ajax/(:segment)', [Ajax::class, 'ajax']);

$routes->get('/cms/', [Dashboard::class, 'index']);
$routes->get('/cms/login', [Authentication::class, 'login']);

$routes->get('/cms/categories', [Categories::class, 'list']);
$routes->match(['get', 'post'], '/cms/categories/add/', [Categories::class, 'add']);
$routes->match(['get', 'post'], '/cms/categories/add/(:num)', [Categories::class, 'add']);
$routes->match(['get', 'post'], '/cms/categories/(:num)/edit', [Categories::class, 'edit']);
$routes->get('/cms/categories/(:num)/delete', [Categories::class, 'delete']);
$routes->get('/cms/category/(:num)', [Categories::class, 'detail']);

$routes->match(['get', 'post'], '/cms/content/add/(:num)', [Content::class, 'add']);
$routes->match(['get', 'post'], '/cms/content/(:num)', [Content::class, 'edit']);
$routes->get('/cms/content/(:num)/delete', [Content::class, 'delete']);
$routes->match(['get', 'post'], '/cms/content/(:num)/add-element/(:num)', [Content::class, 'addElement']);
$routes->get('/cms/content/(:num)/delete-element/(:num)', [Content::class, 'deleteElement']);

$routes->get('/cms/views', [Views::class, 'list']);
$routes->match(['get', 'post'], '/cms/views/add/', [Views::class, 'add']);
$routes->match(['get', 'post'], '/cms/views/(:segment)', [Views::class, 'edit']);
$routes->get('/cms/views/(:segment)/delete', [Views::class, 'delete']);

$routes->get('/cms/navigations', [Navigations::class, 'list']);
$routes->match(['get', 'post'], '/cms/navigations/add/', [Navigations::class, 'add']);
$routes->get('/cms/navigations/(:num)/delete', [Navigations::class, 'delete']);
$routes->match(['get', 'post'], '/cms/navigation/(:num)', [Navigations::class, 'edit']);


