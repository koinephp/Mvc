<?php

namespace Koine\Mvc;

use Koine\Object;
use Koine\Http\Request;
use Koine\Http\Response;
use Nurse\Container;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Controller extends Object
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Container
     */
    protected $dependencyContainer;

    /**
     * Method run before the real action
     */
    public function beforeAction()
    {

    }

    /**
     * Method run after the real action
     */
    public function afterAction()
    {

    }

    /**
     * Sets the response object
     *
     * @param  Response $response
     * @return self
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get the response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the request
     *
     * @param  View $view
     * @return set
     */
    public function setView(View $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get the view
     *
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the application dependency container
     *
     * @param  Container $container
     * @return self
     */
    public function setDependencyContainer(Container $container)
    {
        $this->dependencyContainer = $container;

        return $this;
    }

    /**
     * Get the application dependency container
     *
     * @return Container
     */
    public function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * Appends template content to the body
     *
     * @param string $template
     * @param array  $localVariables
     */
    public function render($template, array $localVariables = array())
    {
        $content = $this->getView()->render($template, $localVariables);
        $this->getResponse()->setBody($content);

        return $this;
    }
}
