<?php

namespace Koine\Test\Mvc;

use PHPUnit_Framework_TestCase;
use Koine\Http\Session;
use Koine\Http\Cookies;
use Koine\Http\Params;
use Koine\Http\Environment;
use Koine\Http\Request;
use Koine\Http\Response;
use Koine\Mvc\View;
use Koine\Mvc\Controller;
use Koine\Mvc\FrontController;
use Nurse\Di;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ControllerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var \Koine\Mvc\Controller
     */
    protected $controller;

    public function setUp()
    {
        $this->setUpController();
    }

    public function setUpController()
    {
        $session     = array();
        $cookies     = array();
        $environment = array();
        $params      = array();

        $this->session             = new Session($session);
        $this->cookies             = new Cookies($cookies);
        $this->environment         = new Environment($environment);
        $this->params              = new Params($params);
        $this->response            = new Response($params);
        $this->dependencyContainer = Di::getInstance()->getContainer();
        $this->view                = new View();

        $this->request = new Request(array(
            'session'     => $this->session,
            'cookies'     => $this->cookies,
            'environment' => $this->environment,
            'params'      => $this->params,
        ));

        $this->frontController = new FrontController();
        $this->frontController
            ->setRequest($this->request)
            ->setResponse($this->response)
            ->setView($this->view)
            ->setControllerClass($this->controllerClass)
            ->setDependencyContainer($this->dependencyContainer);
    }

    public function assertResponseRedirectsTo($url, $statusCode = 302)
    {
        $this->assertEquals(
            $statusCode,
            $this->getResponse()->getStatusCode(),
            "Failed asserting that response status code is '$statusCode'"
        );

        $headers = $this->getResponse()->getHeaders();
        $header = (string) $headers['Location'];
        $actual = str_replace('Location: ', '', $header);

        $this->assertEquals(
            $actual,
            $url,
            "Failed asserting that redirect '$actual' is '$url' "
        );
    }

    public function makeRequest(
        $method,
        $action,
        array $params = array(),
        array $session = array(),
        array $environment = array(),
        array $cookies = array()
    )
    {
        $this->mergeParams($this->session, $session);
        $this->mergeParams($this->params, $params);
        $this->mergeParams($this->environment, $environment);
        $this->mergeParams($this->cookies, $cookies);

        $this->setMethod($method);
        $this->setAction($action);
        $this->dispatch();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function mergeParams($object, $params)
    {
        foreach ($params as $key => $value) {
            $object[$key] = $value;
        }

        return $this;
    }

    public function setMethod($method)
    {
        $this->environment['REQUEST_METHOD'] = $method;

        return $this;
    }

    public function setAction($action)
    {
        $this->frontController->setAction($action);

        return $this;
    }

    public function dispatch()
    {
        $this->response = $this->frontController->execute();
    }
}
