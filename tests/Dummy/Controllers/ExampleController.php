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
        $userId = $this->getRequest()->getSession()->offsetGet('user_id');

        if ($userId) {
            $method = $this->getRequest()->getMethod();
            $body = "Method: $method UserId: $userId";
            $this->getResponse()->setBody($body);
        } else {
            $headers = $this->getResponse()->getHeaders();
            $this->getResponse()->redirectTo("/");
        }
    }
}
