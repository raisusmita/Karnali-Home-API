@component('mail::message')
# Welcome to Karnlai Home

You have successfully booked your Rooms.


<strong> Note: Your booking will be cancelled after 12 hours </strong>

@component('mail::button', ['url' => 'http://karnalihome.com/#/home'])
Visit Karnali Home

@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
