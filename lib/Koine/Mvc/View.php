<?php

namespace Koine\Mvc;

use Koine\View\Config;
use Koine\View\Renderer;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class View
{

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $layout;

    public function __construct()
    {
        $this->config   = new Config();
        $this->renderer = new Renderer($this->config);
    }

    /**
     * Get the view renderer
     *
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Get the renderer config
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * The layout name
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the layout name
     * @param  string $layout
     * @return self
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Render a template
     *
     * @param  string $file
     * @param  array  $localVariables
     * @return string
     */
    public function render($templateName, array $localVariables = array())
    {
        $layout = $this->getLayout();

        if ($layout) {
            $localVariables['view'] = $templateName;
            $templateName = $layout;
        }

        return $this->getRenderer()->render($templateName, $localVariables);
    }
}
