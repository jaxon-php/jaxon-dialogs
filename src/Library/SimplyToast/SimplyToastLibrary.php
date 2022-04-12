<?php

/**
 * DialogLibraryInterface.php - Adapter for the SimplyToast library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\SimplyToast;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\LibraryInterface;
use Jaxon\App\Dialog\MessageInterface;

class SimplyToastLibrary implements LibraryInterface, MessageInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'simply';

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
        return 'simply-toast';
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
        return $this->helper()->getJsCode('simply-toast.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('simply-toast.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('simplytoast/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('simplytoast/ready.js.php', [
            'options' => json_encode($this->helper()->getOptionNames('options.'))
        ]);
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sType The type of the message
     *
     * @return string
     */
    private function alert(string $sMessage, string $sType): string
    {
        if($this->returnCode())
        {
            return "$.simplyToast(" . $sMessage . ", '" . $sType . "')";
        }
        $this->addCommand(['cmd' => 'simply.alert'], ['message' => $sMessage, 'type' => $sType]);
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, 'danger');
    }
}
