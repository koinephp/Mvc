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

    public function getRequest()
    {
        $params = array();

        $options = array(
            'session'     => new Session($params),
            'environment' => new Environment($params),
            'cookies'     => new Cookies($params),
            'params'      => new Params($params),
        );

        return $this->getMock('Koine\Http\Request', null, array($options));
    }
}
