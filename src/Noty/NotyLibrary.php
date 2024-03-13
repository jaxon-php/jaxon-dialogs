<?php

/**
 * NotyLibrary.php
 *
 * Adapter for the Noty library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Noty;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\Library\QuestionTrait;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class NotyLibrary implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;
    use QuestionTrait;

    /**
     * @const The library name
     */
    const NAME = 'noty';

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
        return 'noty';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '2.3.11';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('jquery.noty.packaged.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
         return $this->helper()->render('noty/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
         return $this->helper()->render('noty/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sStdType)
    {
        $aTypes = [
            'info' => 'information',
        ];
        $sType = $aTypes[$sStdType] ?? $sStdType;
        $this->addCommand('noty.alert', ['text' => $sMessage, 'type' => $sType]);
    }
}
