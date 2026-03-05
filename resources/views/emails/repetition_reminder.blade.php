<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
        }

        .header {
            background: #7367F0;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888;
        }

        .details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .details p {
            margin: 5px 0;
        }

        .chant-list {
            list-style: none;
            padding: 0;
        }

        .chant-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .chant-item:last-child {
            border-bottom: none;
        }

        .part-header {
            font-weight: bold;
            color: #7367F0;
            margin-top: 15px;
            text-transform: uppercase;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <!-- <img src="{{ $message->embed(public_path('images/logo chorale st oscar romero blanc.png')) }}" alt="Logo"
                style="width: 80px; height: auto; margin-bottom: 10px;"> -->
            <h1 style="margin: 0; font-size: 24px;">Rappel de Répétition</h1>
        </div>
        <div class="content">
            <p>Bonjour cher choriste,</p>
            <p>Ceci est un rappel pour notre prochaine répétition : <strong>{{ $repetition->titre }}</strong>.</p>

            <div class="details">
                <p><strong>📅 Date :</strong>
                    {{ \Carbon\Carbon::parse($repetition->start_time)->translatedFormat('l d F Y') }}</p>
                <p><strong>⏰ Heure :</strong> {{ \Carbon\Carbon::parse($repetition->start_time)->format('H:i') }} —
                    {{ \Carbon\Carbon::parse($repetition->end_time)->format('H:i') }}
                </p>
                <p><strong>📍 Lieu :</strong> {{ $repetition->lieu }}</p>
            </div>

            @if($repetition->repertoires->count() > 0)
                <h3>Chants à préparer :</h3>

                @php
                    $events = $repetition->repertoires->groupBy('event_id');
                @endphp

                @foreach($events as $eventId => $items)
                    @php $event = $items->first()->event; @endphp
                    <div
                        style="font-weight: bold; color: #4834D4; margin-top: 15px; font-size: 14px; border-bottom: 2px solid #eee; padding-bottom: 5px;">
                        Agenda : {{ $event->title }}
                    </div>
                    @foreach($items->groupBy(fn($i) => $i->partieEvent->titre ?? 'Autre') as $partie => $entries)
                        <div class="part-header">{{ $partie }}</div>
                        <ul class="chant-list">
                            @foreach($entries as $entry)
                                <li class="chant-item">
                                    <strong>{{ $entry->chant->title }}</strong><br>
                                    <small>{{ $entry->chant->composer ?: 'Chef de Choeur' }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endforeach
            @endif

            @if($repetition->description)
                <p><strong>Note :</strong><br><i>{{ $repetition->description }}</i></p>
            @endif

            <p>Nous comptons sur votre présence effective et ponctuelle.</p>
            <p>Musicalement,</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} - {{ config('app.name') }}. Tous droits réservés.
        </div>
    </div>
</body>

</html>