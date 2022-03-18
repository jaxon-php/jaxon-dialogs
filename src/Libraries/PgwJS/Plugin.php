<?php

/**
 * PluginInterface.php - Adapter for the PgwJS ModalInterface library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\PgwJS;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\ModalInterface;

class Plugin extends Library implements ModalInterface
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('pgwjs/modal', '2.0.0');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('pgwmodal.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('pgwmodal.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('pgwjs/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('pgwjs/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.pgwjs.options.', 'options.modal.')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Set the value of the max width, if there is no value defined
        $aOptions['title'] = (string)$sTitle;
        $aOptions['content'] = $this->render('pgwjs/dialog.html',
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
