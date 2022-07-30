<?php

namespace Jaxon\Dialogs;

use Jaxon\Exception\SetupException;

/**
 * @return void
 */
function registerDialogLibraries()
{
    $aLibraries = [
        Jaxon\Dialogs\Bootbox\BootboxLibrary::class, // Bootbox
        Jaxon\Dialogs\Bootstrap\BootstrapLibrary::class, // Bootstrap
        Jaxon\Dialogs\PgwJs\PgwJsLibrary::class, // PgwJs
        Jaxon\Dialogs\Toastr\ToastrLibrary::class, // Toastr
        Jaxon\Dialogs\JAlert\JAlertLibrary::class, // JAlert
        Jaxon\Dialogs\Tingle\TingleLibrary::class, // Tingle
        Jaxon\Dialogs\Noty\NotyLibrary::class, // Noty
        Jaxon\Dialogs\Notify\NotifyLibrary::class, // Notify
        Jaxon\Dialogs\Overhang\OverhangLibrary::class, // Overhang
        Jaxon\Dialogs\PNotify\PNotifyLibrary::class, // PNotify
        Jaxon\Dialogs\SweetAlert\SweetAlertLibrary::class, // SweetAlert
        Jaxon\Dialogs\JQueryConfirm\JQueryConfirmLibrary::class, // JQuery Confirm
        Jaxon\Dialogs\XDialog\XDialogLibrary::class, // XDialog
        Jaxon\Dialogs\CuteAlert\CuteAlertLibrary::class, // CuteAlert
    ];
    $jaxon = jaxon();
    foreach($aLibraries as $sClass)
    {
        try
        {
            $jaxon->dialog()->registerLibrary($sClass, $sClass::NAME);
        }
        catch(SetupException $e){}
    }
    // Register the template dir into the template renderer
    $jaxon->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');
}

registerDialogLibraries();
