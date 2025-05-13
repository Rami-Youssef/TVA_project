<!DOCTYPE html>
<html>
<head>
    <title>Etats CNSS Export</title>
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
    <h1>Etats CNSS</h1>
    <table>
        <thead class="text-primary">
            <tr>
                <th>Entreprise</th>
                <th>Mois</th>
                <th>Année</th>
                <th>Nombre de Salariés</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($declarations as $declaration)
                <tr>
                    <td>{{ $declaration->entreprise->nom ?? 'N/A' }}</td>
                    <td>{{ $declaration->french_month }}</td>
                    <td>{{ $declaration->annee }}</td>
                    <td>{{ $declaration->Nbr_Salries }}</td>
                    <td>
                        <span class="badge badge-{{ $declaration->etat === 'valide' ? 'success' : 'warning' }}">
                            {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
