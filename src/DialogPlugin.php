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

use Jaxon\App\Config\ConfigListenerInterface;
use Jaxon\App\Config\ConfigManager;
use Jaxon\App\Dialog\Library\AlertInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;
use Jaxon\App\Dialog\Manager\LibraryRegistryInterface;
use Jaxon\App\Dialog\Library\ModalInterface;
use Jaxon\App\I18n\Translator;
use Jaxon\Config\Config;
use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\Dialogs\Dialog\LibraryHelper;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Di\Container;
use Jaxon\Exception\SetupException;
use Jaxon\Plugin\AbstractPlugin;
use Jaxon\Plugin\CssCode;
use Jaxon\Plugin\CssCodeGeneratorInterface;
use Jaxon\Plugin\JsCode;
use Jaxon\Plugin\JsCodeGeneratorInterface;
use Jaxon\Utils\Template\TemplateEngine;

use function array_map;
use function class_implements;
use function count;
use function implode;
use function in_array;
use function is_string;

class DialogPlugin extends AbstractPlugin implements ConfigListenerInterface,
    LibraryRegistryInterface, CssCodeGeneratorInterface, JsCodeGeneratorInterface
{
    /**
     * @var string The plugin name
     */
    public const NAME = 'dialog_code';

    /**
     * @var array
     */
    protected $aLibraries = [];

    /**
     * @var array|null
     */
    protected $aActiveLibraries = null;

    /**
     * @var array
     */
    protected $aDefaultLibraries = [];

    /**
     * @var Config|null
     */
    protected $xConfig = null;

    /**
     * @var bool
     */
    protected $bConfigProcessed = false;

    /**
     * The constructor
     *
     * @param Container $di
     * @param Translator $xTranslator
     * @param ConfigManager $xConfigManager
     * @param TemplateEngine $xTemplateEngine
     */
    public function __construct(private Container $di, private Translator $xTranslator,
        private ConfigManager $xConfigManager, private TemplateEngine $xTemplateEngine)
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
     * @return Config
     */
    public function config(): Config
    {
        return $this->xConfig ??= $this->xConfigManager->getConfig('dialogs');
    }

    /**
     * Register a javascript dialog library adapter.
     *
     * @param string $sClass
     * @param string $sLibraryName
     *
     * @return void
     */
    private function setLibraryInContainer(string $sClass, string $sLibraryName): void
    {
        if(!$this->di->h($sClass))
        {
            $this->di->set($sClass, fn($di) => $di->make($sClass));
        }
        // Set the alias, so the libraries can be found by their names.
        $this->di->alias("dialog_library_$sLibraryName", $sClass);
        // Same for the helper.
        $this->di->set("dialog_library_helper_$sLibraryName", fn($di) =>
            new LibraryHelper($di->g($sClass), $this));
    }

    /**
     * @param string $sClassName
     *
     * @return array{alert: bool, confirm: bool, modal: bool}
     * @throws SetupException
     */
    private function getLibraryTypes(string $sClassName): array
    {
        $aInterfaces = class_implements($sClassName);
        $bIsConfirm = in_array(ConfirmInterface::class, $aInterfaces);
        $bIsAlert = in_array(AlertInterface::class, $aInterfaces);
        $bIsModal = in_array(ModalInterface::class, $aInterfaces);
        if(!$bIsConfirm && !$bIsAlert && !$bIsModal)
        {
            // The class is invalid.
            $sMessage = $this->xTranslator->trans('errors.register.invalid', [
                'name' => $sClassName,
            ]);
            throw new SetupException($sMessage);
        }

        return [
            'confirm' => $bIsConfirm,
            'alert' => $bIsAlert,
            'modal' => $bIsModal,
        ];
    }

    /**
     * Register a javascript dialog library adapter.
     *
     * @param string $sClassName
     * @param string $sLibraryName
     *
     * @return void
     * @throws SetupException
     */
    public function registerLibrary(string $sClassName, string $sLibraryName): void
    {
        if(isset($this->aLibraries[$sLibraryName]))
        {
            return;
        }

        // Save the library
        $this->aLibraries[$sLibraryName] = [
            'name' => $sLibraryName,
            'active' => false,
            ...$this->getLibraryTypes($sClassName),
        ];

        // Register the library class in the container
        $this->setLibraryInContainer($sClassName, $sLibraryName);
    }

    /**
     * Get the dialog library
     *
     * @param string $sLibraryName
     *
     * @return AbstractLibrary|null
     */
    private function getLibrary(string $sLibraryName): AbstractLibrary|null
    {
        $sKey = "dialog_library_$sLibraryName";
        return $this->di->h($sKey) ? $this->di->g($sKey) : null;
    }

    /**
     * Get the dialog library helper
     *
     * @param string $sLibraryName
     *
     * @return LibraryHelper
     */
    public function getLibraryHelper(string $sLibraryName): LibraryHelper
    {
        return $this->di->g("dialog_library_helper_$sLibraryName");
    }

    /**
     * @param string $sLibraryName
     *
     * @return string
     */

    public function renderLibraryScript(string $sLibraryName): string
    {
        return $this->xTemplateEngine->render("jaxon::dialogs::{$sLibraryName}.js");
    }

    /**
     * @param string $sType
     *
     * @return AbstractLibrary|null
     */
    private function getDefaultLibrary(string $sType): AbstractLibrary|null
    {
        return $this->getLibrary($this->aDefaultLibraries[$sType] ?? '');
    }

    /**
     * Register the javascript dialog libraries from config options.
     *
     * @return void
     * @throws SetupException
     */
    private function processLibraryConfig(): void
    {
        if($this->bConfigProcessed)
        {
            return;
        }

        // Register the 3rd party libraries
        $aLibraries = $this->config()->getOption('lib.ext', []);
        foreach($aLibraries as $sLibraryName => $sClassName)
        {
            $this->registerLibrary($sClassName, $sLibraryName);
        }

        // Set the other libraries in use
        $aLibraries = $this->config()->getOption('lib.use', []);
        foreach($aLibraries as $sLibraryName)
        {
            if(isset($this->aLibraries[$sLibraryName])) // Make sure the library exists
            {
                $this->aLibraries[$sLibraryName]['active'] = true;
            }
        }

        // Set the default alert, modal and confirm libraries.
        foreach(['alert', 'modal', 'confirm'] as $sType)
        {
            $sLibraryName = trim($this->config()->getOption("default.$sType", ''));
            if(!is_string($sLibraryName) || $sLibraryName === '')
            {
                continue;
            }

            if(!($this->aLibraries[$sLibraryName][$sType] ?? false))
            {
                $sMessage = $this->xTranslator->trans('errors.dialog.library', [
                    'type' => $sType,
                    'name' => $sLibraryName,
                ]);
                throw new SetupException($sMessage);
            }

            $this->aLibraries[$sLibraryName]['active'] = true;
            $this->aDefaultLibraries[$sType] = $sLibraryName;
        }

        $this->bConfigProcessed = true;
    }

    /**
     * @inheritDoc
     */
    public function getConfirmLibrary(): ConfirmInterface
    {
        $this->processLibraryConfig();

        return $this->getDefaultLibrary('confirm') ?? $this->di->g(Alert::class);
    }

    /**
     * @inheritDoc
     */
    public function getAlertLibrary(): AlertInterface
    {
        $this->processLibraryConfig();

        return $this->getDefaultLibrary('alert') ?? $this->di->g(Alert::class);
    }

    /**
     * @inheritDoc
     */
    public function getModalLibrary(): ?ModalInterface
    {
        $this->processLibraryConfig();

        return $this->getDefaultLibrary('modal');
    }

    /**
     * @return array<AbstractLibrary>
     */
    private function getActiveLibraries(): array
    {
        if($this->aActiveLibraries !== null)
        {
            return $this->aActiveLibraries;
        }

        $this->processLibraryConfig();

        // Set the active libraries.
        $cFilter = fn(array $aLibrary) => $aLibrary['active'];
        $cGetter = fn(array $aLibrary) => $this->getLibrary($aLibrary['name']);
        $aLibraries = array_filter($this->aLibraries, $cFilter);
        return $this->aActiveLibraries = array_map($cGetter, $aLibraries);
    }

    /**
     * @return string
     */
    private function getConfigScript(): string
    {
        $aOptions = [
            'labels' => $this->xTranslator->translations('labels'),
            'defaults' => $this->config()->getOption('default', []),
        ];
        $aLibrariesOptions = [];
        foreach($this->getActiveLibraries() as $xLibrary)
        {
            $aLibOptions = $xLibrary->getJsOptions();
            if(count($aLibOptions) > 0)
            {
                $aLibrariesOptions[$xLibrary->getName()] = $aLibOptions;
            }
        }
        if(count($aLibrariesOptions) > 0)
        {
            $aOptions['options'] = $aLibrariesOptions;
        }

        return 'jaxon.dom.ready(() => jaxon.dialog.config(' . json_encode($aOptions) . '));';
    }

    /**
     * @inheritDoc
     */
    public function getCssCode(): CssCode
    {
        $aUrls = [];
        $aCodes = [];
        foreach($this->getActiveLibraries() as $xLibrary)
        {
            $aUrls = [...$aUrls, ...$xLibrary->getCssUrls()];
            if(($sCode = $xLibrary->getCssCode()) !== '')
            {
                $aCodes[] = $sCode;
            }
        }

        return new CssCode(sCode: implode("\n", $aCodes), aUrls: $aUrls);
    }

    /**
     * @inheritDoc
     */
    public function getJsCode(): JsCode
    {
        $sCodeBefore = $this->getConfigScript();
        $aUrls = [];
        $aCodes = [];
        foreach($this->getActiveLibraries() as $xLibrary)
        {
            $aUrls = [...$aUrls, ...$xLibrary->getJsUrls()];
            if(($sCode = $xLibrary->getJsCode()) !== '')
            {
                $aCodes[] = $sCode;
            }
        }

        return new JsCode(sCode: implode("\n", $aCodes), aUrls: $aUrls, sCodeBefore: $sCodeBefore);
    }

    /**
     * @inheritDoc
     */
    public function onChange(Config $xConfig, string $sName): void
    {
        // Reset all the config related data on config change.
        $this->xConfig = null;
        $this->aActiveLibraries = null;
        $this->aDefaultLibraries = [];
        $this->bConfigProcessed = false;
    }
}
