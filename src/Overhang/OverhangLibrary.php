<?php

/**
 * OverhangLibrary.php
 *
 * Adapter for the Overhang library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Overhang;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class OverhangLibrary implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

    /**
     * @const The library name
     */
    const NAME = 'overhang';

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
        return 'overhang';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return 'latest';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('overhang.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('overhang.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('overhang/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('overhang/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sStdType)
    {
        $aTypes = [
            'warning' => 'warn',
        ];
        $sType = $aTypes[$sStdType] ?? $sStdType;
        $this->addCommand('overhang.alert', ['message' => $sMessage, 'type' => $sType]);
    }
}
