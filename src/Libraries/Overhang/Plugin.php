<?php

/**
 * Plugin.php - Adapter for the Overhang library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Overhang;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('overhang', 'latest');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('overhang.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('overhang.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('overhang/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('overhang/ready.js.php');
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $type                 The type of the message
     *
     * @return void
     */
    protected function alert($message, $title, $type)
    {
        if($this->getReturn())
        {
            return "$('body').overhang({message:" . $message . ", type:'" . $type . "'})";
        }
        $options = array('message' => $message, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'overhang.alert'), $options);
    }

    /**
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'warn');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        if(!$noScript)
        {
            return "jaxon.dialogs.overhang.confirm(" . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.overhang.confirm(" . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
