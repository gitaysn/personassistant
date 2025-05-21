<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Akhir - {{ $jenis }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Hasil Akhir Perhitungan<br>Jenis Pakaian: {{ $jenis }}</h2>

    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>Nama Alternatif</th>
                <th>Skor Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($skorAkhir as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['skor'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
