<?php

/**
 * DialogPlugin.php - modal, alert and confirm dialogs for Jaxon.
 *
 * Show modal, alert and confirm dialogs with various javascript libraries.
 * This class generates js ans css code for dialog libraries.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Dialogs;

use Jaxon\App\Config\ConfigManager;
use Jaxon\App\I18n\Translator;
use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\Dialogs\Dialog\LibraryHelper;
use Jaxon\Exception\SetupException;
use Jaxon\Plugin\AbstractPlugin;
use Jaxon\Plugin\Code\JsCode;
use Closure;

use function array_filter;
use function array_map;
use function count;
use function implode;
use function json_encode;
use function trim;

class DialogPlugin extends AbstractPlugin
{
    /**
     * @const The plugin name
     */
    public const NAME = 'dialog_code';

    /**
     * @var array
     */
    protected $aLibraries = null;

    /**
     * The constructor
     *
     * @param ConfigManager $xConfigManager
     * @param Translator $xTranslator
     * @param DialogManager $xDialogManager
     */
    public function __construct(private ConfigManager $xConfigManager,
        private Translator $xTranslator, private DialogManager $xDialogManager)
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
        return '5.0.0';
    }

    /**
     * @return AbstractLibrary[]
     */
    private function getLibraries(): array
    {
        return $this->aLibraries ?: $this->aLibraries = $this->xDialogManager->getLibraries();
    }

    /**
     * @return LibraryHelper[]
     */
    private function getHelpers(): array
    {
        return array_map(fn($xLibrary) => $xLibrary->helper(), $this->getLibraries());
    }

    /**
     * @param array $aCodes
     *
     * @return string
     */
    private function getCode(array $aCodes): string
    {
        $aCodes = array_filter($aCodes, fn($sScript) => $sScript !== '');
        return implode("\n", $aCodes);
    }

    /**
     * @param Closure $fGetCode
     *
     * @return string
     */
    private function getLibCodes(Closure $fGetCode): string
    {
        return $this->getCode(array_map($fGetCode, $this->getLibraries()));
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getLibCodes(fn($xLibrary) => trim($xLibrary->getJs()));
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getLibCodes(fn($xLibrary) => trim($xLibrary->getCss()));
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->getLibCodes(fn($xLibrary) => trim($xLibrary->getScript()));
    }

    /**
     * @inheritDoc
     */
    private function getConfigScript(): string
    {
        $aOptions = [
            'labels' => $this->xTranslator->translations('labels'),
            'defaults' => $this->xConfigManager->getAppOption('dialogs.default', []),
        ];
        $aLibrariesOptions = [];
        foreach($this->getLibraries() as $xLibrary)
        {
            $aLibOptions = $xLibrary->helper()->getJsOptions();
            if(count($aLibOptions) > 0)
            {
                $aLibrariesOptions[$xLibrary->getName()] = $aLibOptions;
            }
        }
        if(count($aLibrariesOptions) > 0)
        {
            $aOptions['options'] = $aLibrariesOptions;
        }
        return "jaxon.dialog.config(" . json_encode($aOptions) . ");\n\n";
    }

    /**
     * @inheritDoc
     * @throws SetupException
     */
    public function getJsCode(): JsCode
    {
        $xJsCode = new JsCode();
        $xJsCode->sJsBefore = $this->getConfigScript();

        $aCodes = [];
        foreach($this->getHelpers() as $xHelper)
        {
            $aCodes[] = $xHelper->getScript();
            $xJsCode->aFiles = array_merge($xJsCode->aFiles, $xHelper->getFiles());
        }
        $xJsCode->sJs = $this->getCode($aCodes);

        return $xJsCode;
    }
}
