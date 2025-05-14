<!DOCTYPE html>
<html>
<head>
    <title>Déclarations CNSS - {{ $entreprise->nom }}</title>
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
        .etat-valide {
            color: green;
            font-weight: bold;
        }
        .etat-non-valide {
            color: orange;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Déclarations CNSS</h1>
    </div>
    
    <div class="company-info">
        <h2>{{ $entreprise->nom }}</h2>
        <p><strong>ICE:</strong> {{ $entreprise->ice }}</p>
        <p><strong>Siège Social:</strong> {{ $entreprise->siege_social }}</p>
        <p><strong>Forme Juridique:</strong> {{ $entreprise->form_juridique }}</p>
        <p><strong>Activité Principale:</strong> {{ $entreprise->activite_principale }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Mois</th>
                <th>Année</th>
                <th>Nombre de Salariés</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($declarations as $declaration)
                <tr>
                    <td>{{ $declaration->french_month }}</td>
                    <td>{{ $declaration->annee }}</td>
                    <td>{{ $declaration->Nbr_Salries }}</td>
                    <td class="{{ $declaration->etat === 'valide' ? 'etat-valide' : 'etat-non-valide' }}">
                        {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer" style="margin-top: 20px; text-align: center; font-size: 12px;">
        <p>Document généré le {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
