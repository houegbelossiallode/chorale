<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Financier - {{ $monthName }} {{ $year }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #7367F0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #7367F0;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-weight: bold;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }

        .summary-card {
            flex: 1;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card.recettes {
            border-left: 5px solid #28C76F;
        }

        .summary-card.depenses {
            border-left: 5px solid #EA5455;
        }

        .summary-card.solde {
            border-left: 5px solid #7367F0;
            background-color: #f8f7fa;
        }

        .card-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .card-value {
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8f7fa;
            color: #444;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .type-recette {
            color: #28C76F;
            font-weight: bold;
        }

        .type-depense {
            color: #EA5455;
            font-weight: bold;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #7367F0;
            margin: 30px 0 15px;
            border-left: 3px solid #7367F0;
            padding-left: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="no-print"
        style="background: #f1f0ff; padding: 10px; text-align: center; margin-bottom: 20px; border-radius: 8px; font-weight: bold; color: #7367F0;">
        💡 Astuce : Utilisez Ctrl+P (Windows) ou Cmd+P (Mac) et choisissez "Enregistrer au format PDF".
    </div>

    <div class="header">
        <h1>Rapport Financier Mensuel</h1>
        <p>Chorale App - {{ $monthName }} {{ $year }}</p>
    </div>

    <div class="summary-grid">
        <div class="summary-card recettes">
            <div class="card-label">Total Recettes</div>
            <div class="card-value">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="summary-card depenses">
            <div class="card-label">Total Dépenses</div>
            <div class="card-value">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="summary-card solde">
            <div class="card-label">Solde du mois</div>
            <div class="card-value">{{ number_format($solde, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    <div class="section-title">Répartition par Catégorie</div>
    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Type</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statsParCategorie as $stat)
                <tr>
                    <td>{{ $stat['libelle'] }}</td>
                    <td class="{{ $stat['type'] === 'recette' ? 'type-recette' : 'type-depense' }}">
                        {{ ucfirst($stat['type']) }}
                    </td>
                    <td class="text-right">{{ number_format($stat['total'], 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Détail des Transactions</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>{{ $t->description }}</td>
                    <td>{{ $t->categorie->libelle }}</td>
                    <td class="text-right {{ $t->type === 'recette' ? 'type-recette' : 'type-depense' }}">
                        {{ $t->type === 'recette' ? '+' : '-' }} {{ number_format($t->montant, 0, ',', ' ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ date('d/m/Y à H:i') }} | Chorale App Administration
    </div>

    <script>
        // Optionnel : Déclencher directement l'impression
        // window.onload = () => window.print();
    </script>
</body>

</html>