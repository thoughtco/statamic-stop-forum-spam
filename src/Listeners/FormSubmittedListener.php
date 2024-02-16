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
        $handle = $submission->form()->handle();
        
        if ($forms = config('stop-forum-spam.forms', 'all') !== 'all') {
            if (! in_array($handle, $forms)) {
                return;
            }
        }
        
        if ($email = $submission->data()->get(config('stop-forum-spam.email_handle', 'email'))) {
            $response = Http::post('http://api.stopforumspam.org/api?json', [
                'ip' => request()->ip(),
                'email' => $email    
            ]);
            
            if (! $response->ok()) {
                return;
            }
            
            $json = $response->json();
            
            if (Arr::get($json, 'email.appears', false) || Arr::get($json, 'ip.appears', false)) {
                
                if (config('stop-forum-spam.fail_silently')
                    return false;
                }
                
                throw ValidationException::withMessages([
                    '_unspecified' => 'Failed spam check',
                ]);
            }
            
        }
    }
}
