<!DOCTYPE html>
<html>
<head>
    <title>Suivi Entreprises Export</title>
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
    <h1>Suivi Entreprises</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Activité</th>
                <th>ICE</th>
                <th>Siège Social</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entreprises as $entreprise)
                <tr>
                    <td>{{ $entreprise->nom }}</td>
                    <td>{{ $entreprise->activite_principale ?? 'N/A' }}</td>
                    <td>{{ $entreprise->ice ?? 'N/A' }}</td>
                    <td>{{ $entreprise->siege_social ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
