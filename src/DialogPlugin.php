<?php

/**
 * DialogPlugin.php - ModalInterface, message and question dialogs for Jaxon.
 *
 * Show modal, alert and confirm dialogs with various javascript libraries.
 * This class generates js ans css code for dialog libraries.
 *
 * @package jaxon-core
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Dialogs;

use Jaxon\Exception\SetupException;
use Jaxon\Plugin\AbstractPlugin;

use function array_reduce;
use function trim;

class DialogPlugin extends AbstractPlugin
{
    /**
     * @const The plugin name
     */
    const NAME = 'dialog_code';

    /**
     * @var array
     */
    protected $aLibraries = null;

    /**
     * The constructor
     *
     * @param DialogManager $xDialogManager
     */
    public function __construct(private DialogManager $xDialogManager)
    {}

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
    public function getHash(): string
    {
        // The version number is used as hash
        return '4.0.0';
    }

    public function getUri(): string
    {
        return '';
    }

    /**
     * @return array
     */
    private function getLibraries(): array
    {
        return $this->aLibraries ?: $this->aLibraries = $this->xDialogManager->getLibraries();
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return array_reduce($this->getLibraries(), function($sCode, $xLibrary) {
            return $sCode . $xLibrary->getJs() . "\n\n";
        }, '');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return array_reduce($this->getLibraries(), function($sCode, $xLibrary) {
            return $sCode . trim($xLibrary->getCss()) . "\n\n";
        }, '');
    }

    /**
     * @inheritDoc
     * @throws SetupException
     */
    public function getScript(): string
    {
        return array_reduce($this->getLibraries(), function($sCode, $xLibrary) {
            return $sCode . trim($xLibrary->getScript()) . "\n\n";
        }, '');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return array_reduce($this->getLibraries(), function($sCode, $xLibrary) {
            return $sCode . trim($xLibrary->getReadyScript()) . "\n\n";
        }, '');
    }
}
