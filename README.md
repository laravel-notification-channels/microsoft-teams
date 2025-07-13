# Microsoft Teams Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/microsoft-teams.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/microsoft-teams)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/microsoft-teams.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/microsoft-teams)

This package makes it easy to send notifications using [Microsoft Teams](https://products.office.com/en-US/microsoft-teams/group-chat-software) with Laravel 5.5+, 6.x, 7.x, 8.x, 9.x, 10.x, 11.x and 12.x

**Since v2 we transitioned from traditional message cards to [MS Adaptive Cards](https://adaptivecards.io). In case you need to upgrade please check out our [Migration Guide](/UPGRADE.md).**

```php
return MicrosoftTeamsAdaptiveCard::create()
    ->to(config('services.microsoft_teams.webhook_url'))
    ->title('Subscription Created')
    ->content([
        TextBlock::create()
            ->setText('Yey, you got a **new subscription**.')
            ->setFontType('Monospace')
            ->setWeight('Bolder')
            ->setSize('ExtraLarge')
            ->setSpacing('ExtraLarge')
            ->setStyle('Heading')
            ->setHorizontalAlignment('Center')
            ->setSeparator(true),
        FactSet::create()
            ->setSpacing('ExtraLarge')
            ->setSeparator(true)
            ->setFacts([
                Fact::create()->setTitle('Subscription Created')->setValue('Today'),
            ])
    ])
    ->actions([
        ActionOpenUrl::create()
            ->setMode('Primary')
            ->setStyle('Positive')
            ->setTitle('Contact Customer')
            ->setUrl("https://www.tournamize.com"),
    ]);
```

## Contents

-   [Microsoft Teams Notifications Channel for Laravel](#microsoft-teams-notifications-channel-for-laravel)
    -   [Contents](#contents)
    -   [Installation](#installation)
        -   [Setting up the Connector](#setting-up-the-connector)
        -   [Setting up the MicrosoftTeams service](#setting-up-the-microsoftteams-service)
    -   [Usage](#usage)
        -   [On-Demand Notification Usage](#on-demand-notification-usage)
        -   [Available Adaptive Card methods](#available-adaptive-cards-methods)
        -   [Content Block Types](#content-block-types)
        -   [Action Types](#action-types)
    -   [Changelog](#changelog)
    -   [Testing](#testing)
    -   [Security](#security)
    -   [Contributing](#contributing)
    -   [Credits](#credits)
    -   [License](#license)

## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/microsoft-teams
```

Next, if you're using Laravel _without_ auto-discovery, add the service provider to `config/app.php`:

```php
'providers' => [
    // ...
    NotificationChannels\MicrosoftTeams\MicrosoftTeamsServiceProvider::class,
],
```

### Setting up the Connector

Please check out [this](https://support.microsoft.com/en-us/office/create-incoming-webhooks-with-workflows-for-microsoft-teams-8ae491c7-0394-4861-ba59-055e33f75498) for setting up and adding a webhook connector to your Team's channel. Please also check out the [adaptive card reference](https://learn.microsoft.com/en-us/adaptive-cards/) which goes in more detail about adaptive cards.

### Setting up the MicrosoftTeams service

Then, configure your webhook url:

Add the following code to your `config/services.php`:

```php
// config/services.php
...
'microsoft_teams' => [
    'webhook_url' => env('TEAMS_WEBHOOK_URL'),
],
...
```

You can also add multiple webhooks if you have multiple teams or channels, it's up to you.

```php
// config/services.php
...
'microsoft_teams' => [
    'sales_url' => env('TEAMS_SALES_WEBHOOK_URL'),
    'dev_url' => env('TEAMS_DEV_WEBHOOK_URL'),
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class SubscriptionCreated extends Notification
{
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }

    public function toMicrosoftTeams($notifiable)
    {
        return MicrosoftTeamsAdaptiveCard::create()
            ->to(config('services.microsoft_teams.webhook_url'))
            ->title('Subscription Created')
            ->content([
                TextBlock::create()
                    ->setText('Yey, you got a **new subscription**.')
                    ->setFontType('Monospace')
                    ->setWeight('Bolder')
                    ->setSize('ExtraLarge')
                    ->setSpacing('ExtraLarge')
                    ->setStyle('Heading')
                    ->setHorizontalAlignment('Center')
                    ->setSeparator(true),
                FactSet::create()
                    ->setSpacing('ExtraLarge')
                    ->setSeparator(true)
                    ->setFacts([
                        Fact::create()->setTitle('Subscription Created')->setValue('Today'),
                    ])
            ])
            ->actions([
                ActionOpenUrl::create()
                    ->setMode('Primary')
                    ->setStyle('Positive')
                    ->setTitle('Contact Customer')
                    ->setUrl("https://www.tournamize.com"),
            ]);
    }
}
```

Instead of adding the `to($url)` method for the recipient you can also add the `routeNotificationForMicrosoftTeams` method inside your Notifiable model. This method needs to return the webhook url.

```php
public function routeNotificationForMicrosoftTeams(Notification $notification)
{
    return config('services.microsoft_teams.sales_url');
}
```

### On-Demand Notification Usage

To use on demand notifications you can use the `route` method on the Notification facade.

```php
Notification::route(MicrosoftTeamsChannel::class,null)
    ->notify(new SubscriptionCreated());
```

### Available Adaptive Card methods

-   `create()`: Static factory method to create a new instance of the MicrosoftTeamsAdaptiveCard.
-   `to(string $webhookUrl)`: Sets the recipient's webhook URL. Required for sending notifications.
-   `title(string $title)`: Sets the title of the adaptive card with appropriate text styling (heading style, bold weight, and large size).
-   `fullWidth()`: Sets the adaptive card to take up the full width of the Teams channel or chat instead of the default width.
-   `content(array $contentBlocks)`: Adds content blocks to the adaptive card body. Accepts an array of content block objects like TextBlock, FactSet, Icon, etc.
-   `actions(array $actions)`: Adds action buttons to the adaptive card. Accepts an array of action objects like ActionOpenUrl.
-   `getWebhookUrl()`: Returns the currently set webhook URL.
-   `toNotGiven()`: Checks if the webhook URL has been provided. Returns true if no webhook URL is set.
-   `toArray()`: Returns the complete payload as an associative array.

### Content Block Types

You can use various content block types in the content() method:

#### `TextBlock::create()`: Creates a text block with options for:

-   `setText(string $text)`: Sets the text content (supports markdown formatting)
-   `setFontType(string $fontType)`: Sets the font type (e.g., 'Default', 'Monospace')
-   `setWeight(string $weight)`: Sets text weight (e.g., 'Lighter', 'Default', 'Bolder')
-   `setSize(string $size)`: Sets text size (e.g., 'Small', 'Default', 'Medium', 'Large', 'ExtraLarge')
-   `setSpacing(string $spacing)`: Sets spacing around the element (e.g., 'None', 'Small', 'Default', 'Medium', 'Large', 'ExtraLarge', 'Padding')
-   `setStyle(string $style)`: Sets text style (e.g., 'Default', 'ColumnHeader', 'Heading')
-   `setHorizontalAlignment(string $alignment)`: Sets text alignment (e.g., 'Left', 'Center', 'Right')
-   `setSeparator(bool $separator)`: Adds a separator line above the element when true
-   `setWrap(bool $wrap)`: Sets whether text should wrap or not
-   `setColor(string $color)`: Sets text color (e.g., 'Default', 'Dark', 'Light', 'Accent', 'Good', 'Warning', 'Attention')
-   `setIsSubtle(bool $isSubtle)`: Sets whether text should be subtle or not
-   `setMaximumLines(int $maxLines)`: Sets the maximum number of lines for the text block
-   `toArray()`: Returns the TextBlock properties as an array

#### `Icon::create()`: Creates an icon with options for:

-   `setName(string $name)`: Sets the name of the icon
-   `setColor(string $color)`: Sets icon color (e.g., 'Default', 'Dark', 'Light', 'Accent', 'Good', 'Warning', 'Attention')
-   `setSpacing(string $spacing)`: Sets spacing around the icon (e.g., 'None', 'Small', 'Default', 'Medium', 'Large', 'ExtraLarge', 'Padding')
-   `setSeparator(bool $separator)`: Adds a separator line above the element when true
-   `setSize(string $size)`: Sets icon size (e.g., 'xxSmall', 'xSmall', 'Small', 'Standard', 'Medium', 'Large', 'xLarge', 'xxLarge')
-   `setStyle(string $style)`: Sets icon style (e.g., 'Regular', 'Filled')
-   `setHorizontalAlignment(string $alignment)`: Sets icon alignment (e.g., 'Left', 'Center', 'Right')
-   `toArray()`: Returns the Icon properties as an array

#### `FactSet::create()`: Creates a set of facts with options for:

-   `setFacts(array $facts)`: Sets an array of Fact objects
-   `setSpacing(string $spacing)`: Sets spacing around the element (e.g., 'None', 'Small', 'Default', 'Medium', 'Large', 'ExtraLarge', 'Padding')
-   `setSeparator(bool $separator)`: Adds a separator line above the element when true
-   `toArray()`: Returns the FactSet properties as an array

#### `Fact::create()`: Creates a single fact (key-value pair) with methods:

-   `setTitle(string $title)`: Sets the fact's title/key
-   `setValue(string $value)`: Sets the fact's value
-   `toArray()`: Returns the Fact properties as an array

### Action Types

You can use various action types in the actions() method:

#### `ActionOpenUrl::create()`: Creates a button that opens a URL with options for:

-   `setTitle(string $title)`: Sets the button text
-   `setUrl(string $url)`: Sets the URL to open when clicked
-   `setStyle(string $style)`: Sets button style (e.g., 'Default', 'Positive', 'Destructive')
-   `setMode(string $mode)`: Sets the interaction mode (e.g., 'Primary', 'Secondary')
-   `toArray()`: Returns the ActionOpenUrl properties as an array

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email tobias.madner@gmx.at instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Tobias Madner](https://github.com/Tob0t)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
