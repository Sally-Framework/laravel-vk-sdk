<?php

namespace Sally\VkSdk\Commands;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    protected $signature = 'laravel-vk-sdk:';

    public function __construct()
    {
        $this->signature .= $this->getCommandSignature();
        parent::__construct();
    }

    abstract protected function getCommandSignature(): string;
}