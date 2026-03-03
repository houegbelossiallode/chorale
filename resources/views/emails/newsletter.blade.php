<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
            text-transform: uppercase;
        }

        .content {
            padding: 40px 30px;
            line-height: 1.6;
            color: #334155;
        }

        .content h2 {
            color: #1e293b;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 14px 30px;
            background-color: #0f172a;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            transition: background-color 0.2s;
        }

        .footer {
            background-color: #f1f5f9;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
        }

        .signature {
            font-style: italic;
            color: #475569;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 40px; margin-bottom: 10px;">🎶</div>
            <h1>Chorale Saint Oscar Romero</h1>
        </div>

        <div class="content">
            <h2>{{ $subjectText }}</h2>

            <div style="white-space: pre-wrap;">{!! $content !!}</div>

            <div class="divider"></div>

            <p class="signature">
                Cordialement,<br>
                <strong>L'Équipe de la Chorale</strong>
            </p>

            <div class="button-container">
                <a href="{{ route('home') }}" class="button">Visiter notre site web</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Chorale Saint Oscar Romero. Tous droits réservés.</p>
            <p>Vous recevez cet email car vous êtes inscrit à notre newsletter.</p>
            <p><a href="{{ route('home') }}">Se désabonner</a> • <a href="{{ route('home') }}">Contactez-nous</a></p>
        </div>
    </div>
</body>

</html>