<?php

namespace Jaxon\Dialogs;

use Jaxon\Dialogs\Bootbox\BootboxLibrary;
use Jaxon\Dialogs\Bootstrap\BootstrapLibrary;
use Jaxon\Dialogs\PgwJs\PgwJsLibrary;
use Jaxon\Dialogs\Toastr\ToastrLibrary;
use Jaxon\Dialogs\JAlert\JAlertLibrary;
use Jaxon\Dialogs\Tingle\TingleLibrary;
use Jaxon\Dialogs\Noty\NotyLibrary;
use Jaxon\Dialogs\Notify\NotifyLibrary;
use Jaxon\Dialogs\Overhang\OverhangLibrary;
use Jaxon\Dialogs\PNotify\PNotifyLibrary;
use Jaxon\Dialogs\SweetAlert\SweetAlertLibrary;
use Jaxon\Dialogs\JQueryConfirm\JQueryConfirmLibrary;
use Jaxon\Dialogs\XDialog\XDialogLibrary;
use Jaxon\Dialogs\CuteAlert\CuteAlertLibrary;
use Jaxon\Exception\SetupException;
use function Jaxon\jaxon;

/**
 * @return void
 */
function registerDialogLibraries()
{
    $aLibraries = [
        BootboxLibrary::class, // Bootbox
        BootstrapLibrary::class, // Bootstrap
        PgwJsLibrary::class, // PgwJs
        ToastrLibrary::class, // Toastr
        JAlertLibrary::class, // JAlert
        TingleLibrary::class, // Tingle
        NotyLibrary::class, // Noty
        NotifyLibrary::class, // Notify
        OverhangLibrary::class, // Overhang
        PNotifyLibrary::class, // PNotify
        SweetAlertLibrary::class, // SweetAlert
        JQueryConfirmLibrary::class, // JQuery Confirm
        XDialogLibrary::class, // XDialog
        CuteAlertLibrary::class, // CuteAlert
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
