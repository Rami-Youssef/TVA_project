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
    <h1>Entreprises List</h1>    <table>        <thead>
            <tr>
                <th>Nom</th>
                <th>Siège Social</th>
                <th>Forme Juridique</th>
                <th>Activité Principale</th>
                <th>ICE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entreprises as $entreprise)                <tr>
                    <td>{{ $entreprise->nom }}</td>
                    <td>{{ $entreprise->siege_social }}</td>
                    <td>{{ $entreprise->form_juridique }}</td>
                    <td>{{ $entreprise->activite_principale }}</td>
                    <td>{{ $entreprise->ice }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
