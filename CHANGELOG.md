# Changelog

All notable changes to `microsoft-teams` will be documented in this file

## 1.1.3 - 2022-06-21
Update Readme to support Laravel 9.x

## 1.1.2 - 2022-06-03
Changed registered notification driver from 'teams' to 'microsoftTeams' to be consistent. Current Release now accepts both names.
Please use 'microsoftTeams' for all calls since 'teams' will be removed with the next major release.

## 1.1.1 - 2022-01-21
Support Laravel 9.x

## 1.1.0 - 2020-09-10
Support Laravel 8.x

## 1.0.1 - 2020-04-27

### Breaking Change
*Method button(string $text, string $url = '', array $params = []): has now only 3 params instead of 4 since the type param got obsolete. Please adapt your code if you were using more than the first required 2 params.*
- change method button() to action() since this fits better to the documentation as [potential action](https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference#actions)
- add button() method as a wrapper for straight forward use

## 1.0.0 - 2020-04-14

- initial release
