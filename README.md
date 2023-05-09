# M2 Email Catch Module

This module has been developed for Magento > 2.4.6

Orangecat_EmailCatch module enables you to save log emails and redirect email traffic to a single email address for development purposes.

## Usage - view/resend/delete Logs

Go to System > Email > View emails Log

There you can see the emails sent, send them or delete them.

If the "redirect" function is enabled, to the original subject of the email will be added the sentence "<<<< REDIRECTED >>>>"

## Configuration

System > Email > Configuration Logs

or

Stores > Configuration > Orangecat > Email Log

- Log Settings > Enable Log Outgoing Emails: Enable saving a copy of sent email
- Log Settings > Log Clean Every (days): Sets the number of days that the logs will be kept until they are deleted (by cron)

- Developer > Enable Redirect Messages: Enable redirection of all email traffic to a single email address
- Developer > Redirect Email Address: Email address where the traffic will be redirected when the previous option is enabled

## Installation

The extension must be installed via `composer`. To proceed, run these commands in your terminal:

```
composer require orangecat/emailcatch
php bin/magento module:enable Orangecat_EmailCatch
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy