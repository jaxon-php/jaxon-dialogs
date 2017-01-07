<?php

namespace Jaxon\Dialogs\Libraries\SweetAlert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/sweetalert/1.1.1/sweetalert.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/sweetalert/latest/sweetalert.css" />';
    }

    public function getScript()
    {
        return '
var sweetAlertOptions = {
    allowEscapeKey: true,
    allowOutsideClick: true
};
jaxon.command.handler.register("sweetalert.alert", function(args) {
    // Set user and default options into data only when they are missing
    for(key in sweetAlertOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = sweetAlertOptions[key];
        }
    }
    swal(args.data);
});
jaxon.swal = {
    confirm: function(question, callback){
        swal({type: "warning", title:"", showCancelButton: true, text: question},
            function(isConfirm){if(isConfirm){callback();}});
    }
};
';
    }

    protected function alert($message, $title, $type)
    {
        $options = array('text' => $message, 'title' => '', 'type' => $type);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'sweetalert.alert'), $options);
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
        $this->alert($message, $title, 'warning');
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
    public function confirm($question, $script)
    {
        return "jaxon.swal.confirm($question,function(){" . $script . ";})";
    }
}
