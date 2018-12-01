<?php
$routes = array(
    array(
        'pattern' => '/',
        'controller' => 'Project\Controllers\Index::indexAction',
        'method' => array('get', 'post', 'put', 'delete', 'options', 'head'),
        'bind' => 'home'
    ),
    array(
        'pattern' => '/category',
        'controller' => 'Project\Controllers\Category::indexAction',
        'method' => array('get', 'post', 'put', 'delete', 'options', 'head'),
        'bind' => 'category'
    ),
);
