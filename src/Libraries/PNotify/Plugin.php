<?php

namespace Jaxon\Dialogs\Libraries\PNotify;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
{
    public function getJs()
    {
        return '
<script type="text/javascript" src="https://lib.jaxon-php.org/pnotify/latest/pnotify.js"></script>
<script type="text/javascript" src="https://lib.jaxon-php.org/pnotify/latest/pnotify.confirm.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/pnotify/latest/pnotify.css" />';
    }

    public function getScript()
    {
        return '
PNotify.prototype.options.delay = 5000;' . $this->getOptionScript('PNotify.prototype.options.', 'options.') . '
jaxon.command.handler.register("pnotify.alert", function(args) {
    notice = new PNotify(args.data);
    notice.get().click(function(){notice.remove();});
});
jaxon.pnotify = {
    confirm: function(question, callback){
        notice = new PNotify({
            text: question,
            hide: false,
            confirm:{
                confirm: true
            },
            buttons:{
                closer: false,
                sticker: false
            }
        });
        notice.get().on("pnotify.confirm", callback);
    }
};
';
    }

    protected function alert($message, $title, $type)
    {
        $options = array('text' => $message, 'title' => $title, 'type' => $type);
        // Show the alert
        $this->addCommand(array('cmd' => 'pnotify.alert'), $options);
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
        $this->alert($message, $title, 'notice');
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
        return "jaxon.pnotify.confirm($question,function(){" . $script . ";})";
    }
}
