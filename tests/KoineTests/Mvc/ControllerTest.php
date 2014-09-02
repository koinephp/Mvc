<?php

namespace KonieTests\Mvc;

use PHPUnit_Framework_TestCase;
use Koine\Mvc\Controller;
use Koine\Mvc\View;
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
class ControllerTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Controller();
    }

    /**
     * @test
     */
    public function extendsKoineHash()
    {
        $this->assertInstanceOf('Koine\Object', $this->object);
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
        $params = array();

        $expected = new Request(array(
            'session'     => new Session($params),
            'environment' => new Environment($params),
            'cookies'     => new Cookies($params),
            'params'      => new Params($params),
        ));

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
    public function renderSetTheContentToTheResponse()
    {
        $response = new Response();

        $view = $this->getMock('Koine\Mvc\View');
        $view->expects($this->once())
            ->method('render')
            ->with('foo', array('foo' => 'bar'))
            ->will($this->returnValue('hello world'));

        $this->object
            ->setResponse($response)
            ->setView($view);

        $this->object->render('foo', array('foo' => 'bar'));

        $this->assertEquals('hello world', $response->getBody());
    }
}
