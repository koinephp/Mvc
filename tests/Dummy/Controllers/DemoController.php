<?php

namespace Dummy\Controllers;

use Koine\Mvc\Controller;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class DemoController extends Controller
{
    public function beforeAction()
    {
        $this->registerAction(__METHOD__);
    }

    public function test()
    {
        $this->registerAction(__METHOD__);
    }

    public function afterAction()
    {
        $this->registerAction(__METHOD__);
    }

    protected function registerAction($method)
    {
        $params   = $this->getRequest()->getParams();
        $params[] = $method;
    }
}
