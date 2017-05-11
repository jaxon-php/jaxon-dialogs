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
        return $this->getJsCode('/pnotify/3.0.0/pnotify.js') . "\n" .
                $this->getJsCode('/pnotify/3.0.0/pnotify.confirm.js');
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
        return $this->getCssCode('/pnotify/3.0.0/pnotify.css');
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
PNotify.prototype.options.delay = 5000;' . $this->getOptionScript('PNotify.prototype.options.', 'options.') . '
jaxon.command.handler.register("pnotify.alert", function(args) {
    jaxon.pnotify.alert(args.data);
});
jaxon.pnotify = {
    confirm: function(title, question, yesCallback, noCallback){
        PNotify.prototype.options.confirm.buttons[0].text = "' . $this->getYesButtonText() . '";
        PNotify.prototype.options.confirm.buttons[1].text = "' . $this->getNoButtonText() . '";
        if(noCallback == undefined) noCallback = function(){};
        notice = new PNotify({
            title: title,
            text: question,
            hide: false,
            confirm:{
                confirm: true
            },
            buttons:{
                closer: false,
                sticker: false,
                labels: {
                    
                }
            }
        });
        notice.get().on("pnotify.confirm", yesCallback);
        notice.get().on("pnotify.cancel", noCallback);
    },
    alert: function(data) {
        notice = new PNotify(data);
        notice.get().click(function(){notice.remove();});
    }
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
            return "jaxon.pnotify.alert({text:" . $message . ", type:'" . $type . "', title:'" . $title . "'})";
        }
        $options = array('text' => $message, 'title' => $title, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'pnotify.alert'), $options);
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
        return $this->alert($message, $title, 'notice');
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
            return "jaxon.pnotify.confirm('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.pnotify.confirm('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
