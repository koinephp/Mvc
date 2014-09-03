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

    /**
     * @test
     */
    public function canTestMethodAndParams()
    {
        $this->makeRequest(
            'POST', 'userHello',
            array('name' => 'Jon'),
            array('user_id' => 1)
        );

        $expectation = "Method: POST UserId: 1";
        $this->assertResponseOk();
        $this->assertEquals($expectation, $this->getResponse()->getBody());
    }
}
