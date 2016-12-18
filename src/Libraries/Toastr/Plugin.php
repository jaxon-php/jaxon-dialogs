<?php

namespace Jaxon\Dialogs\Libraries\Toastr;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert
{
    public function getJs()
    {
        return '<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css">';
    }

    public function getScript()
    {
        $aOptions = $this->getOptionNames('options.');
        $sScript = '';
        foreach($aOptions as $name)
        {
            $value = $this->getOption($name);
            if(is_string($value))
            {
                $value = "'$value'";
            }
            else if(is_bool($value))
            {
                $value = ($value ? 'true' : 'false');
            }
            else if(!is_numeric($value))
            {
                $value = print_r($value, true);
            }
            $sScript .= "$name = $value;\n";
        }
        return $sScript .  '
jaxon.command.handler.register("toastr.info", function(args) {
    if((args.data.title))
        toastr.info(args.data.message, args.data.title);
    else
        toastr.info(args.data.message);
});
jaxon.command.handler.register("toastr.success", function(args) {
    if((args.data.title))
        toastr.success(args.data.message, args.data.title);
    else
        toastr.success(args.data.message);
});
jaxon.command.handler.register("toastr.warning", function(args) {
    if((args.data.title))
        toastr.warning(args.data.message, args.data.title);
    else
        toastr.warning(args.data.message);
});
jaxon.command.handler.register("toastr.error", function(args) {
    if((args.data.title))
        toastr.error(args.data.message, args.data.title);
    else
        toastr.error(args.data.message);
});';
    }

    public function info($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.info'), array('message' => $message, 'title' => $title));
    }

    public function success($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.success'), array('message' => $message, 'title' => $title));
    }

    public function warning($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.warning'), array('message' => $message, 'title' => $title));
    }

    public function error($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.error'), array('message' => $message, 'title' => $title));
    }

    public function remove()
    {
        $this->response()->script('toastr.remove()');
    }

    public function clear()
    {
        $this->response()->script('toastr.clear()');
    }
}

?>