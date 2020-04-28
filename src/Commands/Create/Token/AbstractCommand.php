<?php

namespace Sally\VkSdk\Commands\Create\Token;

abstract class AbstractCommand extends \Sally\VkSdk\Commands\AbstractCommand
{
    protected const OPTION_NAME_CLIENT_ID = 'client_id';

    protected const STATE_SECRET_CODE = 'secret_state_code';

    protected function convertScopesMessage(array $scopes): string
    {
        return collect($scopes)
            ->map(function (array $scopeInfo, int $scopeId): string {
                return "{$scopeId}) {$scopeInfo['name']}";
            })->implode(PHP_EOL);
    }

    protected function getCommandSignature(): string
    {
        return 'create:token';
    }
}