<?php

namespace KonieTests\Mvc;

use Koine\Mvc\Controller;
use PHPUnit_Framework_TestCase;

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
    public function canBeInstantiated()
    {
        $this->assertInstanceOf('Koine\Mvc\Controller', $this->object);
    }
}
