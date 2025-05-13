<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Déclarations CNSS - {{ $monthName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge-success {
            color: green;
        }
        .badge-warning {
            color: orange;
        }
    </style>
</head>
<body>
    <h1>Déclarations CNSS - {{ $monthName }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>Entreprise</th>
                <th>Mois</th>
                <th>Année</th>
                <th>Nombre de Salariés</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($declarations as $declaration)
                <tr>
                    <td>{{ $declaration->entreprise->nom ?? 'N/A' }}</td>
                    <td>{{ $declaration->french_month }}</td>
                    <td>{{ $declaration->annee }}</td>
                    <td>{{ $declaration->Nbr_Salries ?? 'Non déclaré' }}</td>
                    <td class="badge-{{ $declaration->etat === 'valide' ? 'success' : 'warning' }}">
                        {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>