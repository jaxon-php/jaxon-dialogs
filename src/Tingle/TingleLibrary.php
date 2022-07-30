<?php

/**
 * TingleLibrary.php
 *
 * Adapter for the Tingle library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Tingle;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\ModalInterface;

class TingleLibrary implements ModalInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'tingle';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getSubdir(): string
    {
        return 'tingle';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '0.8.4';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('tingle.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('tingle.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('tingle/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('tingle/ready.js');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Show the footer only if there is a button to display.
        $aOptions['footer'] = (count($aButtons) > 0);
        // Show the modal dialog
        $this->addCommand(['cmd' => 'tingle.show'],
            ['content' => '<h2>' . $sTitle . '</h2>' . $sContent, 'buttons' => $aButtons, 'options' => $aOptions]);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(['cmd' => 'tingle.hide'], []);
    }
}
