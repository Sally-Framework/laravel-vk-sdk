<?php

namespace Sally\VkSdk\Commands\Create\Token;

abstract class AbstractCommand extends \Sally\VkSdk\Commands\AbstractCommand
{
    protected const OPTION_NAME_CLIENT_ID = 'client_id';

    protected const STATE_SECRET_CODE = 'secret_state_code';

    /**
     * @var int[]
     */
    private $selectedScopes = [];

    abstract protected function getScopes(): array;

    protected function convertScopesMessage(array $scopes): string
    {
        return collect($scopes)
            ->map(function (array $scopeInfo, int $scopeId): string {
                return "{$scopeId}) {$scopeInfo['name']}";
            })->implode(PHP_EOL);
    }

    protected function getClientId(): int
    {
        return $this->argument(self::OPTION_NAME_CLIENT_ID);
    }

    protected function askForScopes(): void
    {
        $userScopesList = $this->convertScopesMessage($this->getScopes());
        $selectedScopeIndex = $this->ask($userScopesList);
        if ($selectedScopeIndex === null) {
            return;
        }

        $scopeId = collect($this->getScopes())->get($selectedScopeIndex)['id'] ?? null;
        if (in_array($scopeId, $this->selectedScopes)) {
            $this->output->error('It seems like you already added this scope');
            $this->askForScopes();
            return;
        }

        $this->selectedScopes[] = $scopeId;
        $this->askForScopes();
    }

    /**
     * @return int[]
     */
    protected function getSelectedScopes(): array
    {
        return $this->selectedScopes;
    }

    protected function getCommandSignature(): string
    {
        return 'create:token';
    }

}