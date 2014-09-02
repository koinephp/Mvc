<?php

namespace KoineTests\Test\Mvc;

use Koine\Test\Mvc\ControllerTestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ControllerTestCaseTest extends ControllerTestCase
{
    protected $controllerClass = 'Dummy\Controllers\ExampleController';

    /**
     * @test
     */
    public function canTestRedirection()
    {
        $this->makeRequest('GET', 'userHello');
        $this->assertResponseRedirectsTo('/');
    }
}
