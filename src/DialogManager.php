<?php

/**
 * DialogManager.php
 *
 * Manage dialog library list and defaults.
 *
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2019 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Dialogs;

use Jaxon\App\Config\ConfigListenerInterface;
use Jaxon\App\Config\ConfigManager;
use Jaxon\App\Dialog\Library\AlertInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;
use Jaxon\App\Dialog\Library\LibraryInterface;
use Jaxon\App\Dialog\Library\ModalInterface;
use Jaxon\App\Dialog\Manager\LibraryRegistryInterface;
use Jaxon\App\I18n\Translator;
use Jaxon\Config\Config;
use Jaxon\Dialogs\Dialog\LibraryHelper;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Di\Container;
use Jaxon\Exception\SetupException;
use Jaxon\Utils\Template\TemplateEngine;

use function array_map;
use function array_keys;
use function class_implements;
use function in_array;
use function substr;

class DialogManager implements ConfigListenerInterface, LibraryRegistryInterface
{
    /**
     * @var array
     */
    protected $aLibraries = [];

    /**
     * The ConfirmInterface class name
     *
     * @var string
     */
    private $sConfirmLibrary = '';

    /**
     * The AlertInterface class name
     *
     * @var string
     */
    private $sAlertLibrary = '';

    /**
     * The ModalInterface class name
     *
     * @var string
     */
    private $sModalLibrary = '';

    /**
     * The constructor
     *
     * @param Container $di
     * @param ConfigManager $xConfigManager
     * @param Translator $xTranslator
     */
    public function __construct(private Container $di,
        private ConfigManager $xConfigManager, private Translator $xTranslator)
    {}

    /**
     * Register a javascript dialog library adapter.
     *
     * @param string $sClass
     * @param string $sLibraryName
     *
     * @return void
     */
    private function _registerLibrary(string $sClass, string $sLibraryName)
    {
        $this->di->set($sClass, function($di) use($sClass) {
            return $di->make($sClass);
        });
        $this->di->set("dialog_library_helper_$sLibraryName", function($di) use($sClass) {
            return new LibraryHelper($di->g($sClass), $di->g(ConfigManager::class),
                $di->g(TemplateEngine::class));
        });
        // Set the alias, so the libraries can be found by their names.
        $this->di->alias("dialog_library_$sLibraryName", $sClass);
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
    public function registerLibrary(string $sClassName, string $sLibraryName)
    {
        if(isset($this->aLibraries[$sLibraryName]))
        {
            return;
        }
        if(!($aInterfaces = class_implements($sClassName)))
        {
            // The class is invalid.
            $sMessage = $this->xTranslator->trans('errors.register.invalid', ['name' => $sClassName]);
            throw new SetupException($sMessage);
        }

        $bIsConfirm = in_array(ConfirmInterface::class, $aInterfaces);
        $bIsAlert = in_array(AlertInterface::class, $aInterfaces);
        $bIsModal = in_array(ModalInterface::class, $aInterfaces);
        if(!$bIsConfirm && !$bIsAlert && !$bIsModal)
        {
            // The class is invalid.
            $sMessage = $this->xTranslator->trans('errors.register.invalid', ['name' => $sClassName]);
            throw new SetupException($sMessage);
        }

        // Save the library
        $this->aLibraries[$sLibraryName] = [
            'confirm' => $bIsConfirm,
            'alert' => $bIsAlert,
            'modal' => $bIsModal,
            'used' => false,
        ];
        // Register the library class in the container
        $this->_registerLibrary($sClassName, $sLibraryName);
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
     * Set the ConfirmInterface library
     *
     * @param string $sLibraryName The ConfirmInterface library name
     *
     * @return void
     * @throws SetupException
     */
    public function setConfirmLibrary(string $sLibraryName)
    {
        if(!isset($this->aLibraries[$sLibraryName]) || !$this->aLibraries[$sLibraryName]['confirm'])
        {
            $sMessage = $this->xTranslator->trans('errors.dialog.library',
                ['type' => 'confirm', 'name' => $sLibraryName]);
            throw new SetupException($sMessage);
        }
        $this->sConfirmLibrary = $sLibraryName;
    }

    /**
     * Get the ConfirmInterface library
     *
     * @return ConfirmInterface
     */
    public function getConfirmLibrary(): ConfirmInterface
    {
        $sKey = "dialog_library_{$this->sConfirmLibrary}";
        return $this->di->h($sKey) ? $this->di->g($sKey) : $this->di->g(Alert::class);
    }

    /**
     * Set AlertInterface library
     *
     * @param string $sLibraryName The AlertInterface library name
     *
     * @return void
     * @throws SetupException
     */
    public function setAlertLibrary(string $sLibraryName)
    {
        if(!isset($this->aLibraries[$sLibraryName]) || !$this->aLibraries[$sLibraryName]['alert'])
        {
            $sMessage = $this->xTranslator->trans('errors.dialog.library',
                ['type' => 'alert', 'name' => $sLibraryName]);
            throw new SetupException($sMessage);
        }
        $this->sAlertLibrary = $sLibraryName;
    }

    /**
     * Get the AlertInterface library
     *
     * @return AlertInterface
     */
    public function getAlertLibrary(): AlertInterface
    {
        $sKey = "dialog_library_{$this->sAlertLibrary}";
        return $this->di->h($sKey) ? $this->di->g($sKey) : $this->di->g(Alert::class);
    }

    /**
     * Set the ModalInterface library
     *
     * @param string $sLibraryName The ModalInterface library name
     *
     * @return void
     * @throws SetupException
     */
    public function setModalLibrary(string $sLibraryName)
    {
        if(!isset($this->aLibraries[$sLibraryName]) || !$this->aLibraries[$sLibraryName]['modal'])
        {
            $sMessage = $this->xTranslator->trans('errors.dialog.library',
                ['type' => 'modal', 'name' => $sLibraryName]);
            throw new SetupException($sMessage);
        }
        $this->sModalLibrary = $sLibraryName;
    }

    /**
     * Get the ModalInterface library
     *
     * @return ModalInterface|null
     */
    public function getModalLibrary(): ?ModalInterface
    {
        $sKey = "dialog_library_{$this->sModalLibrary}";
        return $this->di->h($sKey) ? $this->di->g($sKey) : null;
    }

    /**
     * Register the javascript dialog libraries from config options.
     *
     * @return void
     * @throws SetupException
     */
    protected function registerLibraries()
    {
        $aLibraries = $this->xConfigManager->getAppOption('dialogs.lib.ext', []);
        foreach($aLibraries as $sLibraryName => $sClassName)
        {
            $this->registerLibrary($sClassName, $sLibraryName);
        }
    }

    /**
     * Set the default library for each dialog feature.
     *
     * @return void
     * @throws SetupException
     */
    protected function setDefaultLibraries()
    {
        // Set the default modal library
        if(($sLibraryName = $this->xConfigManager->getAppOption('dialogs.default.modal', '')))
        {
            $this->setModalLibrary($sLibraryName);
            $this->aLibraries[$sLibraryName]['used'] = true;
        }
        // Set the default alert library
        if(($sLibraryName = $this->xConfigManager->getAppOption('dialogs.default.alert', '')))
        {
            $this->setAlertLibrary($sLibraryName);
            $this->aLibraries[$sLibraryName]['used'] = true;
        }
        // Set the default confirm library
        if(($sLibraryName = $this->xConfigManager->getAppOption('dialogs.default.confirm', '')))
        {
            $this->setConfirmLibrary($sLibraryName);
            $this->aLibraries[$sLibraryName]['used'] = true;
        }
    }

    /**
     * Set the libraries in use.
     *
     * @return void
     */
    protected function setUsedLibraries()
    {
        // Set the other libraries in use
        $aLibraries = $this->xConfigManager->getAppOption('dialogs.lib.use', []);
        foreach($aLibraries as $sLibraryName)
        {
            if(isset($this->aLibraries[$sLibraryName])) // Make sure the library exists
            {
                $this->aLibraries[$sLibraryName]['used'] = true;
            }
        }
    }

    /**
     * Get the dialog libraries class instances
     *
     * @return LibraryInterface[]
     */
    public function getLibraries(): array
    {
        // Reset the default libraries any time the config is changed.
        $this->registerLibraries();
        $this->setDefaultLibraries();
        $this->setUsedLibraries();

        // Only return the libraries that are used.
        $aLibraries = array_filter($this->aLibraries, function($aLibrary) {
            return $aLibrary['used'];
        });
        return array_map(function($sLibraryName) {
            return $this->di->g("dialog_library_$sLibraryName");
        }, array_keys($aLibraries));
    }

    /**
     * @inheritDoc
     * @throws SetupException
     */
    public function onChange(Config $xConfig, string $sName)
    {
        if($sName === '')
        {
            // Reset the default libraries any time the config is changed.
            $this->registerLibraries();
            $this->setDefaultLibraries();
            $this->setUsedLibraries();
            return;
        }
        $sPrefix = substr($sName, 0, 15);
        switch($sPrefix)
        {
        case 'dialogs.default':
            $this->setDefaultLibraries();
            return;
        case 'dialogs.lib.ext':
            $this->registerLibraries();
            return;
        case 'dialogs.lib.use':
            $this->setUsedLibraries();
            return;
        default:
        }
    }
}
