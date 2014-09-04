<?php

namespace KonieTests\Mvc;

use Koine\Mvc\View;
use Koine\View\Config;
use Koine\View\Renderer;
use PHPUnit_Framework_TestCase;

class ViewTester extends View
{
    public function __construct($renderer)
    {
        parent::__construct();

        $this->renderer = $renderer;
    }
}

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itInstanciatesAViewRenderer()
    {
        $view = new View();

        $this->assertInstanceOf('Koine\View\Renderer', $view->getRenderer());
    }

    /**
     * @test
     */
    public function itInstanciatesAConfig()
    {
        $view = new View();

        $this->assertInstanceOf('Koine\View\Config', $view->getConfig());
    }

    /**
     * @test
     */
    public function getAndSetLayout()
    {
        $view   = new View();
        $layout = $view->setLayout('foo')->getLayout();

        $this->assertEquals('foo', $layout);
    }

    /**
     * @test
     */
    public function rendersWithNoLayout()
    {
        $config = new Config;
        $mock   = $this->getMock('Koine\View\Renderer', array(), array($config));

        $params = array('foo' => 'bar');

        $mock->expects($this->once())
            ->method('render')
            ->with('foo', $params);

        $view   = new ViewTester($mock);
        $view->render('foo', $params);
    }

    /**
     * @test
     */
    public function rendersWithLayout()
    {
        $config = new Config;
        $renderer = $this->getMock('Koine\View\Renderer', array(), array($config));

        $expectedParams = array(
            'view' => 'foo',
            'foo'  => 'bar',
            'localVariables' => array(
                'foo'  => 'bar',
            )
        );

        $renderer->expects($this->once())
            ->method('render')
            ->with('foo_layout', $expectedParams);

        $view = new ViewTester($renderer);
        $view->setLayout('foo_layout')->render('foo', array('foo' => 'bar'));
    }
}
