<?php

/**
 * Plugin.php - Adapter for the PgwJS Modal library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\PgwJS;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal
{
    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('/pgwjs/modal/2.0.0/pgwmodal.min.js');
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->getCssCode('/pgwjs/modal/2.0.0/pgwmodal.min.css');
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return '
var pgwModalDefaultOptions = {
    maxWidth: 400
};
var pgwModalUserOptions = {};' . $this->getOptionScript('pgwModalUserOptions.', 'options.modal.') . '
jaxon.command.handler.register("pgwModal", function(args) {
    // Set user and default options into data only when they are missing
    for(key in pgwModalUserOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = pgwModalUserOptions[key];
        }
    }
    for(key in pgwModalDefaultOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = pgwModalDefaultOptions[key];
        }
    }
    $.pgwModal(args.data);
});';
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     *
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     *
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Set the value of the max width, if there is no value defined
        $options['title'] = (string)$title;
        // Buttons
        $modalButtons = '
';
        foreach($buttons as $button)
        {
            if($button['click'] == 'close')
            {
                $modalButtons .= '
            <button type="button" class="' . $button['class'] . '" onclick="$.pgwModal(\'close\')">' . $button['title'] . '</button>';
            }
            else
            {
                $modalButtons .= '
            <button type="button" class="' . $button['class'] . '" onclick="' . $button['click'] . '">' . $button['title'] . '</button>';
            }
        }
        // Dialog body and footer
         $options['content'] = '
        <div class="modal-body">
' . $content . '
        </div>
        <div class="modal-footer" style="padding:10px 5px 5px;">' . $modalButtons . '
        </div>
';
        // Affectations du contenu de la fenêtre
        $this->addCommand(array('cmd'=>'pgwModal'), $options);
    }

    /**
     * Hide the modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     *
     * @return void
     */
    public function hide()
    {
        $this->response()->script('$.pgwModal("close")');
    }
}
