<?php

/**
 * ToastrLibrary.php
 *
 * Adapter for the Toastr library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Toastr;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\MessageInterface;

class ToastrLibrary implements MessageInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

    /**
     * @const The library name
     */
    const NAME = 'toastr';

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
        return 'toastr.js';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '2.1.3';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('toastr.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('toastr.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('toastr/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('toastr/ready.js.php', [
            'options' =>  $this->helper()->getOptionScript('toastr.options.', 'options.')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sType)
    {
        $this->addCommand('toastr.' . $sType, ['message' => $sMessage, 'title' => $sTitle]);
    }

    public function remove()
    {
        $this->addCommand('toastr.remove');
    }

    public function clear()
    {
        $this->addCommand('toastr.clear');
    }
}
