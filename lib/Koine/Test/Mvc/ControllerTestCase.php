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
     * @var Controller
     */
    protected $controller;

    public function setUp()
    {
        $this->setUpController($this->controllerClass);
    }

    /**
     * Sets up the controller for testing
     */
    public function setUpController($controllerClass)
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

        $this->controller = new $controllerClass();

        $this->frontController = new FrontController();
        $this->frontController
            ->setRequest($this->request)
            ->setResponse($this->response)
            ->setView($this->view)
            ->setControllerClass($this->controllerClass)
            ->setController($this->controller)
            ->setDependencyContainer($this->dependencyContainer);
    }

    /**
     * Assert that the response redirects to given url
     * @param string  $url
     * @param integer $statusCode
     */
    public function assertResponseRedirectsTo($url, $statusCode = 302)
    {

        $actual = $this->getResponse()->getRedirectUrl();

        $this->assertEquals(
            $actual,
            $url,
            "Failed asserting that redirect '$actual' is '$url' "
        );

        $this->assertResponseStatusCode(302);
    }

    /**
     * Assert that the response has given content
     * @param string $content
     */
    public function assertResponseContent($content)
    {
        $this->assertEquals(
            $content,
            $this->getResponse()->getBody(),
            "Failed asserting that response body is '$content'"
        );
    }

    /**
     * Assert that the response has given content
     * @param string $content
     */
    public function assertResponseContains($content)
    {
        $this->assertContains(
            $content,
            $this->getResponse()->getBody(),
            "Failed asserting that response contains '$content'"
        );
    }

    /**
     * Assert that the response does not contain given content
     * @param string $content
     */
    public function assertResponseNotContains($content)
    {
        $this->assertNotContains(
            $content,
            $this->getResponse()->getBody(),
            "Failed asserting that response does not contain '$content'"
        );
    }

    /**
     * Asserts that the response code is $code
     * @param integer $code
     */
    public function assertResponseStatusCode($code)
    {
        $this->assertEquals(
            $code,
            $this->getResponse()->getStatusCode(),
            "Failed asserting that response status code is '$code'"
        );
    }

    /**
     * Asserts that the response code is 200
     */
    public function assertResponseOk()
    {
        $this->assertResponseStatusCode(200);
    }

    /**
     * Make a request to the controller action
     * @param string $method
     * @param string $action
     * @param array  $params
     * @param array  $session
     * @param array  $environment
     * @param array  $cookies
     */
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

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Adds params to object
     * @param Hash
     * @param  array $params
     * @return self
     */
    public function mergeParams($object, $params)
    {
        foreach ($params as $key => $value) {
            $object[$key] = $value;
        }

        return $this;
    }

    /**
     * Sets the request method
     * @param string method
     * @return self
     */
    public function setMethod($method)
    {
        $this->params['_method'] = $method;

        return $this;
    }

    /**
     * Sets the controller action
     * @param  string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->frontController->setAction($action);

        return $this;
    }

    /**
     * Execute controller and sets up response
     */
    public function dispatch()
    {
        $this->response = $this->frontController->execute();
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Make a POST request
     * @param string $action
     * @param array  $params
     * @param array  $session
     */
    public function post($action, $params = array(), $session = array())
    {
        return $this->makeRequest('POST', $action, $params, $session);
    }

    /**
     * Make a GET request
     * @param string $action
     * @param array  $params
     * @param array  $session
     */
    public function get($action, $params = array(), $session = array())
    {
        return $this->makeRequest('GET', $action, $params, $session);
    }

    /**
     * Make a PATCH request
     * @param string $action
     * @param array  $params
     * @param array  $session
     */
    public function patch($action, $params = array(), $session = array())
    {
        return $this->makeRequest('PATCH', $action, $params, $session);
    }

    /**
     * Make a PUT request
     * @param string $action
     * @param array  $params
     * @param array  $session
     */
    public function put($action, $params = array(), $session = array())
    {
        return $this->makeRequest('PUT', $action, $params, $session);
    }

    /**
     * Make a DELETE request
     * @param string $action
     * @param array  $params
     * @param array  $session
     */
    public function delete($action, $params = array(), $session = array())
    {
        return $this->makeRequest('DELETE', $action, $params, $session);
    }
}
