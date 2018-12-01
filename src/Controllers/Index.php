<?php

namespace Project\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class Index {

    public function indexAction(Request $request, Application $app) {
        return $app['twig']->render('category/index.html.twig', array());
    }
}
