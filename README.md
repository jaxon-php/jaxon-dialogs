Dialogs for Jaxon
=================

Modals, alerts and confirmation dialogs for Jaxon with various javascript libraries.


Features
--------

This package provides modal, alert and confirmation functions to Jaxon applications with various javascript libraries.
The javascript library to use for each function is chosen by configuration, and the package takes care of loading the
library files into the page and generating javascript code.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.

```json
"require": {
    "jaxon-php/jaxon-dialogs": "~1.0"
}
```

Configuration
-------------

This packages defines 3 config options to set the default library resp. for modal, alert and confirmation dialogs.
A 4th config option can be used to load additional libraries into the page.

```php
    'dialogs' => array(
        'default' => array(
            'modal' => 'bootstrap',  // Default library for modal dialogs
            'alert' => 'toastr',     // Default library for alerts
            'confirm' => 'toastr',   // Default library for confirmation
        ),
        'libraries' => array('pgwjs', 'noty'), // Additional libraries
        // Options for the Toastr library
        'toastr' => array(
            'options' => array(
                'closeButton' => true,
                'positionClass' => 'toast-top-center'
            ),
        ),
    ),
```

Specific options can also be set for each library in use.

Usage
-----
The plugin provides functions to show and hide modal dialogs. They are called using the response plugin.

```php
/**
 * Show a modal dialog.
 */
public function show($title, $content, array $buttons, array $options = array());

/**
 * Hide the modal dialog.
 */
public function hide();
```

Example.

```php
    public function showDialog()
    {
        $buttons = array(array('title' => 'Close', 'class' => 'btn', 'click' => 'close'));
        $content = "This modal dialog depends on application settings!!";
        $options = array('width' => 500);
        $this->response->dialog->show("Modal Dialog", $content, $buttons, $options);
        return $this->response;
    }
```

This plugin provides functions to show 4 types of alerts or notification messages. They are also called using the response plugin.

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
            return $this->response;
        }
        $this->repository->save($formValues);
        $this->response->dialog->success("Data saved!", "Success");
        return $this->response;
    }
```

The `confirm()` function adds a confirmation question to a Jaxon request.

```php
/**
 * Add a confirmation question to the request
 */
public function confirm($question, ...);
```

The first parameter, which is mandatory, is the question to ask.
The following parameters are optional; they allow the insertion of content from the web page in the confirmation question, using Jaxon or jQuery selectors.

Example with Jaxon selector.

```php
use Jaxon\Request\Factory as rq
```

```php
<select class="form-control" id="colorselect" name="colorselect" onchange="<?php
    echo rq::call('HelloWorld.setColor', rq::select('colorselect'))
        ->confirm('Set color to {1}?', rq::select('colorselect')) ?>; return false;">
    <option value="black" selected="selected">Black</option>
    <option value="red">Red</option>
    <option value="green">Green</option>
    <option value="blue">Blue</option>
</select>
```

Example with jQuery selector.

```php
use Jaxon\Request\Factory as rq
```

```php
<select class="form-control" id="colorselect" name="colorselect" onchange="<?php
    echo rq::call('HelloWorld.setColor', jq('#colorselect')->val())
        ->confirm('Set color to {1}?', jq('#colorselect')->val()) ?>; return false;">
    <option value="black" selected="selected">Black</option>
    <option value="red">Red</option>
    <option value="green">Green</option>
    <option value="blue">Blue</option>
</select>
```

Supported libraries
-------------------

This package currently supports 13 javascript libraries, each implementing one or more interfaces.

Bootstrap Dialog: https://nakupanda.github.io/bootstrap3-dialog

- Dialog id: bootstrap
- Implements: Modal, Alert, Confirm
- Options:


Bootbox: http://bootboxjs.com

- Dialog id: bootbox
- Implements: Modal, Alert, Confirm
- Options:

jAlert: http://flwebsites.biz/jAlert/

- Dialog id: jalert
- Implements: Alert, Confirm
- Options:

PgwJS: http://pgwjs.com/pgwmodal/

- Dialog id: pgwjs
- Implements: Modal
- Options:

Toastr: https://codeseven.github.io/toastr/

- Dialog id: toastr
- Implements: Alert
- Options:

Tingle: http://robinparisi.github.io/tingle/

- Dialog id: tingle
- Implements: Modal
- Options:

Simply Toast: https://github.com/ericprieto/simply-toast

- Dialog id: simply
- Implements: Alert
- Options:

Noty: http://ned.im/noty/

- Dialog id: noty
- Implements: Alert, Confirm
- Options:

Notify: https://notifyjs.com

- Dialog id: notify
- Implements: Alert
- Options:

Lobibox: http://lobianijs.com/site/lobibox

- Dialog id: lobibox
- Implements: Modal, Alert, Confirm
- Options:

Overhang: http://paulkr.github.io/overhang.js/ (requires jQuery and jQuery UI)

- Dialog id: overhang
- Implements: Alert, Confirm
- Options:

PNotify: http://sciactive.com/pnotify/ (requires jQuery and jQuery UI)

- Dialog id: pnotify
- Implements: Alert, Confirm
- Options:

Sweet Alert: http://t4t5.github.io/sweetalert/

- Dialog id: sweetalert
- Implements: Alert, Confirm
- Options:


Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-dialogs/issues
- Source Code: github.com/jaxon-php/jaxon-dialogs

License
-------

The package is licensed under the BSD license.
