[![Build Status](https://github.com/jaxon-php/jaxon-dialogs/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/jaxon-php/jaxon-dialogs/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jaxon-php/jaxon-dialogs/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/jaxon-php/jaxon-dialogs/?branch=main)
[![StyleCI](https://styleci.io/repos/60390067/shield?branch=main)](https://styleci.io/repos/60390067)
[![codecov](https://codecov.io/gh/jaxon-php/jaxon-dialogs/branch/main/graph/badge.svg?token=MKqDVnW7eJ)](https://codecov.io/gh/jaxon-php/jaxon-dialogs)

[![Latest Stable Version](https://poser.pugx.org/jaxon-php/jaxon-dialogs/v/stable)](https://packagist.org/packages/jaxon-php/jaxon-dialogs)
[![Total Downloads](https://poser.pugx.org/jaxon-php/jaxon-dialogs/downloads)](https://packagist.org/packages/jaxon-php/jaxon-dialogs)
[![Latest Unstable Version](https://poser.pugx.org/jaxon-php/jaxon-dialogs/v/unstable)](https://packagist.org/packages/jaxon-php/jaxon-dialogs)
[![License](https://poser.pugx.org/jaxon-php/jaxon-dialogs/license)](https://packagist.org/packages/jaxon-php/jaxon-dialogs)

Dialogs for Jaxon
=================

Modals, alerts and confirmation dialogs for Jaxon with various javascript libraries.


Features
--------

This package provides modal, alert and confirmation dialogs to Jaxon applications with various javascript libraries.
12 libraries are currently supported.

The javascript library to use for each function is chosen by configuration, and the package takes care of loading the library files into the page and generating the javascript code.

The URL and version number can be set individually for each javascript library.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.

```json
"require": {
    "jaxon-php/jaxon-core": "^5.0",
    "jaxon-php/jaxon-dialogs": "^5.0"
}
```

Configuration
-------------

This package defines 3 config options in the `default` section to set the default library to be used.
- modal: the default library for modal dialogs
- alert: the default library for alerts
- confirm: the default library for questions

The `lib.use` option allows to load additional libraries into the page, if they are used in the application.

The `confirm` section defines options for the confirm dialog.

The `lib.uri` option defines the URI where to download the libraries files from.

Specific options can also be set for each library.

```php
    'dialogs' => [
        'default' => [
            'modal' => 'bootstrap',  // Default library for modal dialogs
            'alert' => 'jconfirm', // Default library for alerts
            'confirm' => 'noty',    // Default library for questions
        ],
        'lib' => [
            'uri' => 'https://cdn.jaxon-php.org/libs',
            'use' => ['cute', 'toastr'], // Additional libraries in use
        ],
        // Confirm options
        'confirm' => [
            'title' => 'Confirm',   // The confirm dialog
            'yes' => 'Oh Yes',       // The text of the Yes button
            'no' => 'No way',        // The text of the No button
        ],
        // Options for the Toastr library
        'toastr' => [
            'options' => [
                'closeButton' => true,
                'positionClass' => 'toast-top-center'
            ],
        ],
        // Load a different version of the JQuery Confirm library from a different CDN
        'jconfirm' => [
            'uri' => 'https://cdnjs.cloudflare.com/ajax/libs',
            'subdir' => 'jquery-confirm',
            'version' => '3.3.2',
        ],
    ],
```

Usage
-----

### Modal dialogs

This plugin provides functions to show and hide modal dialogs, with a title, a content and zero or more buttons.

```php
/**
 * Show a modal dialog.
 */
public function show($title, $content, array $buttons, array $options = []);

/**
 * Hide the modal dialog.
 */
public function hide();
```

The parameters of the `show()` methods are described as follow:

- `$title`: is a one line text to be printed at the top of the dialog.
- `$content`: the HTML content of the dialog.
- `$buttons`: a list of buttons to be printed in the dialog. Each button is an array with the following entries:
  - `title`: the text to be printed in the button.
  - `class`: the CSS class or classes to be applied on the button.
  - `click`: the javascript code to be executed on a click on this button. It can be defined using the [Request Factory](https://www.jaxon-php.org/docs/requests/factory.html), or it can be set to `close` to close the dialog.
- `$options`: an array of config options that are specific to the javascript library in use.

Example.

```php
    public function showDialog()
    {
        // The dialog buttons
        $buttons = [
            [
                'title' => 'Close',
                'class' => 'btn',
                'click' => 'close'
            ]
        ];
        // The HTML content of the dialog
        $content = "This modal dialog depends on application settings!!";
        // The dialog specific options
        $options = ['width' => 500];
        // Show the dialog
        $this->response->dialog->show("Modal Dialog", $content, $buttons, $options);
    }
```

### Alerts or notifications

This plugin provides functions to show 4 different types of alerts or notification messages.

```php
/**
 * Print a success message.
 */
public function success($message, $title = null);

/**
 * Print an information message.
 */
public function info($message, $title = null);

/**
 * Print a warning message.
 */
public function warning($message, $title = null);

/**
 * Print an error message.
 */
public function error($message, $title = null);
```

Example.

```php
    public function save($formValues)
    {
        if(!$this->validator->valid($formValues))
        {
            $this->response->dialog->error("Invalid input", "Error");
            return;
        }
        $this->repository->save($formValues);
        $this->response->dialog->success("Data saved!", "Success");
    }
```

### Confirmation question

The `confirm()` function adds a confirmation question to a Jaxon request, which will then be called only if the user answers yes to the given question.

```php
/**
 * Add a confirmation question to the request
 */
public function confirm($question, ...);
```

The first parameter, which is mandatory, is the question to ask.

The next parameters are optional; they allow the insertion of content from the web page in the confirmation question, using Jaxon or jQuery selectors and positional placeholders.
They are specially useful when pieces of information from the web page need to be inserted in translated strings.

In the example below, the user has to choose a color, and the selected color is inserted in the confirmation question.

Example with Jaxon selector.

```php
<select class="form-control" id="colorselect" name="colorselect" onchange="<?php
    echo rq('HelloWorld')->setColor(pm()->select('colorselect'))
        ->confirm('Set color to {1}?', pm()->select('colorselect')) ?>; return false;">
    <option value="black" selected="selected">Black</option>
    <option value="red">Red</option>
    <option value="green">Green</option>
    <option value="blue">Blue</option>
</select>
```

Example with jQuery selector.

```php
<select class="form-control" id="colorselect" name="colorselect" onchange="<?php
    echo rq('HelloWorld')->setColor(jq('#colorselect')->val())
        ->confirm('Set color to {1}?', jq('#colorselect')->val()) ?>; return false;">
    <option value="black" selected="selected">Black</option>
    <option value="red">Red</option>
    <option value="green">Green</option>
    <option value="blue">Blue</option>
</select>
```

Supported libraries
-------------------

This package currently supports the following javascript libraries, each implementing one or more interfaces.

#### Alertify

https://alertifyjs.com/

- Dialog id: alertify
- Implements: Modal, Alert, Confirm
- Version: 1.14.0

#### Bootbox

https://bootboxjs.com/

- Dialog id: bootbox
- Implements: Modal, Alert, Confirm
- Version: 6.0.0

#### Bootstrap 5

https://getbootstrap.com/docs/5.2/components/modal/

- Dialog id: bootstrap5
- Implements: Modal

#### Bootstrap 4

https://getbootstrap.com/docs/4.0/components/modal/

- Dialog id: bootstrap4
- Implements: Modal

#### Bootstrap 3

https://nakupanda.github.io/bootstrap3-dialog/

- Dialog id: bootstrap3
- Implements: Modal, Alert, Confirm
- Version: 1.35.4

#### Butterup

https://butteruptoast.com

- Dialog id: butterup
- Implements: Alert, Confirm

#### CuteAlert

https://github.com/gustavosmanc/cute-alert/

- Dialog id: cute
- Implements: Alert, Confirm

#### IziToast

https://marcelodolza.github.io/iziToast/

- Dialog id: izitoast
- Implements: Alert, Confirm

#### jAlert

https://htmlguyllc.github.io/jAlert/

- Dialog id: jalert
- Implements: Alert, Confirm
- Version: 4.9.1

#### JQuery-Confirm

https://craftpip.github.io/jquery-confirm/

- Dialog id: jconfirm
- Implements: Modal, Alert, Confirm
- Version: 3.3.4

#### Notify

https://notifyjs.jpillora.com/

- Dialog id: notify
- Implements: Alert
- Versions: 0.4.1

#### Noty

https://ned.im/noty/

- Dialog id: noty
- Implements: Alert, Confirm
- Version: 3.1.4

#### Notyf

https://carlosroso.com/notyf/

- Dialog id: notyf
- Implements: Alert, Confirm

#### Quantum

https://quantumalert.cosmogic.com/

- Dialog id: quantum
- Implements: Alert, Confirm

#### Sweet Alert

Sweet Alert: https://sweetalert.js.org/

- Dialog id: sweetalert
- Implements: Alert, Confirm
- Version: 2.1.2

#### Tingle

https://tingle.robinparisi.com/

- Dialog id: tingle
- Implements: Modal
- Version: 0.16.0

#### Toastr

https://codeseven.github.io/toastr/

- Dialog id: toastr
- Implements: Alert
- Version: 2.1.4

Adding a new library
--------------------

In order to add a new javascript library to this plugin, a new class needs to be defined and registered.

The class must implement the `Jaxon\App\Dialog\Library\LibraryInterface` interface, and at least one of the
`Jaxon\App\Dialog\Library\AlertInterface`, `Jaxon\App\Dialog\Library\ConfirmInterface`, or `Jaxon\App\Dialog\Library\ModalInterface`
interfaces, depending on the features it provides.

### Interfaces

The `LibraryInterface` interface is defined as follow.
It defines the name of the library, and its javascript code.

```php
interface LibraryInterface
{
    /**
     * Get the library name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the library base URI
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Get the CSS header code and file includes
     *
     * @return string
     */
    public function getCss(): string;

    /**
     * Get the javascript header code and file includes
     *
     * @return string
     */
    public function getJs(): string;

    /**
     * Get the javascript code to be printed into the page
     *
     * @return string
     */
    public function getScript(): string;

    /**
     * Get the javascript code to be executed on page load
     *
     * @return string
     */
    public function getReadyScript(): string;
}
```

The `getJs()` and `getCss()` methods return the HTML header code for loading javascript and CSS files of the library.
The `getScript()` method returns the javascript code to be executed after the page is loaded to initialize the library.

Depending on the javascript library features, the class must implement one or more of the following three interfaces.
These interfaces are empty, and thay just give an indication of which features are implemented in the js code of the library.

For windows and modal dialogs.

```php
interface ModalInterface
{
}
```

For notifications alerts.

```php
interface AlertInterface
{
}
```

For confirmation questions.

```php
interface ConfirmInterface
{
}
```

### Helper

The `Jaxon\App\Dialog\Library\AbstractDialogLibrary` bas class provides default implementations for some methods of the
`Jaxon\App\Dialog\Library\LibraryInterface` interface, as well as a `Jaxon\App\Dialog\Library\DialogLibraryHelper` object,
returned by the `helper()` method, which gives access to the dialog config options, and templates.

### Registration

After it is defined, the library class needs to be configured and registered before it can be used in the application.

The class can be registered when starting the library.

```php
use function Jaxon\Dialogs\dialog;
dialog()->registerLibrary(\Path\To\My\Plugin::class, 'myplugin');
```

Or declared in the `dialog` section of the Jaxon configuration.

```php
    'dialogs' => [
        'default' => [
            'modal' => 'myplugin',    // Default library for modal dialogs
            'alert' => 'myplugin',  // Default library for alerts
            'confirm' => 'myplugin', // Default library for confirm questions
        ],
        'lib' => [
            'ext' => [
                'myplugin' => \Path\To\My\Plugin::class,
            ],
        ],
        'myplugin' => [         // Plugin config options
            'options' => [
               'position' => 'center',
            ],
        ],
    ],
```

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-dialogs/issues
- Source Code: github.com/jaxon-php/jaxon-dialogs

License
-------

The package is licensed under the BSD license.
