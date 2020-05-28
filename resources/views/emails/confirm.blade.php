@component('mail::message')
Hola {{$user->name}}

Has cambiado tu correo electronico por favor verificala usando el siguiente boton:

@component('mail::button', ['url' => route('verify', $user->varification_token)])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

