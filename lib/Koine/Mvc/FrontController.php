<?php

namespace Koine\Mvc;

use Koine\Http\Response;
use Koine\Http\Request;
use Nurse\Container;

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
     * @var Controller
     */
    protected $controller;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Container
     */
    protected $dependencyContainer;

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
     * Set up controller
     * @param  Controller $controller
     * @return self
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
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

    /**
     * If the controller was not manually set, it instanciates it with the
     * provided controller class
     *
     * @return Response
     */
    public function execute()
    {
        $controller = $this->getController();

        if (!$controller) {
            $controller = $this->factoryController();
            $this->setController($controller);
        }

        $this->prepareController($controller);

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

        return new $class;
    }

    /**
     * Set data to the controller
     * @param Controller
     */
    public function prepareController($controller)
    {
        $response   = $this->getResponse();

        if (!$response) {
            $response = new Response();
        }

        $controller->setView($this->getView())
            ->setResponse($response)
            ->setDependencyContainer($this->getDependencyContainer())
            ->setRequest($this->getRequest());
    }
}
