<?php

/**
 * Plugin.php - Adapter for the jAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Jalert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    use \Jaxon\Request\Traits\Alert;

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/jAlert/4.5.1/jAlert.min.js"></script>';
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/jAlert/4.5.1/jAlert.css" />';
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return '
jaxon.command.handler.register("jalert.alert", function(args) {
    $.jAlert(args.data);
});
jaxon.confirm.jalert = function(title, question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    $.jAlert({
        title: title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: "' . $this->getYesButtonText() . '",
        denyBtnText: "' . $this->getNoButtonText() . '",
        onConfirm: yesCallback,
        onDeny: noCallback
    });
};
';
    }

    /**
     * Print an alert message.
     * 
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $theme                The type of the message
     * 
     * @return void
     */
    protected function alert($content, $title, $theme)
    {
        if(!$title)
        {
            $title = '&nbsp;';
        }
        if($this->getReturn())
        {
            return "$.jAlert({content:" . $content . ", title:'" . $title . "', theme:'" . $theme . "'})";
        }
        $this->addCommand(array('cmd' => 'jalert.alert'), array('content' => $content, 'title' => $title, 'theme' => $theme));
    }

    /**
     * Print a success message.
     * 
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'green');
    }

    /**
     * Print an information message.
     * 
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'blue');
    }

    /**
     * Print a warning message.
     * 
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'yellow');
    }

    /**
     * Print an error message.
     * 
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'red');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question.
     * 
     * It is a function of the Jaxon\Request\Interfaces\Confirm interface.
     * 
     * @return string
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getConfirmTitle();
        if(!$noScript)
        {
            return "jaxon.confirm.jalert('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.jalert('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
