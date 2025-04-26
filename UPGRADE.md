# Upgrade Guide

## Migrating from Message Cards to Adaptive Cards

### Overview

Microsoft is deprecating **O365 Connectors** within Microsoft Teams, which means **Message Cards** are now considered legacy and will no longer be supported. As a result, this package has been updated to use **Adaptive Cards** instead.

With this major update, you will need to:

#### Outside Your Application

-   **Create new incoming webhooks using Workflows for Microsoft Teams** (O365 Connectors are no longer valid).

#### Inside Your Application

-   **Migrate from Message Cards to Adaptive Cards** for Teams notifications.
-   **Adjust your message formatting** (Adaptive Cards use structured blocks instead of Markdown).

### Breaking Changes

-   **Message Cards are no longer supported**
-   **O365 Connectors cannot be used**; users must create new webhooks with Workflows
-   **Adaptive Cards require structured content blocks** instead of Markdown formatting

### Upgrade Steps

#### 1. Create a New Webhook

Since O365 Connectors are being deprecated, you must create a new incoming webhook using Microsoft Teams Workflows:

[Official Microsoft Guide](https://support.microsoft.com/en-us/office/create-incoming-webhooks-with-workflows-for-microsoft-teams-8ae491c7-0394-4861-ba59-055e33f75498)

Once created, update your `.env` file with the new webhook URL:

```env
TEAMS_WEBHOOK_URL=https://your-new-webhook-url
```

Update `config/services.php` to reference the new webhook:

```php
'microsoft_teams' => [
    'webhook_url' => env('TEAMS_WEBHOOK_URL'),
]
```

#### 2. Replace Message Cards with Adaptive Cards

##### Before (Message Cards):

```php
return MicrosoftTeamsMessage::create()
            ->to(config('services.microsoft_teams.notification_webhook_url'))
            ->type('success')
            ->title('Subscription Created')
            ->content('Yey, you got a **new subscription**.')
            ->button('Check User', 'https://foo.bar/users/123');
```

##### After (Adaptive Cards):

```php
return MicrosoftTeamsAdaptiveCard::create()
    ->to(config('services.microsoft_teams.notification_webhook_url'))
    ->title('Subscription Created')
    ->content([
        TextBlock::create()
            ->setText('Yey, you got a **new subscription**.')
            ->setWeight('Bolder')
            ->setSize('Large'),
    ])
    ->actions([
        ActionOpenUrl::create()
            ->setTitle('Check User')
            ->setUrl('https://foo.bar/users/123'),
    ]);
```

### Additional Resources

-   **Microsoft Announcement on O365 Connector Deprecation:** [Read here](https://devblogs.microsoft.com/microsoft365dev/retirement-of-office-365-connectors-within-microsoft-teams/)
-   **Adaptive Cards Documentation:** [Visit AdaptiveCards.io](https://adaptivecards.io/)
