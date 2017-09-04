<?php

/**
 * Modal.php - Adapter for the IziModal library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Izi;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Modal extends Library implements Modal
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('izi-modal', '1.4.2');
    }
    
    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('iziModal.min.js');
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
        return $this->getCssCode('iziModal.min.css');
    }
    
    /**
     * Get the modal container in the DOM
     *
     * @return string
     */
    protected function getContainer()
    {
        $sContainer = 'izimodal-container';
        if($this->hasOption('dom.container'))
        {
            $sContainer = $this->getOption('dom.container');
        }
        return $sContainer;
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
        // Modal container
        $sContainer = $this->getContainer();
        return '
if(!$("#' . $sContainer . '").length)
{
    $(\'body\').append(\'<div id="' . $sContainer . '"></div>\');
}
jaxon.command.handler.register("izimodal.show", function(args) {
    $("' . $sContainer . '").iziModal(args.data);
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
        // Set the default dialog width
        if(!array_key_exists('width', $options))
        {
            $options['width'] = 600;
        }
        // Dialog title
        $options['title'] = $title;
        // Dialog container
        $sContainer = $this->getContainer();
        // Assign dialog content
        $this->response()->assign($sContainer, 'innerHTML', $content);
        $this->addCommand(array('cmd' => 'izimodal.show'), $options);
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
        $this->addCommand(array('cmd' => 'izimodal.hide'), []);
    }
}
