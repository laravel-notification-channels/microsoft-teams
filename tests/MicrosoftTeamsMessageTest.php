<?php

namespace NotificationChannels\MicrosoftTeams\Test;

use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class MicrosoftTeamsMessageTest.
 */
class MicrosoftTeamsMessageTest extends TestCase
{
    /** @test */
    public function it_accepts_content_when_constructed(): void
    {
        $message = new MicrosoftTeamsMessage('Laravel Notification Channels are awesome!');
        $this->assertEquals('Laravel Notification Channels are awesome!', $message->getPayloadValue('text'));
    }

    /** @test */
    public function the_default_params_are_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $this->assertEquals('MessageCard', $message->getPayloadValue('@type'));
        $this->assertEquals('https://schema.org/extensions', $message->getPayloadValue('@context'));
        $this->assertEquals('Incoming Notification', $message->getPayloadValue('summary'));
        $this->assertEquals('#1976D2', $message->getPayloadValue('themeColor'));
    }

    /** @test */
    public function setting_the_title_also_sets_the_summary(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->title('Laravel Notification');
        $this->assertEquals('Laravel Notification', $message->getPayloadValue('title'));
        $this->assertEquals('Laravel Notification', $message->getPayloadValue('summary'));
    }

    /** @test */
    public function setting_the_title_of_a_section_is_working(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->title('Laravel Notification', ['section' => 1]);
        $this->assertEquals('Laravel Notification', $message->getPayloadValue('sections')[1]['title']);
        $this->assertEquals('', $message->getPayloadValue('title'));
        $this->assertEquals('Incoming Notification', $message->getPayloadValue('summary'));
    }

    /** @test */
    public function setting_the_title_of_different_sections_is_working(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->title('Laravel Notification Section 1', ['section' => 1]);
        $message->title('Laravel Notification Section 2', ['section' => 2]);
        $this->assertEquals('Laravel Notification Section 1', $message->getPayloadValue('sections')[1]['title']);
        $this->assertEquals('Laravel Notification Section 2', $message->getPayloadValue('sections')[2]['title']);
    }

    /** @test */
    public function a_summary_can_be_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->summary('Laravel Notification');
        $this->assertEquals('Laravel Notification', $message->getPayloadValue('summary'));
    }

    /** @test */
    public function a_type_info_generates_the_info_color_code_on_the_themeColor_property(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->type('info');
        $this->assertEquals('#2196F3', $message->getPayloadValue('themeColor'));
    }

    /** @test */
    public function a_non_existing_type_is_using_the_type_as_hex_code_for_the_themeColor_property(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->type('#fff');
        $this->assertEquals('#fff', $message->getPayloadValue('themeColor'));
    }

    /** @test */
    public function a_content_can_be_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->content('Laravel is awesome!');
        $this->assertEquals('Laravel is awesome!', $message->getPayloadValue('text'));
    }

    /** @test */
    public function setting_the_content_of_a_section_is_working(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->content('Laravel is awesome!', ['section' => 1]);
        $this->assertEquals('Laravel is awesome!', $message->getPayloadValue('sections')[1]['text']);
        $this->assertEquals('', $message->getPayloadValue('text'));
    }

    /** @test */
    public function the_recipients_webhook_url_can_be_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
        $this->assertEquals('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567', $message->getWebhookUrl());
    }

    /** @test */
    public function it_throws_an_exception_if_the_recipients_webhook_url_is_an_empty_string(): void
    {
        $message = new MicrosoftTeamsMessage();

        $this->expectException(CouldNotSendNotification::class);

        $message->to('');
    }

    /** @test */
    public function the_notification_message_can_be_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->content('Laravel Notification Channels are awesome!');
        $this->assertEquals('Laravel Notification Channels are awesome!', $message->getPayloadValue('text'));
    }

    /** @test */
    public function an_action_with_options_can_be_added_to_the_messsage(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->action('Laravel', 'HttpPOST', ['body' => 'test body']);
        $expectedButton = (object) [
            '@type' => 'HttpPOST',
            'name' => 'Laravel',
            'body' => 'test body',
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('potentialAction')[0]);
    }

    /** @test */
    public function a_standard_button_can_be_added_to_the_messsage(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->button('Laravel', 'https://laravel.com');
        $expectedButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Laravel',
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' => 'https://laravel.com',
                ],
            ],
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('potentialAction')[0]);
    }

    /** @test */
    public function a_button_with_additional_options_can_be_added_to_the_messsage(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->button('Laravel', 'https://laravel.com', ['body' => 'test body']);
        $expectedButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Laravel',
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' => 'https://laravel.com',
                ],
            ],
            'body' => 'test body',
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('potentialAction')[0]);
    }

    /** @test */
    public function a_button_with_custom_targets_are_overwriting_the_standard_targets_of_the_messsage(): void
    {
        $message = new MicrosoftTeamsMessage();
        $customTargets = ['targets' => [
            (object) [
                'os'=> 'android',
                'uri' => 'https://android.laravel.com',
            ],
            (object) [
                'os'=> 'iOS',
                'uri' => 'https://ios.laravel.com',
            ],
        ],
        ];
        $message->button('Laravel', 'https://laravel.com', $customTargets);
        $expectedButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Laravel',
            'targets' => $customTargets['targets'],
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('potentialAction')[0]);
    }

    /** @test */
    public function multiple_buttons_can_be_added_to_the_messsage(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->button('Laravel', 'https://laravel.com');
        $message->button('Microsoft Teams', 'https://products.office.com/de-at/microsoft-teams/group-chat-software');
        $expectedFirstButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Laravel',
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' => 'https://laravel.com',
                ],
            ],
        ];
        $expectedSecondButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Microsoft Teams',
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' =>  'https://products.office.com/de-at/microsoft-teams/group-chat-software',
                ],
            ],
        ];

        $this->assertEquals($expectedFirstButton, $message->getPayloadValue('potentialAction')[0]);
        $this->assertEquals($expectedSecondButton, $message->getPayloadValue('potentialAction')[1]);
    }

    /** @test */
    public function additional_options_can_be_set_for_the_message(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->options(['foo' => 'bar']);
        $this->assertEquals('bar', $message->getPayloadValue('foo'));
    }

    /** @test */
    public function additional_options_can_be_set_for_an_existing_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->addStartGroupToSection(1);
        $message->options(['foo' => 'bar'], 1);
        $this->assertEquals('bar', $message->getPayloadValue('sections')[1]['foo']);
    }

    /** @test */
    public function an_action_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->action('Laravel', 'HttpPOST', ['section' => 1]);
        $expectedButton = (object) [
            '@type' => 'HttpPOST',
            'name' => 'Laravel',
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('sections')[1]['potentialAction'][0]);
        $this->assertEmpty($message->getPayloadValue('potentialAction'));
    }

    /** @test */
    public function a_standard_button_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->button('Laravel', 'https://laravel.com', ['section' => 1]);
        $expectedButton = (object) [
            '@type' => 'OpenUri',
            'name' => 'Laravel',
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' => 'https://laravel.com',
                ],
            ],
        ];
        $this->assertEquals($expectedButton, $message->getPayloadValue('sections')[1]['potentialAction'][0]);
        $this->assertEmpty($message->getPayloadValue('potentialAction'));
    }

    /** @test */
    public function a_start_group_property_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->addStartGroupToSection(1);
        $this->assertTrue($message->getPayloadValue('sections')[1]['startGroup']);
    }

    /** @test */
    public function an_activity_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->activity('https://connectorsdemo.azurewebsites.net/images/MSC12_Oscar_002.jpg', 'Activity Title', 'Activity Subtitle', 'Activity Content', 1);
        $this->assertEquals('https://connectorsdemo.azurewebsites.net/images/MSC12_Oscar_002.jpg', $message->getPayloadValue('sections')[1]['activityImage']);
        $this->assertEquals('Activity Title', $message->getPayloadValue('sections')[1]['activityTitle']);
        $this->assertEquals('Activity Subtitle', $message->getPayloadValue('sections')[1]['activitySubtitle']);
        $this->assertEquals('Activity Content', $message->getPayloadValue('sections')[1]['activityText']);
    }

    /** @test */
    public function a_fact_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->fact('Name', 'John Doe', 1);
        $this->assertEquals('Name', $message->getPayloadValue('sections')[1]['facts'][0]['name']);
        $this->assertEquals('John Doe', $message->getPayloadValue('sections')[1]['facts'][0]['value']);
    }

    /** @test */
    public function a_fact_can_be_added_without_a_section_which_defaults_to_standard_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->fact('Name', 'John Doe');
        $this->assertEquals('Name', $message->getPayloadValue('sections')['standard_section']['facts'][0]['name']);
        $this->assertEquals('John Doe', $message->getPayloadValue('sections')['standard_section']['facts'][0]['value']);
    }

    /** @test */
    public function multiple_facts_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->fact('Name', 'John Doe', 1);
        $message->fact('Age', '28', 1);
        $this->assertEquals('Name', $message->getPayloadValue('sections')[1]['facts'][0]['name']);
        $this->assertEquals('John Doe', $message->getPayloadValue('sections')[1]['facts'][0]['value']);
        $this->assertEquals('Age', $message->getPayloadValue('sections')[1]['facts'][1]['name']);
        $this->assertEquals('28', $message->getPayloadValue('sections')[1]['facts'][1]['value']);
    }

    /** @test */
    public function a_image_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->image('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip', 1);
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', $message->getPayloadValue('sections')[1]['images'][0]['image']);
        $this->assertEquals('Tooltip', $message->getPayloadValue('sections')[1]['images'][0]['title']);
    }

    /** @test */
    public function a_image_can_be_added_without_a_section_which_defaults_to_standard_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->image('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip');
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', $message->getPayloadValue('sections')['standard_section']['images'][0]['image']);
        $this->assertEquals('Tooltip', $message->getPayloadValue('sections')['standard_section']['images'][0]['title']);
    }

    /** @test */
    public function multiple_images_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->image('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip1', 1);
        $message->image('https://messagecardplayground.azurewebsites.net/assets/TINYPulseQuestionIcon.png', 'Tooltip2', 1);
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', $message->getPayloadValue('sections')[1]['images'][0]['image']);
        $this->assertEquals('Tooltip1', $message->getPayloadValue('sections')[1]['images'][0]['title']);
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/TINYPulseQuestionIcon.png', $message->getPayloadValue('sections')[1]['images'][1]['image']);
        $this->assertEquals('Tooltip2', $message->getPayloadValue('sections')[1]['images'][1]['title']);
    }

    /** @test */
    public function a_hero_image_can_be_added_to_a_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->heroImage('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip', 1);
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', $message->getPayloadValue('sections')[1]['heroImage']['image']);
        $this->assertEquals('Tooltip', $message->getPayloadValue('sections')[1]['heroImage']['title']);
    }

    /** @test */
    public function a_hero_image_can_be_added_without_a_section_which_defaults_to_standard_section(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->heroImage('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip');
        $this->assertEquals('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', $message->getPayloadValue('sections')['standard_section']['heroImage']['image']);
        $this->assertEquals('Tooltip', $message->getPayloadValue('sections')['standard_section']['heroImage']['title']);
    }

    /** @test */
    public function it_can_determine_if_the_recipient_id_has_not_been_set(): void
    {
        $message = new MicrosoftTeamsMessage();
        $this->assertTrue($message->toNotGiven());

        $message->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
        $this->assertFalse($message->toNotGiven());
    }

    /** @test */
    public function it_can_show_the_webhook_url(): void
    {
        $message = new MicrosoftTeamsMessage();

        $message->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
        $this->assertEquals('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567', $message->getWebhookUrl());
    }

    /** @test */
    public function it_can_return_the_payload_as_an_array(): void
    {
        $message = new MicrosoftTeamsMessage('Laravel Notification Channels are awesome!');
        $message->options(['foo' => 'bar']);
        $message->title('Laravel is awesome!');
        $message->button('Laravel', 'https://laravel.com');
        $expected = [
            '@type' => 'MessageCard',
            '@context' => 'https://schema.org/extensions',
            'summary' => 'Laravel is awesome!',
            'themeColor' => '#1976D2',
            'title' => 'Laravel is awesome!',
            'text' => 'Laravel Notification Channels are awesome!',
            'foo'  => 'bar',
            'potentialAction' => [(object) [
                '@type' => 'OpenUri',
                'name' => 'Laravel',
                'targets' => [
                    (object) [
                        'os'=> 'default',
                        'uri' => 'https://laravel.com',
                    ],
                ],
            ]],
        ];

        $this->assertEquals($expected, $message->toArray());
    }

    /** @test */
    public function it_can_return_the_payload_as_an_array_with_sections(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->title('Laravel is awesome!', ['section' => 1]);
        $message->content('Laravel Notification Channels are awesome!', ['section' => 1]);
        $message->button('Laravel', 'https://laravel.com');
        $expected = [
            '@type' => 'MessageCard',
            '@context' => 'https://schema.org/extensions',
            'summary' => 'Incoming Notification',
            'themeColor' => '#1976D2',
            'text' => '',
            'sections' => [
                '1' => [
                    'title' => 'Laravel is awesome!',
                    'text' => 'Laravel Notification Channels are awesome!',
                ],
            ],
            'potentialAction' => [(object) [
                '@type' => 'OpenUri',
                'name' => 'Laravel',
                'targets' => [
                    (object) [
                        'os'=> 'default',
                        'uri' => 'https://laravel.com',
                    ],
                ],
            ]],
        ];

        $this->assertEquals($expected, $message->toArray());
    }

    /** @test */
    public function it_can_return_the_payload_as_an_array_with_activities_facts_and_images_in_multiple_sections(): void
    {
        $message = new MicrosoftTeamsMessage();
        $message->title('Laravel is awesome!', ['section' => 1])
            ->content('Laravel Notification Channels are awesome!', ['section' => 1])
            ->addStartGroupToSection('activity_section')
            ->activity('', 'My Activity', 'Info to my Activity', 'This is the content of my activity', 'activity_section')
            ->fact('Name', 'John Doe', 'fact_section')
            ->fact('Age', '28', 'fact_section')
            ->heroImage('https://messagecardplayground.azurewebsites.net/assets/TINYPulseQuestionIcon.png', 'TooltipHero', 'image_section')
            ->image('https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'Tooltip', 'image_section')
            ->image('https://messagecardplayground.azurewebsites.net/assets/FlowLogo2.png', 'Tooltip2', 'image_section')
            ->button('Laravel', 'https://laravel.com', ['section' => 'image_section']);
        $expected = [
            '@type' => 'MessageCard',
            '@context' => 'https://schema.org/extensions',
            'summary' => 'Incoming Notification',
            'themeColor' => '#1976D2',
            'text' => '',
            'sections' => [
                '1' => [
                    'title' => 'Laravel is awesome!',
                    'text' => 'Laravel Notification Channels are awesome!',
                ],
                'activity_section' => [
                    'startGroup' => true,
                    'activityImage' => '',
                    'activityTitle' => 'My Activity',
                    'activitySubtitle' => 'Info to my Activity',
                    'activityText' => 'This is the content of my activity',
                ],
                'fact_section' => [
                    'facts' => [
                        ['name' => 'Name', 'value' => 'John Doe'],
                        ['name' => 'Age', 'value' => '28'],
                    ],
                ],
                'image_section' => [
                    'heroImage' => ['image' => 'https://messagecardplayground.azurewebsites.net/assets/TINYPulseQuestionIcon.png', 'title' => 'TooltipHero'],
                    'images' => [
                        ['image' => 'https://messagecardplayground.azurewebsites.net/assets/FlowLogo.png', 'title' => 'Tooltip'],
                        ['image' => 'https://messagecardplayground.azurewebsites.net/assets/FlowLogo2.png', 'title' => 'Tooltip2'],
                    ],
                    'potentialAction' => [(object) [
                        '@type' => 'OpenUri',
                        'name' => 'Laravel',
                        'targets' => [
                            (object) [
                                'os'=> 'default',
                                'uri' => 'https://laravel.com',
                            ],
                        ],
                    ]],
                ],
            ],
        ];

        $this->assertEquals($expected, $message->toArray());
    }
}
