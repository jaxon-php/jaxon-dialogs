<?php

/**
 * Plugin.php - Adapter for the Bootstrap library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Bootstrap;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
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
        return $this->getJsCode('/bootstrap-dialog/1.35.3/bootstrap-dialog.min.js');
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
        return $this->getCssCode('/bootstrap-dialog/1.35.3/bootstrap-dialog.min.css');
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
jaxon.command.handler.register("bootstrap.show", function(args) {
    // Add buttons
    for(var ind = 0, len = args.data.buttons.length; ind < len; ind++)
    {
        button = args.data.buttons[ind];
        if(button.action == "close")
        {
            button.action = function(dialog){dialog.close();};
        }
        else
        {
            button.action = new Function(button.action);
        }
    }
    // Open modal
    BootstrapDialog.show(args.data);
});
jaxon.command.handler.register("bootstrap.hide", function(args) {
    // Hide modal
    BootstrapDialog.closeAll();
});
jaxon.command.handler.register("bootstrap.alert", function(args) {
    var dataTypes = {
        "success": BootstrapDialog.TYPE_SUCCESS,
        "info": BootstrapDialog.TYPE_INFO,
        "warning": BootstrapDialog.TYPE_WARNING,
        "danger": BootstrapDialog.TYPE_DANGER
    };
    args.data.type = dataTypes[args.data.type];
    BootstrapDialog.alert(args.data);
});
jaxon.confirm.bootstrap = function(title, question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    BootstrapDialog.confirm({
        title: title,
        message: question,
        btnOKLabel: "' . $this->getYesButtonText() . '",
        btnCancelLabel: "' . $this->getNoButtonText() . '",
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
     * Show a modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     * 
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Fill the options array with the parameters
        $options['title'] = (string)$title;
        $options['message'] = (string)$content;
        $options['buttons'] = array();
        foreach($buttons as $button)
        {
            $options['buttons'][] = array(
                'label' => $button['title'],
                'cssClass' => $button['class'],
                'action' => $button['click'],
            );
        }
        // Turn the value of the nl2br option to false, because it alters form rendering.
        if(!array_key_exists('nl2br', $options))
        {
            $options['nl2br'] = false;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.show'), $options);
    }

    /**
     * Hide the modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @return void
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.hide'), array());
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
            $aDataTypes = [
                'success' => 'BootstrapDialog.TYPE_SUCCESS',
                'info' => 'BootstrapDialog.TYPE_INFO',
                'warning' => 'BootstrapDialog.TYPE_WARNING',
                'danger' => 'BootstrapDialog.TYPE_DANGER',
            ];
            $type = $aDataTypes[$type];
            if(($title))
            {
                return "BootstrapDialog.alert({message:" . $message . ", title:'" . $title . "', type:" . $type . "})";
            }
            else
            {
                return "BootstrapDialog.alert({message:" . $message . ", type:" . $type . "})";
            }
        }
        $options = array('message' => $message, 'type' => $type);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'bootstrap.alert'), $options);
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
        return $this->alert($message, $title, 'danger');
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
            return "jaxon.confirm.bootstrap('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.bootstrap('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}

?>