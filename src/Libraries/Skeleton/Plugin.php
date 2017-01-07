<?php

namespace Jaxon\Dialogs\Libraries\Skeleton;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    public function getCss()
    {
        return '';
    }

    public function getJs()
    {
        return '';
    }

    public function getScript()
    {
        return '';
    }

    public function show($title, $content, array $buttons, array $options = array())
    {}

    public function hide()
    {}

    public function success($message, $title = null)
    {}

    public function info($message, $title = null)
    {}

    public function warning($message, $title = null)
    {}

    public function error($message, $title = null)
    {}

    /**
     * Get the script which makes a call only if the user answers yes to the given question
     * 
     * This is the implementation of the Jaxon\Request\Interfaces\Confirm interface.
     * 
     * @return string
     */
    public function confirm($question, $script)
    {}
}
