<?php

namespace Jaxon\Dialogs\Libraries\SimplyToast;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/simply-toast/latest/simply-toast.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/simply-toast/latest/simply-toast.min.css" />';
    }

    public function getScript()
    {
        $aOptions = $this->getOptionNames('options.');
        return '
jaxon.command.handler.register("simply.alert", function(args) {
    $.simplyToast(args.data.message, args.data.type, ' . json_encode($aOptions) . ');
});';
    }

    private function alert($message, $type)
    {
        
        $this->addCommand(array('cmd' => 'simply.alert'), array('message' => $message, 'type' => $type));
    }

    public function success($message, $title = null)
    {
        $this->alert($message, 'success');
    }

    public function info($message, $title = null)
    {
        $this->alert($message, 'info');
    }

    public function warning($message, $title = null)
    {
        $this->alert($message, 'warning');
    }

    public function error($message, $title = null)
    {
        $this->alert($message, 'danger');
    }
}
