<!DOCTYPE html>
<html>
<head>
    <title>TVA Annuelle Export</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>TVA Annuelle</h1>
    <table>
        <thead>
            <tr>
                <th>Entreprise</th>
                <th>Période</th>
                <th>Montant</th>
                <th>Date de Déclaration</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($declarations as $declaration)
                <tr>
                    <td>{{ $declaration->entreprise->nom }}</td>
                    <td>{{ $declaration->periode }}</td>
                    <td>{{ number_format($declaration->montant, 2, ',', ' ') }} €</td>
                    <td>{{ \Carbon\Carbon::parse($declaration->date_declaration)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
