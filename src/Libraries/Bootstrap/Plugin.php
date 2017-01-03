<?php

namespace Jaxon\Dialogs\Libraries\Bootstrap;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/bootstrap-dialog/latest/bootstrap-dialog.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/bootstrap-dialog/latest/bootstrap-dialog.min.css" />';
    }

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
jaxon.command.handler.register("bootstrap.success", function(args) {
    args.data.type = BootstrapDialog.TYPE_SUCCESS;
    BootstrapDialog.alert(args.data);
});
jaxon.command.handler.register("bootstrap.info", function(args) {
    args.data.type = BootstrapDialog.TYPE_INFO;
    BootstrapDialog.alert(args.data);
});
jaxon.command.handler.register("bootstrap.warning", function(args) {
    args.data.type = BootstrapDialog.TYPE_WARNING;
    BootstrapDialog.alert(args.data);
});
jaxon.command.handler.register("bootstrap.danger", function(args) {
    args.data.type = BootstrapDialog.TYPE_DANGER;
    BootstrapDialog.alert(args.data);
});';
    }

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
        // Turn the default value of the nl2br option to false, because its alter form rendering.
        if(!array_key_exists('nl2br', $options))
        {
            $options['nl2br'] = false;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.show'), $options);
    }

    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.hide'), array());
    }

    protected function alert($message, $title, $type)
    {
        $options = array('message' => $message);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'bootstrap.' . $type), $options);
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
        $this->alert($message, $title, 'danger');
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
        return "BootstrapDialog.confirm(" . $question . ",function(res){if(res){" . $script . ";}})";
    }
}

?>