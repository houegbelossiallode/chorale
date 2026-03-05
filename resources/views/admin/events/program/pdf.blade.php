<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 1cm;
            font-family: sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .chorale-name {
            font-weight: bold;
            font-size: 13pt;
            display: block;
        }

        .event-title {
            font-weight: bold;
            font-size: 12pt;
            margin-top: 5px;
        }

        .container {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .partie {
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }

        .lyrics {
            font-family: serif;
            font-size: 10pt;
            white-space: pre-wrap;
            margin-top: 3px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="header">
        <span class="chorale-name">Paroisse Ste Mère Térésa - Chapelle St Oscar Romero</span>
        <div class="event-title">{{ $event->title }}</div>
        <div>{{ \Carbon\Carbon::parse($event->start_at)->translatedFormat('j F Y') }}</div>
    </div>

    <div class="container">
        <div class="column">
            @foreach($col1 as $item)
                <div class="section">
                    <div><span class="partie">{{ $item->partie_titre }}</span> : <strong>{{ $item->chant_title }}</strong>
                    </div>
                    <div class="lyrics">{!! $item->parole !!}</div>
                </div>
            @endforeach
        </div>
        <div class="column">
            @foreach($col2 as $item)
                <div class="section">
                    <div><span class="partie">{{ $item->partie_titre }}</span> : <strong>{{ $item->chant_title }}</strong>
                    </div>
                    @if($item->parole)
                        <div class="lyrics">{!! $item->parole !!}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>