<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Data Absensi</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Total Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->karyawan->id_karyawan ?? '-' }}</td>
                    <td>{{ $item->nama_karyawan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->jam_masuk }}</td>
                    <td>{{ $item->jam_keluar }}</td>
                    <td>{{ $item->total_jam_hari_ini }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
