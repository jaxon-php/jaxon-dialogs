<?php

namespace Jaxon\Dialogs\Libraries\Tingle;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/tingle/0.8.4/tingle.min.js"></script>';
    }

    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/tingle/0.8.4/tingle.min.css" />';
    }

    public function getScript()
    {
        return '
var tingleModal = null;
jaxon.command.handler.register("tingle.show", function(args) {
    if(tingleModal != null)
    {
        tingleModal.close();
    }
    tingleModal = new tingle.modal(args.data.options);
    // Set content
    tingleModal.setContent(args.data.content);
    // Add buttons
    for(var ind = 0, len = args.data.buttons.length; ind < len; ind++)
    {
        button = args.data.buttons[ind];
        if(button.click == "close")
        {
            button.click = function(){tingleModal.close();};
        }
        else
        {
            button.click = new Function(button.click);
        }
        tingleModal.addFooterBtn(button.title, button.class, button.click);
    }
    // Open modal
    tingleModal.open();
});
jaxon.command.handler.register("tingle.hide", function(args) {
    if(tingleModal != null)
    {
        // Close an destroy modal
        tingleModal.close();
        tingleModal.destroy();
        tingleModal = null;
    }
});';
    }

    public function show($title, $content, array $buttons, array $options = array())
    {
        // Show the footer only if there is a button to display.
        $options['footer'] = (count($buttons) > 0);
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'tingle.show'),
            array('content' => '<h2>' . $title . '</h2>' . $content, 'buttons' => $buttons, 'options' => $options));
    }

    public function hide()
    {
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'tingle.hide'));
    }
}
