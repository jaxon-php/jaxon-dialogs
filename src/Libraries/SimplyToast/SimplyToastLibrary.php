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

namespace Jaxon\Dialogs\Libraries\SimplyToast;

use Jaxon\Dialogs\Libraries\AbstractDialogLibrary;
use Jaxon\Ui\Dialogs\MessageInterface;

class SimplyToastLibrary extends AbstractDialogLibrary implements MessageInterface
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('simply-toast', 'latest');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('simply-toast.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('simply-toast.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('simplytoast/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('simplytoast/ready.js.php', [
            'options' => json_encode($this->getOptionNames('options.'))
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
        if($this->getReturn())
        {
            return "$.simplyToast(" . $sMessage . ", '" . $sType . "')";
        }
        $this->addCommand(array('cmd' => 'simply.alert'), array('message' => $sMessage, 'type' => $sType));
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
