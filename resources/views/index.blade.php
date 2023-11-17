@extends('./layout/default')

@section('content')

    @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <h2 class='text-center'>STOCK OVERVIEW</h2>
    <table id="dashboard" class="table table-hover">
        <thead>
            <tr>
                <th>Code</th>
                <th>Item Name</th>
                <th>Sending</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->sending_qty }}</td>
                    <td>{{ number_format($item->stock, 0, ',', '.') }}</td>
                </tr>
                @php
                    $total += $item->stock;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Grand Total</th>
                <th>{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('add-js')
    <script>
        let table = new DataTable('#dashboard', {
            "columnDefs": [{
                "width": "10%",
                "targets": 0
            }],
            responsive: true,
        });
    </script>
@endsection
