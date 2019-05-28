<?php

/**
 * Plugin.php - Adapter for the PNotify library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\PNotify;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Alert;
use Jaxon\Contracts\Dialogs\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    use \Jaxon\Features\Dialogs\Alert;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('pnotify', '3.0.0');
    }

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('pnotify.js') . "\n" . $this->getJsCode('pnotify.confirm.js');
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->getCssCode('pnotify.css');
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return $this->render('pnotify/alert.js', [
            'options' => $this->getOptionScript('PNotify.prototype.options.', 'options.')
        ]);
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
            return "jaxon.dialogs.pnotify.alert({text:" . $message . ", type:'" . $type . "', title:'" . $title . "'})";
        }
        $options = array('text' => $message, 'title' => $title, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'pnotify.alert'), $options);
    }

    /**
     * Print a success message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'success');
    }

    /**
     * Print an information message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'info');
    }

    /**
     * Print a warning message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'notice');
    }

    /**
     * Print an error message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'error');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Confirm interface.
     *
     * @return string
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getConfirmTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.pnotify.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.pnotify.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
