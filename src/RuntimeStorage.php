<?php
/*
 * This code is part of the aesonus/storage package.
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Storage;

/**
 * Storage for a single script
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeStorage implements Contracts\StorageInterface
{
    use Concerns\HasCache;
}
