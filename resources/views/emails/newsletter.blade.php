@component('mail::message')
# {{ $subjectText }}

{{ $content }}

Cordialement,<br>
L'Équipe de la **Chorale Saint Oscar Romero**

@component('mail::subcopy')
Vous recevez cet email car vous êtes inscrit à notre newsletter.
[Se désabonner]({{ route('home') }})
@endcomponent
@endcomponent