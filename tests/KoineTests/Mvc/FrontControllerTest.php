<?php

namespace KonieTests\Mvc;

use Koine\Mvc\FrontController;
use PHPUnit_Framework_TestCase;

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
    public function canBeInstantiated()
    {
        $this->assertInstanceOf('Koine\Mvc\FrontController', $this->object);
    }
}
