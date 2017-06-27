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
        return $this->getJsCode('/overhang/latest/overhang.min.js');
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
        return $this->getCssCode('/overhang/latest/overhang.min.css');
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
jaxon.command.handler.register("overhang.alert", function(args) {
    // Default options
    args.data.duration = 5;
    $("body").overhang(args.data);
});
jaxon.confirm.overhang = function(question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    $("body").overhang({
        type: "confirm",
        message: question,
        callback: function(res){
            if(res)
                yesCallback();
            else
                noCallback();
        }
    });
};
';
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
        return $this->alert($message, $title, 'success');
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
        return $this->alert($message, $title, 'info');
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
        return $this->alert($message, $title, 'warn');
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
        return $this->alert($message, $title, 'error');
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
        if(!$noScript)
        {
            return "jaxon.confirm.overhang(" . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.overhang(" . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
