<?php

/**
 * Plugin.php - Adapter for the Noty library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Noty;

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
        return '<script type="text/javascript" src="https://cdn.jaxon-php.org/libs/noty/latest/jquery.noty.packaged.min.js"></script>';
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
jaxon.command.handler.register("noty.alert", function(args) {
    noty({text: args.data.text, type: args.data.type, layout: "topCenter", timeout: 5000});
});
jaxon.confirm.noty = function(question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    noty({
        text: question,
        layout: "topCenter",
        buttons: [
            {
                addClass: "btn btn-primary",
                text: "' . $this->getYesButtonText() . '",
                onClick: function($noty){
                    $noty.close();
                    yesCallback();
                }
            },{
                addClass: "btn btn-danger",
                text: "' . $this->getNoButtonText() . '",
                onClick: function($noty){
                    $noty.close();
                    noCallback();
                }
            }
        ]
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
            return "noty({text:" . $message . ", type:'" . $type . "', layout: 'topCenter'})";
        }
        $options = array('text' => $message, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'noty.alert'), $options);
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
        return $this->alert($message, $title, 'information');
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
        return $this->alert($message, $title, 'warning');
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
        $title = $this->getConfirmTitle();
        if(!$noScript)
        {
            return "jaxon.confirm.noty(" . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.noty(" . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
