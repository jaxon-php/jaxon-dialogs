<?php

/**
 * PgwJsLibrary.php
 *
 * Adapter for the PgwJs ModalInterface library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\PgwJs;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\ModalInterface;

class PgwJsLibrary implements ModalInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'pgwjs';

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
        return 'pgwjs/modal';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '2.0.0';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('pgwmodal.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('pgwmodal.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('pgwjs/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        $sVarPrefix = 'jaxon.dialogs.pgwjs.options.';
        return $this->helper()->render('pgwjs/ready.js.php', [
            'options' => $this->helper()->getOptionScript($sVarPrefix, 'options.modal.'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Set the value of the max width, if there is no value defined
        $aOptions['title'] = $sTitle;
        $aOptions['content'] = $this->helper()->render('pgwjs/dialog.html',
            ['content' => $sContent, 'buttons' => $aButtons]);
        // Affectations du contenu de la fenêtre
        $this->addCommand(array('cmd'=>'pgw.modal'), $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->response()->script('$.pgwModal("close")');
    }
}
