<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Financier - {{ $monthName }} {{ $year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: #2F2B3D;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            background-color: #fff;
        }

        .page-container {
            padding: 40px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 1px solid #DBDADE;
            padding-bottom: 20px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background-color: #7367F0;
            border-radius: 8px;
            display: inline-block;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #444050;
            letter-spacing: -0.5px;
        }

        .doc-title {
            text-align: right;
        }

        .doc-title h1 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #7367F0;
            text-transform: uppercase;
        }

        .doc-title p {
            margin: 4px 0 0;
            color: #6D687D;
            font-size: 10px;
        }

        /* Summary Cards */
        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 15px 0;
            margin-left: -15px;
            margin-right: -15px;
            margin-bottom: 40px;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            padding: 16px;
            background: #F8F7FA;
            border-radius: 12px;
            border: 1px solid #DBDADE;
            vertical-align: top;
        }

        .card-label {
            font-size: 9px;
            font-weight: 600;
            color: #6D687D;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 16px;
            font-weight: 700;
            color: #444050;
        }

        .value-recette {
            color: #28C76F;
        }

        .value-depense {
            color: #EA5455;
        }

        .value-solde {
            color: #7367F0;
        }

        /* Tables */
        .section-header {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header h2 {
            font-size: 12px;
            font-weight: 700;
            color: #444050;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #F8F7FA;
            color: #444050;
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #DBDADE;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #F1F0F2;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-recette {
            background-color: #DFF7E9;
            color: #28C76F;
        }

        .badge-depense {
            background-color: #FCEAEA;
            color: #EA5455;
        }

        .font-bold {
            font-weight: 600;
        }

        .text-muted {
            color: #6D687D;
            font-size: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #DBDADE;
            text-align: center;
            font-size: 9px;
            color: #A5A2AD;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(115, 103, 240, 0.03);
            z-index: -1;
            font-weight: 900;
            white-space: nowrap;
        }

        .no-print-banner {
            background: #EEEBFF;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            color: #7367F0;
            font-size: 11px;
            border-bottom: 1px solid #D1C8FF;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .page-container {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="no-print no-print-banner">
        ✨ Design Premium Activé • Utilisez <b>Ctrl+P</b> pour exporter en PDF de haute qualité
    </div>

    <div class="watermark">CHORALE APP</div>

    <div class="page-container">
        <!-- Header -->
        <div class="header">
            <div class="brand">
                <div class="brand-logo"></div>
                <span class="brand-name">CHORALE APP</span>
            </div>
            <div class="doc-title">
                <h1>Rapport Financier</h1>
                <p>Période : {{ $monthName }} {{ $year }}</p>
                <p>Généré le {{ date('d/m/Y') }}</p>
            </div>
        </div>

        <!-- summary cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="card-label">Flux de Recettes</div>
                <div class="card-value value-recette">{{ number_format($totalRecettes, 0, ',', ' ') }}
                    <small>€</small>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-label">Flux de Dépenses</div>
                <div class="card-value value-depense">{{ number_format($totalDepenses, 0, ',', ' ') }}
                    <small>€</small>
                </div>
            </div>
            <div class="summary-card" style="background-color: #EEEBFF; border-color: #D1C8FF;">
                <div class="card-label">Solde Net</div>
                <div class="card-value value-solde">{{ number_format($solde, 0, ',', ' ') }} <small>€</small></div>
            </div>
        </div>

        <!-- Categories -->
        <div class="section-header">
            <h2>Répartition par Analyse Catégorielle</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Désignation de la Catégorie</th>
                    <th>Nature</th>
                    <th class="text-right">Volume Financier</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statsParCategorie as $stat)
                    <tr>
                        <td class="font-bold">{{ $stat['libelle'] }}</td>
                        <td>
                            <span class="badge {{ $stat['type'] === 'recette' ? 'badge-recette' : 'badge-depense' }}">
                                {{ strtoupper($stat['type']) }}
                            </span>
                        </td>
                        <td class="text-right font-bold">{{ number_format($stat['total'], 0, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Transactions -->
        <div class="section-header" style="margin-top: 40px;">
            <h2>Grand Livre des Transactions</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th width="80">Date</th>
                    <th>Libellé / Description</th>
                    <th>Catégorie</th>
                    <th class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                    <tr>
                        <td class="text-muted">{{ $t->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="font-bold">{{ $t->description }}</div>
                            @if($t->reference)
                                <div class="text-muted" style="font-size: 8px;">REF: {{ $t->reference }}</div>
                            @endif
                        </td>
                        <td>{{ $t->categorie->libelle }}</td>
                        <td class="text-right font-bold {{ $t->type === 'recette' ? 'value-recette' : 'value-depense' }}">
                            {{ $t->type === 'recette' ? '+' : '-' }}{{ number_format($t->montant, 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            Document officiel d'administration financière généré par le système Chorale App.<br>
            &copy; {{ date('Y') }} Administration Chorale. Tous droits réservés.
        </div>
    </div>
</body>

</html>