<?php

namespace App\Interfaces;

use App\Classes\Command;

interface CommandInterface
{
    public function __construct(Command $command);

    public function validate(): bool;

    public function execute(): void;
}
