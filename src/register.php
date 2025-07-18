<?php

namespace Jaxon\Dialogs;

use Jaxon\App\Config\ConfigManager;
use Jaxon\App\Dialog\Manager\LibraryRegistryInterface;
use Jaxon\App\I18n\Translator;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Exception\SetupException;

use function Jaxon\jaxon;
use function php_sapi_name;

/**
 * Get the dialog library manager
 *
 * @return DialogManager
 */
function dialog(): DialogManager
{
    return jaxon()->di()->g(DialogManager::class);
}

/**
 * @return void
 */
function _register(): void
{
    $jaxon = jaxon();
    $xDi = $jaxon->di();

    // Dialog library manager
    $xDi->set(DialogManager::class, function($di) {
        // Register the template dir into the template renderer
        jaxon()->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/js');

        $xDialog = new DialogManager($di, $di->g(ConfigManager::class), $di->g(Translator::class));
        // Register the provided libraries.
        $aLibraries = [
            Dialog\Library\Alertify::class, // Alertify
            Dialog\Library\Bootbox::class, // Bootbox
            Dialog\Library\Bootstrap3::class, // Bootstrap 3
            Dialog\Library\Bootstrap4::class, // Bootstrap 4
            Dialog\Library\Bootstrap5::class, // Bootstrap 5
            Dialog\Library\Toastr::class, // Toastr
            Dialog\Library\JAlert::class, // JAlert
            Dialog\Library\Tingle::class, // Tingle
            Dialog\Library\Noty::class, // Noty
            Dialog\Library\Notify::class, // Notify
            Dialog\Library\SweetAlert::class, // SweetAlert
            Dialog\Library\JQueryConfirm::class, // JQuery Confirm
            Dialog\Library\CuteAlert::class, // CuteAlert
            Dialog\Library\Notyf::class, // Notyf
            Dialog\Library\Quantum::class, // QuantumAlert
            Dialog\Library\Butterup::class, // Butterup
            Dialog\Library\IziToast::class, // IziToast
        ];
        foreach($aLibraries as $sClass)
        {
            try
            {
                $xDialog->registerLibrary($sClass, $sClass::NAME);
            }
            catch(SetupException $_){}
        }

        return $xDialog;
    });

    $xDi->alias(LibraryRegistryInterface::class, DialogManager::class);
    $xDi->set(Alert::class, fn() => new Alert());

    // Listener for app config changes.
    $jaxon->config()->addAppEventListener(DialogManager::class);
    // Register the plugin
    $jaxon->registerPlugin(DialogPlugin::class, DialogPlugin::NAME, 900);
}

function register(): void
{
    // Do nothing if running in cli.
    if(php_sapi_name() !== 'cli')
    {
        _register();
    };
}

register();
