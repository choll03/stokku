<style>
    @media print {
        html, body {
            display: block;
            margin: 0 10px;
            font-size:10px;
        }

        @page {
            /* size: 57mm 50mm; */
            /* width: 57mm;
            height: 57mm; */
        }

        table, th, td {
            border: 1px solid gray;
            border-collapse: collapse;
        }

    }
</style>
<table width="100%" >
    <thead>
        <tr>
            <td>Barang</td>
            <td>Qty</td>
            <td>Ukuran Bahan</td>
            <td>jumlah Bahan (lembar)</td>
            <td>Harga Bahan</td>
            <td>Harga Produksi</td>
            <td>Harga Potong Bahan</td>
            <td>Biaya</td>
        </tr>
    </thead>
    <tbody>
        @foreach($dataRow as $data)
        <tr>
            <td>{{ $data->barangHtml }}</td>
            <td>{{ $data->qtyHtml }}</td>
            <td>{{ $data->ukuranBahanHtml }}</td>
            <td align="center">{{ $data->jmlBahanHtml }}</td>
            <td align="right">{{ number_format($data->totalHargaBahan,0, ',', '.') }}</td>
            <td align="right">{{ number_format($data->totalHargaProduksi,0, ',', '.') }}</td>
            <td align="right">{{ number_format($data->totalHargaPotongBahan,0, ',', '.') }}</td>
            <td align="right">{{ number_format($data->totalBiaya,0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td align="center">{{ $dataTotal->finalTotalBahan }}</td>
            <td align="right">{{ number_format($dataTotal->finalTotalHargaBahan,0, ',', '.') }}</td>
            <td align="right">{{ number_format($dataTotal->finalTotalHargaProduksi,0, ',', '.') }}</td>
            <td align="right">{{ number_format($dataTotal->finalTotalBiayaPotongBahan,0, ',', '.') }}</td>
            <td align="right">{{ number_format($dataTotal->finalTotalBiaya,0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
