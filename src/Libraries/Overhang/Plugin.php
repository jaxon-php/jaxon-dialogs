<?php

namespace Jaxon\Dialogs\Libraries\Overhang;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/overhang/latest/overhang.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/overhang/latest/overhang.min.css" />';
    }

    public function getScript()
    {
        return '
jaxon.command.handler.register("overhang.alert", function(args) {
    // Default options
    args.data.duration = 5;
    $("body").overhang(args.data);
});
';
    }

    protected function alert($message, $title, $type)
    {
        $options = array('message' => $message, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'overhang.alert'), $options);
    }

    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'success');
    }

    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'info');
    }

    public function warning($message, $title = null)
    {
        $this->alert($message, $title, 'warn');
    }

    public function error($message, $title = null)
    {
        $this->alert($message, $title, 'error');
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
        return "$('body').overhang({type: 'confirm', message:" . $question . ", callback: function(res){if(res){" . $script . ";}}});";
    }
}
