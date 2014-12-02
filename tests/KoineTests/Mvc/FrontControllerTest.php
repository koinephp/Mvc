<?php

namespace Koine\Mvc;

use PHPUnit_Framework_TestCase;
use Koine\Mvc\FrontController;
use Koine\Mvc\View;
use Koine\Mvc\Controller;
use Koine\Http\Request;
use Koine\Http\Response;
use Koine\Http\Session;
use Koine\Http\Cookies;
use Koine\Http\Params;
use Koine\Http\Environment;
use Nurse\Di;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class FrontControllerTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new FrontController();
    }

    /**
     * @test
     */
    public function canSetAndGetControllerClass()
    {
        $class = $this->object->setControllerClass('Class')
            ->getControllerClass();

        $this->assertEquals('Class', $class);
    }

    /**
     * @test
     */
    public function canSetAndGetControllerAction()
    {
        $action = $this->object->setAction('action')
            ->getAction();

        $this->assertEquals('action', $action);
    }

    /**
     * @test
     */
    public function canSetAndGetResponse()
    {
        $expected = new Response();

        $response = $this->object->setResponse($expected)->getResponse();

        $this->assertSame($expected, $response);
    }

    /**
     * @test
     */
    public function canSetAndGetRequest()
    {
        $expected = $this->getRequest();

        $request = $this->object->setRequest($expected)->getRequest();

        $this->assertSame($expected, $request);
    }

    /**
     * @test
     */
    public function canGetAndSetView()
    {
        $expected = new View();
        $view = $this->object->setView($expected)->getView();
        $this->assertSame($expected, $view);
    }

    /**
     * @test
     * @expectedException Koine\Mvc\Exceptions\ControllerNotFoundException
     * @expectedExceptionMessage Could not load class 'Dummy\UndefinedController'
     */
    public function throwsExceptionWhenControllerDoesNotExist()
    {
        $this->setupFrontController()
            ->setControllerClass('Dummy\UndefinedController')
            ->execute();
    }

    /**
     * @test
     */
    public function canSetAndGetDependencyContainer()
    {
        $expected = Di::getInstance()->getContainer();

        $container = $this->object
            ->setDependencyContainer($expected)
            ->getDependencyContainer();

        $this->assertSame($expected, $container);
    }

    /**
     * @test
     */
    public function canFactoryControllerWithAllTheNecessaryProperties()
    {
        $frontController = new FrontController();

        $view = $this->getMock('Koine\Mvc\View');
        $request = $this->getRequest();
        $container = Di::getInstance()->getContainer();

        $frontController->setControllerClass('Dummy\Controllers\DemoController')
            ->setView($view)
            ->setDependencyContainer($container)
            ->setRequest($request);

        $controller = $frontController->factoryController();
        $frontController->prepareController($controller);

        $this->assertInstanceOf('Dummy\Controllers\DemoController', $controller);

        $this->assertSame($view, $controller->getView());
        $this->assertSame($request, $controller->getRequest());
        $this->assertSame($container, $controller->getDependencyContainer());

        $this->assertInstanceOf(
            'Koine\Http\Response',
            $controller->getResponse()
        );
    }

    /**
     * @test
     */
    public function whenResponseIsSetTheControllerResponseIsThatSameResponse()
    {
        $frontController = new FrontController();

        $view = $this->getMock('Koine\Mvc\View');
        $request = $this->getRequest();
        $response = new Response();

        $frontController->setControllerClass('Dummy\Controllers\DemoController')
            ->setView($view)
            ->setResponse($response)
            ->setDependencyContainer(Di::getInstance()->getContainer())
            ->setRequest($request);

        $controller = $frontController->factoryController();
        $frontController->prepareController($controller);

        $this->assertSame($response, $controller->getResponse());
    }

    public function getRequest($mock = true)
    {
        $params = array();

        $options = array(
            'session'     => new Session($params),
            'environment' => new Environment($params),
            'cookies'     => new Cookies($params),
            'params'      => new Params($params),
        );

        if ($mock) {
            return $this->getMock('Koine\Http\Request', null, array($options));
        } else {
            return new Request($options);
        }
    }

    protected function setUpFrontController()
    {
        $request = $this->getRequest(false);

        $this->object->setRequest($request)
            ->setResponse(new Response())
            ->setControllerClass('Dummy\Controllers\DemoController')
            ->setDependencyContainer(Di::getInstance()->getContainer())
            ->setView(new View())
            ->setAction('test');

        return $this->object;
    }

    /**
     * @test
     */
    public function executeExecutesTheBeforeActionAndThenTheActionAndThenTheAfterAction()
    {
        $this->setUpFrontController()->execute();
        $request = $this->object->getRequest();

        $methods = array(
            'Dummy\Controllers\DemoController::beforeAction',
            'Dummy\Controllers\DemoController::test',
            'Dummy\Controllers\DemoController::afterAction'
        );

        $this->assertEquals($methods, $request->getParams()->toArray());
    }

    /**
     * @test
     */
    public function executeReturnsTheResponse()
    {
        $response = $this->setUpFrontController()->execute();
        $expected = $this->object->getResponse();

        $this->assertSame($expected, $response);
    }

    /**
     * @test
     * @expectedException Koine\Mvc\Exceptions\ActionNotFoundException
     * @expectedExceptionMessage Action 'Dummy\Controllers\DemoController::undefined' was not defined
     */
    public function throwsExceptionWhenActionDoesNotExist()
    {
        $this->setupFrontController()->setAction('undefined')->execute();
    }
}
