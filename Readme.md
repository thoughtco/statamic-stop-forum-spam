# Statamic Stop Forum Spam

This addon checks the [Stop Forum Spam API ](https://www.stopforumspam.com/usage) for the presence of the IP or Email from your Form submissions.

## Installation

Install by composer: `composer require thoughtco/statamic-stop-forum-spam`

## Configuration

By default all Form Submissions will be checked for the presence of an `email` field, and if found a check will be run.

If you want to override this, publish the config:

`php artisan vendor:publish --tag=statamic-stop-forum-spam`

You then have the option to specify an array of specific forms to check, what field handle to check for and whether you want to fail silently.