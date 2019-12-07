<?php

/**
 * Plugin.php - Adapter for the PgwJS Modal library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\PgwJS;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;

class Plugin extends Library implements Modal
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
    public function getJs()
    {
        return $this->getJsCode('pgwmodal.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('pgwmodal.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('pgwjs/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('pgwjs/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.pgwjs.options.', 'options.modal.')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Set the value of the max width, if there is no value defined
        $options['title'] = (string)$title;
        $options['content'] = $this->render('pgwjs/dialog.html', compact('content', 'buttons'));
        // Affectations du contenu de la fenêtre
        $this->addCommand(array('cmd'=>'pgw.modal'), $options);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->response()->script('$.pgwModal("close")');
    }
}
