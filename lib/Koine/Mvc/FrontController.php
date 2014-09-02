<?php

namespace Koine\Mvc;

use Koine\Http\Request;
use Koine\Http\Response;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class FrontController
{

    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var View
     */
    protected $view;

    /**
     * Set the controller class
     *
     * @param  string $class
     * @return self
     */
    public function setControllerClass($class)
    {
        $this->controllerClass = $class;

        return $this;
    }

    /**
     * Get the controller class to be executed
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param  string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the action to be executed
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function execute()
    {

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
     * @return Controler
     */
    public function factoryController()
    {
        $class      = $this->getControllerClass();
        $controller = new $class;
        $response   = new Response();

        $controller->setView($this->getView())
            ->setResponse($response)
            ->setRequest($this->getRequest());

        return $controller;
    }
}
