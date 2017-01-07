<?php

namespace Jaxon\Dialogs\Libraries\Noty;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/noty/latest/jquery.noty.packaged.min.js"></script>';
    }

    public function getScript()
    {
        return '
jaxon.command.handler.register("noty.alert", function(args) {
    noty({text: args.data.text, type: args.data.type, layout: "topCenter", timeout: 5000});
});
';
    }

    protected function alert($message, $title, $type)
    {
        $options = array('text' => $message, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'noty.alert'), $options);
    }

    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'success');
    }

    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'information');
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
        return "noty({text: " . $question . ",buttons:[{addClass: 'btn btn-primary', text: 'Ok', " .
            "onClick: function(\$noty){\$noty.close();" . $script . ";}},{addClass: 'btn btn-danger', " .
            "text: 'Cancel', onClick: function(\$noty){\$noty.close();}}]})";
    }
}
