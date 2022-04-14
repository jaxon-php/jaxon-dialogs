<?php

/**
 * Register the javascript libraries adapters in the DI container.
 *
 * @return void
 */
function registerDialogLibraries()
{
    $aLibraries = [
        // Bootbox
        'bootbox'       => Jaxon\Dialogs\Library\Bootbox\BootboxLibrary::class,
        // Bootstrap
        'bootstrap'     => Jaxon\Dialogs\Library\Bootstrap\BootstrapLibrary::class,
        // PgwJs
        'pgwjs'         => Jaxon\Dialogs\Library\PgwJs\PgwJsLibrary::class,
        // Toastr
        'toastr'        => Jaxon\Dialogs\Library\Toastr\ToastrLibrary::class,
        // JAlert
        'jalert'        => Jaxon\Dialogs\Library\JAlert\JAlertLibrary::class,
        // Tingle
        'tingle'        => Jaxon\Dialogs\Library\Tingle\TingleLibrary::class,
        // SimplyToast
        'simply'        => Jaxon\Dialogs\Library\SimplyToast\SimplyToastLibrary::class,
        // Noty
        'noty'          => Jaxon\Dialogs\Library\Noty\NotyLibrary::class,
        // Notify
        'notify'        => Jaxon\Dialogs\Library\Notify\NotifyLibrary::class,
        // Lobibox
        'lobibox'       => Jaxon\Dialogs\Library\Lobibox\LobiboxLibrary::class,
        // Overhang
        'overhang'      => Jaxon\Dialogs\Library\Overhang\OverhangLibrary::class,
        // PNotify
        'pnotify'       => Jaxon\Dialogs\Library\PNotify\PNotifyLibrary::class,
        // SweetAlert
        'sweetalert'    => Jaxon\Dialogs\Library\SweetAlert\SweetAlertLibrary::class,
        // JQuery Confirm
        'jconfirm'      => Jaxon\Dialogs\Library\JQueryConfirm\JQueryConfirmLibrary::class,
    ];
}

// Register the template dir into the template renderer
jaxon()->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');
