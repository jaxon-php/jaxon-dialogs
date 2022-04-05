<?php

/**
 * PluginInterface.php - Adapter for the Tingle library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Tingle;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\ModalInterface;

class Plugin extends Library implements ModalInterface
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('tingle', '0.8.4');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('tingle.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('tingle.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('tingle/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('tingle/ready.js');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Show the footer only if there is a button to display.
        $aOptions['footer'] = (count($aButtons) > 0);
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'tingle.show'),
            array('content' => '<h2>' . $sTitle . '</h2>' . $sContent, 'buttons' => $aButtons, 'options' => $aOptions));
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'tingle.hide'), array());
    }
}
