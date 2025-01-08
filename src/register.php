<?php

namespace Jaxon\Dialogs;

use Jaxon\Dialogs\Bootbox\BootboxLibrary;
use Jaxon\Dialogs\Bootstrap\BootstrapLibrary;
use Jaxon\Dialogs\Toastr\ToastrLibrary;
use Jaxon\Dialogs\JAlert\JAlertLibrary;
use Jaxon\Dialogs\Tingle\TingleLibrary;
use Jaxon\Dialogs\Noty\NotyLibrary;
use Jaxon\Dialogs\Notify\NotifyLibrary;
use Jaxon\Dialogs\SweetAlert\SweetAlertLibrary;
use Jaxon\Dialogs\JQueryConfirm\JQueryConfirmLibrary;
use Jaxon\Dialogs\CuteAlert\CuteAlertLibrary;
use Jaxon\Exception\SetupException;

use function Jaxon\jaxon;
use function php_sapi_name;

/**
 * @return void
 */
function register()
{
    // Do nothing if running in cli.
    if(php_sapi_name() === 'cli')
    {
        return;
    };

    $aLibraries = [
        Library\Alertify::class,
        BootboxLibrary::class, // Bootbox
        BootstrapLibrary::class, // Bootstrap
        ToastrLibrary::class, // Toastr
        JAlertLibrary::class, // JAlert
        TingleLibrary::class, // Tingle
        NotyLibrary::class, // Noty
        NotifyLibrary::class, // Notify
        SweetAlertLibrary::class, // SweetAlert
        JQueryConfirmLibrary::class, // JQuery Confirm
        CuteAlertLibrary::class, // CuteAlert
    ];
    $jaxon = jaxon();
    $xDialog = $jaxon->dialog();
    foreach($aLibraries as $sClass)
    {
        try
        {
            $xDialog->registerLibrary($sClass, $sClass::NAME);
        }
        catch(SetupException $_){}
    }

    // Register the template dir into the template renderer
    $jaxon->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/lib');
}

register();
