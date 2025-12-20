<!DOCTYPE html>
<html>
<head>
    <title>Inventaire {{ $inventaire->numero_inventaire }}</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .text-center { text-center; }
    </style>
</head>
<body>
    <h2>Inventaire {{ $inventaire->numero_inventaire }}</h2>
    <p><strong>Date:</strong> {{ $inventaire->date_inventaire->format('d/m/Y') }}</p>
    <p><strong>Dépôt:</strong> {{ $inventaire->depot->designation }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Qté Théorique</th>
                <th>Qté Physique</th>
                <th>Écart</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventaire->details as $detail)
            <tr>
                <td>{{ $detail->article->designation }}</td>
                <td class="text-center">{{ $detail->quantite_theorique }}</td>
                <td class="text-center">{{ $detail->quantite_physique }}</td>
                <td class="text-center">{{ $detail->ecart_quantite }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <script>window.print();</script>
</body>
</html>