<?php

/**
 * Quantum.php
 *
 * Adapter for the QuantumAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2025 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog\Library;

use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\App\Dialog\Library\AlertInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;

class Quantum extends AbstractLibrary implements AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'quantum';

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['minfile/quantumalert.js'];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return 'https://cdn.jsdelivr.net/gh/cosmogicofficial/quantumalert@latest';
    }
}
