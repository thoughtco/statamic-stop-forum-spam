<?php

namespace Thoughtco\StatamicStopForumSpam\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Statamic\Events\FormSubmitted;

class FormSubmittedListener
{
    public function handle(FormSubmitted $event)
    {
        $submission = $event->submission;
        $form = $submission->form();
        $handle = $form->handle();

        $forms = config('stop-forum-spam.forms', 'all');

        if ($forms !== 'all') {
            if (! in_array($handle, $forms)) {
                return;
            }
        }

        $fieldHandle = config('stop-forum-spam.email_handle', 'email');

        $matchingFields = $form->blueprint()->fields()->all()->filter(fn ($field) => $field->type() == 'text' && $field->get('input_type', '') == 'email');

        if ($matchingFields->isNotEmpty()) {
            $fieldHandle = $matchingFields->first()->handle();
        }

        if ($email = $submission->data()->get($fieldHandle)) {
            $response = Http::get('https://api.stopforumspam.org/api?ip='.request()->ip().'&email='.$email.'&json');

            if (! $response->ok()) {
                return;
            }

            $json = $response->json();

            if (Arr::get($json, 'email.appears', false) || Arr::get($json, 'ip.appears', false)) {

                if (config('stop-forum-spam.fail_silently')) {
                    return false;
                }

                throw ValidationException::withMessages([
                    '_unspecified' => 'Failed spam check',
                ]);
           }

        }
    }
}
