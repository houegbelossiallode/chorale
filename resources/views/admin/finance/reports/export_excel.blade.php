<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        /* Base styles for the viewer */
        table {
            border-collapse: collapse;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            font-size: 10pt;
        }

        .header {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }

        .subheader {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .total-label {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: right;
        }

        .total-value {
            font-weight: bold;
            text-align: right;
        }

        .recette {
            color: #10b981;
        }

        .depense {
            color: #ef4444;
        }
    </style>
</head>

<body>
    <table>
        <!-- Explicit column widths for Excel -->
        <colgroup>
            <col style="width: 100px;"> <!-- Date -->
            <col style="width: 300px;"> <!-- Description -->
            <col style="width: 100px;"> <!-- Type -->
            <col style="width: 150px;"> <!-- Catégorie -->
            <col style="width: 120px;"> <!-- Montant -->
            <col style="width: 150px;"> <!-- Référence -->
        </colgroup>

        <thead>
            <tr>
                <th colspan="6" class="header"
                    style="height: 50px; background-color: #4f46e5; color: #ffffff; font-size: 16pt;">
                    JOURNAL DE CAISSE — {{ strtoupper($monthName) }} {{ $year }}
                </th>
            </tr>
            <tr>
                <th colspan="6"
                    style="text-align: center; color: #94a3b8; font-size: 9pt; height: 30px; border-bottom: 2px solid #4f46e5;">
                    Document généré par la Chorale Saint Oscar Romero le {{ date('d/m/Y à H:i') }}
                </th>
            </tr>
            <tr class="subheader">
                <th style="background-color: #f8fafc; color: #64748b;">Date</th>
                <th style="background-color: #f8fafc; color: #64748b;">Description</th>
                <th style="background-color: #f8fafc; color: #64748b;">Flux</th>
                <th style="background-color: #f8fafc; color: #64748b;">Catégorie</th>
                <th style="background-color: #f8fafc; color: #64748b;">Montant (CFA)</th>
                <th style="background-color: #f8fafc; color: #64748b;">Référence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td style="text-align: center;">{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>{{ $t->description }}</td>
                    <td style="text-align: center; font-weight: bold;"
                        class="{{ $t->type == 'recette' ? 'recette' : 'depense' }}">
                        {{ strtoupper($t->type) }}
                    </td>
                    <td>{{ $t->categorie->libelle }}</td>
                    <td style="text-align: right; font-weight: 500;">{{ number_format($t->montant, 0, ',', ' ') }}</td>
                    <td style="color: #64748b; font-size: 9pt;">{{ $t->reference }}</td>
                </tr>
            @endforeach

            <!-- Spacing row -->
            <tr>
                <td colspan="6" style="border: none; height: 20px;"></td>
            </tr>

            <!-- Totals Section -->
            <tr>
                <td colspan="4" class="total-label">CUMUL DES RECETTES (+)</td>
                <td class="total-value recette">{{ number_format($totalRecettes, 0, ',', ' ') }}</td>
                <td style="background-color: #f1f5f9;"></td>
            </tr>
            <tr>
                <td colspan="4" class="total-label">CUMUL DES DÉPENSES (-)</td>
                <td class="total-value depense">{{ number_format($totalDepenses, 0, ',', ' ') }}</td>
                <td style="background-color: #f1f5f9;"></td>
            </tr>
            <tr>
                <td colspan="4" class="total-label" style="background-color: #4f46e5; color: #ffffff; height: 35px;">
                    SOLDE DE PÉRIODE</td>
                <td class="total-value" style="background-color: #4f46e5; color: #ffffff; font-size: 12pt;">
                    {{ number_format($totalRecettes - $totalDepenses, 0, ',', ' ') }}
                </td>
                <td style="background-color: #4f46e5;"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>