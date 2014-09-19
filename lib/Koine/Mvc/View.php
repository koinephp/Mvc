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
     * Renders with layout
     *
     * @param string $template
     * @param array  $localVariables
     */
    public function renderWithLayout($template, array $localVariables = array())
    {
        $this->addData(array(
            'localVariables' => $localVariables,
            'view'           => $template,
        ));

        return $this->render($this->getLayout(), $localVariables);
    }
}
