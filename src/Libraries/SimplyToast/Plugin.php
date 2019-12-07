<?php

/**
 * Plugin.php - Adapter for the SimplyToast library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\SimplyToast;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Message
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('simply-toast', 'latest');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('simply-toast.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('simply-toast.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('simplytoast/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('simplytoast/ready.js.php', [
            'options' => json_encode($this->getOptionNames('options.'))
        ]);
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $type                 The type of the message
     *
     * @return void
     */
    private function alert($message, $type)
    {
        if($this->getReturn())
        {
            return "$.simplyToast(" . $message . ", '" . $type . "')";
        }
        $this->addCommand(array('cmd' => 'simply.alert'), array('message' => $message, 'type' => $type));
    }

    /**
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, 'danger');
    }
}
