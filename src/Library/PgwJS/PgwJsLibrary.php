<?php

/**
 * DialogLibraryInterface.php - Adapter for the PgwJS ModalInterface library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\PgwJS;

use Jaxon\Ui\Dialog\Library\AbstractDialogLibrary;
use Jaxon\Ui\Dialog\ModalInterface;

class PgwJsLibrary extends AbstractDialogLibrary implements ModalInterface
{
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
        return $this->xHelper->getJsCode('pgwmodal.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->xHelper->getCssCode('pgwmodal.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->xHelper->render('pgwjs/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        $sVarPrefix = 'jaxon.dialogs.pgwjs.options.';
        return $this->xHelper->render('pgwjs/ready.js.php', [
            'options' => $this->xHelper->getOptionScript($sVarPrefix, 'options.modal.'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Set the value of the max width, if there is no value defined
        $aOptions['title'] = $sTitle;
        $aOptions['content'] = $this->xHelper->render('pgwjs/dialog.html',
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
