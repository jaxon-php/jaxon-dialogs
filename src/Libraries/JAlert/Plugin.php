<?php

namespace Jaxon\Dialogs\Libraries\Jalert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/jAlert/4.5.1/jAlert.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/jAlert/4.5.1/jAlert.css" />';
    }

    public function getScript()
    {
        return '
jaxon.command.handler.register("jalert.alert", function(args) {
    $.jAlert({title: args.data.title, content: args.data.content, theme: args.data.theme});
});
';
    }

    protected function alert($content, $title, $theme)
    {
        $this->addCommand(array('cmd' => 'jalert.alert'), array('content' => $content, 'title' => $title, 'theme' => $theme));
    }

    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'green');
    }

    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'blue');
    }

    public function warning($message, $title = null)
    {
        $this->alert($message, $title, 'orange');
    }

    public function error($message, $title = null)
    {
        $this->alert($message, $title, 'red');
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
        return "$.jAlert({type: 'confirm', confirmQuestion: '" . addslashes($question) .
            "', onConfirm: function(){" . $script . ";}, onDeny: function(){}});return false;";
    }
}
