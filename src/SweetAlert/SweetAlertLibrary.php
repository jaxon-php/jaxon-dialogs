<?php

/**
 * SweetAlertLibrary.php
 *
 * Adapter for the SweetAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\SweetAlert;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class SweetAlertLibrary implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

    /**
     * @const The library name
     */
    const NAME = 'sweetalert';

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
        return 'sweetalert';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '1.1.1';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('sweetalert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('sweetalert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('sweetalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('sweetalert/ready.js.php', [
            'options' =>  $this->helper()->getOptionScript('jaxon.dialogs.swal.options.', 'options.')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sType)
    {
        $aOptions = ['text' => $sMessage, 'title' => '', 'type' => $sType];
        if(($sTitle))
        {
            $aOptions['title'] = $sTitle;
        }
        // Show the alert
        $this->addCommand('sweetalert.alert', $aOptions);
    }
}
