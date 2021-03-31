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
use Jaxon\Dialogs\Contracts\Modal as ModalContract;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Modal extends Library implements ModalContract
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('izi-modal', '1.4.2');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('iziModal.min.js');
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('izi/modal.js', ['container' => $this->getContainer()]);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function hide()
    {
        $this->addCommand(array('cmd' => 'izimodal.hide'), []);
    }
}
