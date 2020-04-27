# Changelog

All notable changes to `teams` will be documented in this file

## 2.0.0 - 2020-04-27

### Breaking Change
*Method button(string $text, string $url = '', array $params = []): has now only 3 params instead of 4 since the type param got obsolete. Please adapt your code if you were using more than the first required 2 params.*
- change method button() to action() since this fits better to the documentation as [potential action](https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference#actions)
- add button() method as a wrapper for straight forward use

## 1.0.0 - 2020-04-14

- initial release
