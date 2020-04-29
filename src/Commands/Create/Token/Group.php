<?php

namespace Sally\VkSdk\Commands\Create\Token;

use VK\OAuth;

class Group extends AbstractCommand
{
    private const OPTION_NAME_GROUP_IDS = 'group_ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates link to create vk group access token by provided rules and clientId';

    public function handle(OAuth\VKOAuth $oauth): void
    {
        $this->output->comment('Enter number to add group scope or press enter to skip');
        $this->askForScopes();
        $scopes = $this->getSelectedScopes();

        $redirectUrl = $this->ask('need redirect url?', 'https://oauth.vk.com/blank.html');
        $display = OAuth\VKOAuthDisplay::PAGE;

        $groupIds = explode(',', $this->argument('group_ids'));
        $clientId = $this->getClientId();

        $this->output->horizontalTable(
            ['url'],
            [
                [$oauth->getAuthorizeUrl(OAuth\VKOAuthResponseType::TOKEN, $clientId, $redirectUrl, $display, $scopes, self::STATE_SECRET_CODE, $groupIds)],
            ]
        );
    }

    protected function getCommandSignature(): string
    {
        return sprintf('%s:group {%s : For which groups access token will have access} {%s : id of application which in application settings}', parent::getCommandSignature(), self::OPTION_NAME_GROUP_IDS, self::OPTION_NAME_CLIENT_ID);
    }

    protected function getScopes(): array
    {
        return [
            1 => [
                'id'   => OAuth\Scopes\VKOAuthGroupScope::MESSAGES,
                'name' => 'message'
            ],
            2 => [
                'id'   => OAuth\Scopes\VKOAuthGroupScope::APP_WIDGET,
                'name' => 'api widget'
            ],
            3 => [
                'id'   => OAuth\Scopes\VKOAuthGroupScope::DOCS,
                'name' => 'docs'
            ],
            4 => [
                'id'   => OAuth\Scopes\VKOAuthGroupScope::MANAGE,
                'name' => 'manage'
            ],
            5 => [
                'id'   => OAuth\Scopes\VKOAuthGroupScope::PHOTOS,
                'name' => 'photos'
            ],
        ];
    }
}