<?php

namespace Koine\Mvc;

use Koine\View\Config;
use Koine\View\Renderer;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class View extends Renderer
{
    /**
     * @var string
     */
    protected $layout;

    /**
     * @var string
     */
    protected $lastLayout;

    public function __construct()
    {
        parent::__construct(new Config());
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
            $localVariables['localVariables'] = $localVariables;
            $localVariables['view'] = $templateName;
            $templateName = $layout;
            $this->lastLayout = $layout;
            $this->setLayout(null);
        } else {
            $this->setLayout($this->lastLayout);
        }

        return parent::render($templateName, $localVariables);
    }
}
