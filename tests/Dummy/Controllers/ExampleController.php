<?php

namespace Dummy\Controllers;

use Koine\Mvc\Controller;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ExampleController extends Controller
{
    public function userHello()
    {
        $headers = $this->getResponse()->getHeaders();
        $this->getResponse()->setStatusCode(302);
        $headers['Location'] = '/';
    }
}
