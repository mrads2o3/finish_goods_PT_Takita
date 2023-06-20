@extends('./layout/default')

@section('content')
    <h2 class='text-center'>OVERVIEW</h2>
    <table id="dashboard" class="table table-hover">
        <thead>
            <tr>
                <th>Date</th>
                <th>Code</th>
                <th>Item Name</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->transaction_date }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
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
