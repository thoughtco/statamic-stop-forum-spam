<?php

return [
    'forms' => 'all', // or add an array of form handles eg ['form1', 'form2']

    'email_handle' => 'email',

    'fail_silently' => true,

    'test_mode' => env('STATAMIC_STOP_FORUM_SPAM_TEST_MODE', 'off'),
];
