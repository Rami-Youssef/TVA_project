<!DOCTYPE html>
<html>
<head>
    <title>Entreprises Export</title>
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
    <h1>Entreprises List</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>ICE</th>
                <th>Téléphone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entreprises as $entreprise)
                <tr>
                    <td>{{ $entreprise->nom }}</td>
                    <td>{{ $entreprise->ice }}</td>
                    <td>{{ $entreprise->telephone }}</td>
                    <td>{{ $entreprise->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
