<?php

namespace Jaxon\Dialogs\Libraries\Notify;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/notify/0.4.2/notify.js"></script>';
    }

    public function getCss()
    {
        return '';
    }

    public function getScript()
    {
        return '
jaxon.command.handler.register("notify.alert", function(args) {
    $.notify(args.data.message, {className: args.data.class, position: "top center"});
});
';
    }

    protected function alert($message, $title, $class)
    {
        $options = array('message' => $message, 'class' => $class);
        // Show the alert
        $this->addCommand(array('cmd' => 'notify.alert'), $options);
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
}
