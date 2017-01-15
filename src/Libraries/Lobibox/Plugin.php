<?php

/**
 * Plugin.php - Adapter for the Lobibox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Lobibox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/lobibox/1.2.4/lobibox.min.js"></script>';
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
        return '<link href="https://lib.jaxon-php.org/lobibox/1.2.4/lobibox.min.css" rel="stylesheet" type="text/css">';
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
Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {sound: false, position: "top center", delayIndicator: false});
Lobibox.window.DEFAULTS = $.extend({}, Lobibox.window.DEFAULTS, {width: 700, height: "auto"});
var lobiboxWindowInstance = null;
jaxon.command.handler.register("lobibox.show", function(args) {
    // Add buttons
    for(key in args.data.buttons)
    {
        button = args.data.buttons[key];
        if(button.action == "close")
        {
            button.action = function(){return false;};
            button.closeOnClick = true;
        }
        else
        {
            button.action = new Function(button.action);
            button.closeOnClick = false;
        }
    }
    args.data.callback = function(lobibox, type){
        args.data.buttons[type].action();
    };
    if((lobiboxWindowInstance))
    {
        lobiboxWindowInstance.destroy();
    }
    lobiboxWindowInstance = Lobibox.window(args.data);
});
jaxon.command.handler.register("lobibox.hide", function(args) {
    if((lobiboxWindowInstance))
    {
        lobiboxWindowInstance.destroy();
    }
    lobiboxWindowInstance = null;
});
jaxon.command.handler.register("lobibox.notify", function(args) {
    Lobibox.notify(args.data.type, {title: args.data.title, msg: args.data.message});
});
jaxon.confirm.lobibox = function(question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    Lobibox.confirm({
        msg: question,
        callback: function(lobibox, type){
            if(type == "yes")
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
        $options['content'] = (string)$content;
        $options['buttons'] = array();
        $ind = 0;
        foreach($buttons as $button)
        {
            $options['buttons']['btn' . $ind] = array(
                'text' => $button['title'],
                'action' => $button['click'],
            );
            $ind++;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'lobibox.show'), $options);
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
        $this->addCommand(array('cmd' => 'lobibox.hide'), array());
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
    protected function notify($message, $title, $type)
    {
        $options = array('message' => $message, 'type' => $type, 'title' => (($title) ?: false));
        // Show the alert
        $this->addCommand(array('cmd' => 'lobibox.notify'), $options);
    }

    /**
     * Print a success message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function success($message, $title = null)
    {
        $this->notify($message, $title, 'success');
    }

    /**
     * Print an information message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function info($message, $title = null)
    {
        $this->notify($message, $title, 'info');
    }

    /**
     * Print a warning message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function warning($message, $title = null)
    {
        $this->notify($message, $title, 'warning');
    }

    /**
     * Print an error message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function error($message, $title = null)
    {
        $this->notify($message, $title, 'error');
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
            return 'jaxon.confirm.lobibox(' . $question . ',function(){' . $yesScript . ';})';
        }
        else
        {
            return 'jaxon.confirm.lobibox(' . $question . ',function(){' . $yesScript . ';},function(){' . $noScript . ';})';
        }
    }
}
