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
        $this->post(
            'userHello',
            array('name' => 'Jon'),
            array('user_id' => 1)
        );

        $expectation = "Method: POST UserId: 1";
        $this->assertResponseOk();
        $this->assertResponseContains('POST');
        $this->assertResponseNotContains('other stuff');
        $this->assertResponseContent($expectation);
    }

    /**
     * @test
     */
    public function canAccessControllerInTheTestCase()
    {
        $this->assertInstanceOf($this->controllerClass, $this->getController());
    }
}
