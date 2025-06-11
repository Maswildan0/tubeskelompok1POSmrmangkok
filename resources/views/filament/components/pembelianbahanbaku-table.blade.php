<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">ID Pembelian</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Pembayaran</th>
            <th class="border border-gray-300 px-4 py-2">Jumlah</th>
            <th class="border border-gray-300 px-4 py-2">Total Pembelian</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembayarans as $pembayaran)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $pembayaran->id_pembelian }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $pembayaran->tgl }}</td>
                <td class="border border-gray-300 px-4 py-2">Rp{{ number_format($pembayaran->tagihan, 0, ',', '.') }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $pembayaran->total_pembelian}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
