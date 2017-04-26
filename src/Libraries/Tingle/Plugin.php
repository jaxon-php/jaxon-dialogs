<?php

/**
 * Plugin.php - Adapter for the Tingle library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Tingle;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal
{
    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/tingle/0.8.4/tingle.min.js"></script>';
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/tingle/0.8.4/tingle.min.css" />';
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
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

    /**
     * Show a modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     * 
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Show the footer only if there is a button to display.
        $options['footer'] = (count($buttons) > 0);
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'tingle.show'),
            array('content' => '<h2>' . $title . '</h2>' . $content, 'buttons' => $buttons, 'options' => $options));
    }

    /**
     * Hide the modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @return void
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'tingle.hide'), array());
    }
}
