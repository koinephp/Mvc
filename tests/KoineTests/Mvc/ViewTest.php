<?php

namespace KonieTests\Mvc;

use Koine\Mvc\View;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ViewTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new View();
    }

    /**
     * @test
     */
    public function canBeInstantiated()
    {
        $this->assertInstanceOf('Koine\Mvc\View', $this->object);
    }
}
