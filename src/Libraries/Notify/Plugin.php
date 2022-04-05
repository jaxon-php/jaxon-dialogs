<?php

/**
 * PluginInterface.php - Adapter for the Notify library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Notify;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\LibraryTrait;

class Plugin extends Library implements MessageInterface
{
    use LibraryTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('notify', '0.4.2');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('notify.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('notify/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('notify/ready.js.php');
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sTitle The title of the message
     * @param string $sClass The type of the message
     *
     * @return string
     */
    protected function alert(string $sMessage, string $sTitle, string $sClass): string
    {
        if($this->getReturn())
        {
            return "$.notify(" . $sMessage . ", {className:'" . $sClass . "', position:'top center'})";
        }
        $aOptions = array('message' => $sMessage, 'className' => $sClass);
        // Show the alert
        $this->addCommand(array('cmd' => 'notify.alert'), $aOptions);
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'warn');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'error');
    }
}
