<?php

namespace Koine\Mvc;

use Koine\Http\Response;
use Koine\Http\Request;

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
     * @var Response
     */
    protected $response;

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
        $controller = $this->factoryController();

        $action = $this->getAction();

        if (!$controller->respondTo($action)) {
            throw new Exceptions\ActionNotFoundException(sprintf(
                "Action '%s::%s' was not defined",
                $controller->getClass(),
                $action
            ));
        }

        $controller->beforeAction();
        $controller->send($action);
        $controller->afterAction();

        return $controller->getResponse();
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get the request
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
     * @return Controler
     */
    public function factoryController()
    {
        $class = $this->getControllerClass();

        if (!class_exists($class)) {
            throw new Exceptions\ControllerNotFoundException(sprintf(
                "Could not load class '%s'",
                $class
            ));
        }

        $controller = new $class;
        $response   = $this->getResponse();

        if (!$response) {
            $response   = new Response();
        }

        $controller->setView($this->getView())
            ->setResponse($response)
            ->setRequest($this->getRequest());

        return $controller;
    }
}
