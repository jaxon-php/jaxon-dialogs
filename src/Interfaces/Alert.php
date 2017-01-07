<?php

/**
 * Alert.php - Interface for alert messages.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Interfaces;

Interface Alert
{
    /**
     * Print a success message.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     */
    public function success($message, $title = null);

    /**
     * Print an information message.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     */
    public function info($message, $title = null);

    /**
     * Print a warning message.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     */
    public function warning($message, $title = null);

    /**
     * Print an error message.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     */
    public function error($message, $title = null);
}
