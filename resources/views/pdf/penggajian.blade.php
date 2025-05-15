<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; } /* 👈 Tambahkan ini */
    </style>
</head>
<body>
    <h2>Slip Gaji</h2>
    <table>
        <thead>
            <tr>
                <th>ID Gaji</th>
                <th>ID Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Total Hari Kerja</th>
                <th>Gaji</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $penggajian->id_gaji }}</td>
                <td>{{ $penggajian->id_karyawan }}</td>
                <td>{{ $penggajian->nama_karyawan }}</td>
                <td>{{ $penggajian->jabatan }}</td>
                <td>{{ $penggajian->total_jam_kerja }}</td>
                <td class="text-right">Rp{{ number_format($penggajian->gaji, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
