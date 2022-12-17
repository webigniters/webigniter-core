<?php

namespace Home\Config;

use Config\Services;
use Webigniter\Controllers\Authentication;
use Webigniter\Controllers\Categories;
use Webigniter\Controllers\Content;
use Webigniter\Controllers\Dashboard;
use Webigniter\Controllers\FrontendController;
use Webigniter\Models\ContentModel;

$routes = Services::routes();


/* FRONTEND ROUTES */
$contentModel = new ContentModel();

$allContent = $contentModel->findAll();

foreach($allContent as $content)
{
    $routes->match(['get', 'post'], '/'.$content->getFullUrl(), [FrontendController::class, 'index/'.$content->getId()]);
}


/* CMS ROUTES */

$routes->get('/cms/', [Dashboard::class, 'index']);
$routes->get('/cms/login', [Authentication::class, 'login']);

$routes->get('/cms/categories', [Categories::class, 'list']);
$routes->match(['get', 'post'], '/cms/categories/add/', [Categories::class, 'add']);
$routes->match(['get', 'post'], '/cms/categories/add/(:num)', [Categories::class, 'add']);
$routes->match(['get', 'post'], '/cms/categories/(:num)/edit', [Categories::class, 'edit']);
$routes->get('/cms/category/(:num)', [Categories::class, 'detail']);

$routes->match(['get', 'post'], '/cms/content/add/(:num)', [Content::class, 'add']);
$routes->match(['get', 'post'], '/cms/content/(:num)', [Content::class, 'edit']);
$routes->match(['get', 'post'], '/cms/content/(:num)/add-element/(:num)', [Content::class, 'addElement']);