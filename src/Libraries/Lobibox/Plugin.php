<?php

namespace Jaxon\Dialogs\Libraries\Lobibox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="//lib.jaxon-php.org/lobibox/1.2.4/lobibox.min.js"></script>';
    }

    public function getCss()
    {
        return '<link href="//lib.jaxon-php.org/lobibox/1.2.4/lobibox.min.css" rel="stylesheet" type="text/css">';
    }

    public function getScript()
    {
        return '
Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {sound: false, position: "top center", delayIndicator: false});
Lobibox.window.DEFAULTS = $.extend({}, Lobibox.window.DEFAULTS, {width: 700, height: "auto"});
jaxon.command.handler.register("lobibox.window", function(args) {
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
    Lobibox.window(args.data);
});
jaxon.command.handler.register("lobibox.notify", function(args) {
    Lobibox.notify(args.data.type, {title: args.data.title, msg: args.data.message});
});
';
    }

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
        $this->addCommand(array('cmd' => 'lobibox.window'), $options);
    }

    public function hide()
    {
        $this->response()->script('Lobibox.hide()');
    }

    protected function notify($message, $title, $type)
    {
        $options = array('message' => $message, 'type' => $type, 'title' => (($title) ?: false));
        // Show the alert
        $this->addCommand(array('cmd' => 'lobibox.notify'), $options);
    }

    public function success($message, $title = null)
    {
        $this->notify($message, $title, 'success');
    }

    public function info($message, $title = null)
    {
        $this->notify($message, $title, 'info');
    }

    public function warning($message, $title = null)
    {
        $this->notify($message, $title, 'warning');
    }

    public function error($message, $title = null)
    {
        $this->notify($message, $title, 'error');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question
     * 
     * This is the implementation of the Jaxon\Request\Interfaces\Confirm interface.
     * 
     * @return string
     */
    public function getScriptWithQuestion($question, $script)
    {
        return "Lobibox.confirm({msg: " . $question . ",callback:function(lobibox, type){if(type == 'yes'){" . $script . ";}}})";
    }
}
