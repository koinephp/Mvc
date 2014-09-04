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

class Helper extends View
{
    public function sayHello($name, $lastName)
    {
        return "Hello $name $lastName";
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
        $view = new View();
        $view->getConfig()
            ->setHelper('helper', new Helper())
            ->addPath($this->getFixtruesPath());

        $actual = $view->render('view', array(
            'name'     => 'Jon',
            'lastName' => 'Doe',
        ));

        $expected = "<p>Hello Jon Doe</p>\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function rendersWithLayout()
    {

        $view = new View();
        $view->getConfig()
            ->setHelper('helper', new Helper())
            ->addPath($this->getFixtruesPath());

        $actual = $view->setLayout('layout')->render('view', array(
            'name'     => 'Jon',
            'lastName' => 'Doe',
        ));

        $expected = "<h1>Doe</h1>\n<p>Hello Jon Doe</p>\n";
        $this->assertEquals($expected, $actual);
        $this->assertEquals('layout', $view->getLayout());
    }

    public function getFixtruesPath()
    {
        return dirname(__FILE__) . '/../../fixtures';
    }
}
