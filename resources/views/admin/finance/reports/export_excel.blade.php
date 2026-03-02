<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        .header {
            background-color: #7367F0;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        .subheader {
            background-color: #F8F7FA;
            font-weight: bold;
        }

        .recette {
            color: #28C76F;
        }

        .depense {
            color: #EA5455;
        }

        .total-row {
            background-color: #f1f0ff;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #E6E6E6;
            padding: 8px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="6" class="header" height="30">RAPPORT FINANCIER - {{ strtoupper($monthName) }} {{ $year }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-size: 10px;">Généré par Chorale App le
                    {{ date('d/m/Y H:i') }}</th>
            </tr>
            <tr>
                <th colspan="6"></th>
            </tr>
            <tr class="subheader">
                <th width="15">Date</th>
                <th width="40">Description</th>
                <th width="15">Type</th>
                <th width="20">Catégorie</th>
                <th width="20">Montant (FCFA)</th>
                <th width="20">Référence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>{{ $t->description }}</td>
                    <td class="{{ $t->type == 'recette' ? 'recette' : 'depense' }}">{{ ucfirst($t->type) }}</td>
                    <td>{{ $t->categorie->libelle }}</td>
                    <td style="text-align: right;">{{ $t->montant }}</td>
                    <td>{{ $t->reference }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL RECETTES</td>
                <td style="text-align: right; color: #28C76F;">{{ $totalRecettes }}</td>
                <td></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL DÉPENSES</td>
                <td style="text-align: right; color: #EA5455;">{{ $totalDepenses }}</td>
                <td></td>
            </tr>
            <tr class="total-row" style="background-color: #7367F0; color: #ffffff;">
                <td colspan="4" style="text-align: right;">SOLDE NET</td>
                <td style="text-align: right;">{{ $totalRecettes - $totalDepenses }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>

</html>