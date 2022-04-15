<?php

use Jaxon\Exception\SetupException;

/**
 * Register the javascript libraries in the DI container.
 *
 * @return void
 */
function registerDialogLibraries()
{
    $aLibraries = [
        // Bootbox
        'bootbox'       => Jaxon\Dialogs\Bootbox\BootboxLibrary::class,
        // Bootstrap
        'bootstrap'     => Jaxon\Dialogs\Bootstrap\BootstrapLibrary::class,
        // PgwJs
        'pgwjs'         => Jaxon\Dialogs\PgwJs\PgwJsLibrary::class,
        // Toastr
        'toastr'        => Jaxon\Dialogs\Toastr\ToastrLibrary::class,
        // JAlert
        'jalert'        => Jaxon\Dialogs\JAlert\JAlertLibrary::class,
        // Tingle
        'tingle'        => Jaxon\Dialogs\Tingle\TingleLibrary::class,
        // SimplyToast
        'simply'        => Jaxon\Dialogs\SimplyToast\SimplyToastLibrary::class,
        // Noty
        'noty'          => Jaxon\Dialogs\Noty\NotyLibrary::class,
        // Notify
        'notify'        => Jaxon\Dialogs\Notify\NotifyLibrary::class,
        // Lobibox
        'lobibox'       => Jaxon\Dialogs\Lobibox\LobiboxLibrary::class,
        // Overhang
        'overhang'      => Jaxon\Dialogs\Overhang\OverhangLibrary::class,
        // PNotify
        'pnotify'       => Jaxon\Dialogs\PNotify\PNotifyLibrary::class,
        // SweetAlert
        'sweetalert'    => Jaxon\Dialogs\SweetAlert\SweetAlertLibrary::class,
        // JQuery Confirm
        'jconfirm'      => Jaxon\Dialogs\JQueryConfirm\JQueryConfirmLibrary::class,
    ];
    $jaxon = jaxon();
    foreach($aLibraries as $sName => $sClass)
    {
        try
        {
            $jaxon->dialog()->registerLibrary($sClass, $sName);
        }
        catch(SetupException $e){}
    }
    // Register the template dir into the template renderer
    $jaxon->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');
}

registerDialogLibraries();
