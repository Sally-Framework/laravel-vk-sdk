<?php

namespace Sally\VkSdk\Commands\Create\Token;

use VK\OAuth;

class User extends AbstractCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates link to create vk user access token by provided rules, clientId and group id\'s';

    public function handle(OAuth\VKOAuth $oauth): void
    {
        $clientId = $this->argument('client_id');

        $scopes = [];
        $selectedScopeIndex = $this->askWithScopes();
        while ($selectedScopeIndex !== null) {
            $scopeId = collect($this->getUserScopes())->get($selectedScopeIndex)['id'] ?? null;
            if (in_array($scopeId, $scopes)) {
                $this->output->error('It seems like you already added this scope');
                $selectedScopeIndex = $this->askWithScopes();
                continue;
            }

            $scopes[] = $scopeId;
            $selectedScopeIndex = $this->askWithScopes();
        }

        $redirectUrl = $this->ask('need redirect url?', 'https://oauth.vk.com/blank.html');
        $display = OAuth\VKOAuthDisplay::PAGE;

        $this->output->horizontalTable(
            ['url'],
            [
                [$oauth->getAuthorizeUrl(OAuth\VKOAuthResponseType::TOKEN, $clientId, $redirectUrl, $display, $scopes, self::STATE_SECRET_CODE)],
            ]
        );
    }

    protected function getCommandSignature(): string
    {
        return sprintf('%s:user {%s : id of application which in application settings}', parent::getCommandSignature(), self::OPTION_NAME_CLIENT_ID);
    }

    private function getUserScopes(): array
    {
        return [
             1 => ['id' => OAuth\Scopes\VKOAuthUserScope::PHOTOS, 'name' => 'photos'],
             2 => ['id' => OAuth\Scopes\VKOAuthUserScope::DOCS, 'name' => 'docs'],
             3 => ['id' => OAuth\Scopes\VKOAuthUserScope::MESSAGES, 'name' => 'messages'],
             4 => ['id' => OAuth\Scopes\VKOAuthUserScope::ADS, 'name' => 'ads'],
             5 => ['id' => OAuth\Scopes\VKOAuthUserScope::AUDIO, 'name' => 'audio'],
             6 => ['id' => OAuth\Scopes\VKOAuthUserScope::EMAIL, 'name' => 'email'],
             7 => ['id' => OAuth\Scopes\VKOAuthUserScope::FRIENDS, 'name' => 'friends'],
             8 => ['id' => OAuth\Scopes\VKOAuthUserScope::GROUPS, 'name' => 'groups'],
             9 => ['id' => OAuth\Scopes\VKOAuthUserScope::LINK, 'name' => 'link'],
            10 => ['id' => OAuth\Scopes\VKOAuthUserScope::MARKET, 'name' => 'market'],
            11 => ['id' => OAuth\Scopes\VKOAuthUserScope::NOTES, 'name' => 'notes'],
            12 => ['id' => OAuth\Scopes\VKOAuthUserScope::NOTIFICATIONS, 'name' => 'notifications'],
            13 => ['id' => OAuth\Scopes\VKOAuthUserScope::NOTIFY, 'name' => 'notify'],
            14 => ['id' => OAuth\Scopes\VKOAuthUserScope::OFFLINE, 'name' => 'offline'],
            15 => ['id' => OAuth\Scopes\VKOAuthUserScope::PAGES, 'name' => 'pages'],
            16 => ['id' => OAuth\Scopes\VKOAuthUserScope::STATS, 'name' => 'stats'],
            17 => ['id' => OAuth\Scopes\VKOAuthUserScope::STATUS, 'name' => 'status'],
            18 => ['id' => OAuth\Scopes\VKOAuthUserScope::VIDEO, 'name' => 'video'],
            19 => ['id' => OAuth\Scopes\VKOAuthUserScope::WALL, 'name' => 'wall'],
        ];
    }

    private function askWithScopes(): ?string
    {
        $userScopesList = $this->convertScopesMessage($this->getUserScopes());
        $this->output->comment('Enter user scope number for token access or press enter to skip');
        return $this->ask("User Scopes:\n{$userScopesList}");
    }
}