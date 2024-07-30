<x-mail::message>
# New Task Created

Hi {{$ticket->author->name}},

You have a new ticket assigned.

## Title: {{$ticket->title}}
## Description: {{$ticket->description}}


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
