<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perkara</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .status-selesai {
            color: green;
            font-weight: bold;
        }

        .status-proses {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>DIVISI 2 KOSTRAD</h2>
        <h2>SISTEM INFORMASI PERKARA</h2>
        <p>Laporan Data Perkara</p>
        <p>Dicetak: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Nomor Perkara</th>
                <th width="20%">Jenis Perkara</th>
                <th width="15%">Kategori</th>
                <th width="12%">Tanggal Masuk</th>
                <th width="12%">Tanggal Selesai</th>
                <th width="10%" class="text-center">Status</th>
                <th width="11%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perkaras as $index => $perkara)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $perkara->nomor_perkara }}</td>
                    <td>{{ $perkara->jenis_perkara }}</td>
                    <td>{{ $perkara->kategori->nama ?? '-' }}</td>
                    <td>{{ $perkara->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>{{ $perkara->tanggal_selesai ? $perkara->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                    <td class="text-center {{ $perkara->status == 'Selesai' ? 'status-selesai' : 'status-proses' }}">
                        {{ $perkara->status }}
                    </td>
                    <td>{{ \Str::limit($perkara->keterangan ?? '-', 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Perkara: <strong>{{ $perkaras->count() }}</strong></p>
    </div>
</body>

</html>
