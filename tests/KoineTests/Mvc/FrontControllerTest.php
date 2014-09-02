<?php

namespace KonieTests\Mvc;

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
     */
    public function canFactoryControllerWithAllTheNecessaryProperties()
    {
        $frontController = new FrontController();

        $view = $this->getMock('Koine\Mvc\View');
        $request = $this->getRequest();

        $frontController->setControllerClass('Dummy\DemoController')
            ->setView($view)
            ->setRequest($request);

        $controller = $frontController->factoryController();

        $this->assertInstanceOf('Dummy\DemoController', $controller);

        $this->assertSame($view, $controller->getView());
        $this->assertSame($request, $controller->getRequest());

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

        $frontController->setControllerClass('Dummy\DemoController')
            ->setView($view)
            ->setResponse($response)
            ->setRequest($request);

        $controller = $frontController->factoryController();

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
            ->setControllerClass('Dummy\DemoController')
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
            'Dummy\DemoController::beforeAction',
            'Dummy\DemoController::test',
            'Dummy\DemoController::afterAction'
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
     * @expectedExceptionMessage Action 'Dummy\DemoController::undefined' was not defined
     */
    public function throwsExceptionWhenActionDoesNotExist()
    {
        $this->setupFrontController()->setAction('undefined')->execute();
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
}
