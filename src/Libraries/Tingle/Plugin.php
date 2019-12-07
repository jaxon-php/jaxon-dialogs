<?php

/**
 * Plugin.php - Adapter for the Tingle library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Tingle;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Modal
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('tingle', '0.8.4');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('tingle.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('tingle.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('tingle/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('tingle/ready.js');
    }

    /**
     * @inheritDoc
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Show the footer only if there is a button to display.
        $options['footer'] = (count($buttons) > 0);
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'tingle.show'),
            array('content' => '<h2>' . $title . '</h2>' . $content, 'buttons' => $buttons, 'options' => $options));
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'tingle.hide'), array());
    }
}
