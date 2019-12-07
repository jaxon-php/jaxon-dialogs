<?php

/**
 * Plugin.php - Adapter for the Notify library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Notify;

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
        parent::__construct('notify', '0.4.2');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('notify.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('notify/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('notify/ready.js.php');
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $class                The type of the message
     *
     * @return void
     */
    protected function alert($message, $title, $class)
    {
        if($this->getReturn())
        {
            return "$.notify(" . $message . ", {className:'" . $class . "', position:'top center'})";
        }
        $options = array('message' => $message, 'className' => $class);
        // Show the alert
        $this->addCommand(array('cmd' => 'notify.alert'), $options);
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
}
