@component('mail::message')
# Welcome to Karnlai Home

Your room has been booked from {{$check_in_date}} to {{$check_out_date}}.


<strong> Note: Your booking will be cancelled after 12 hours </strong>

@component('mail::button', ['url' => 'http://karnalihome.com/#/home'])
Visit Karnali Home

@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
