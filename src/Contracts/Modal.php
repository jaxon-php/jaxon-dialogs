<?php

/**
 * Modal.php - Interface for modal dialogs.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Contracts;

interface Modal
{
    /**
     * Show a modal dialog.
     *
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     *
     * Each button is an array with the following entries:
     * - title: the text to be printed in the button
     * - class: the CSS class of the button
     * - click: the javascript function to be called when the button is clicked
     * If the click value is set to "close", then the button closes the dialog.
     *
     * The content of the $options depends on the javascript library in use.
     * Check their specific documentation for more information.
     *
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array());

    /**
     * Hide the modal dialog.
     *
     * @return void
     */
    public function hide();
}
