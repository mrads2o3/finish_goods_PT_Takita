@extends('./layout/default')
@section('add-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <button class="btn btn-primary w-auto" type="button" data-bs-toggle="modal" data-bs-target="#add_transaction">Add
        Transaction</button>
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
    <table id="transaction" class="table table-hover">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Date</th>
                <th>Item Name</th>
                <th>Shift</th>
                <th>Jumlah (Pcs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $txs)
                <tr>
                    <td class="text-center pt-4">
                        <a class="deleteTxs btn btn-xs btn-danger" data-name="{{ $txs->item_name }}"
                            data-date="{{ $txs->transaction_date }}" data-id="{{ $txs->id }}">Delete</a>
                    </td>
                    <td>
                        {{ $txs->transaction_date }}
                    </td>
                    <td>
                        {{ $txs->item_name }}
                    </td>
                    <td>
                        {{ $txs->shift }}
                    </td>
                    <td>
                        <a href="#" class="update" data-name="jumlah" data-type="number" data-pk="{{ $txs->id }}"
                            data-title="Edit jumlah">{{ $txs->jumlah }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Add Transaction  --}}
    <div class="modal fade" id="add_transaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><b>Add Transaction</b></h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <form action="{{ route('transactions.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span>Item Name :</span>
                        <select name="item_id" class="form-select mb-3" aria-label="Default select example">
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_code }} - {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="transaction_date" id="floatingInput"
                                placeholder="Date" @required(true)>
                            <label for="floatingInput">Date</label>
                        </div>
                        <span>Shift :</span>
                        <select name="shift" class="form-select mb-3" aria-label="Default select example">
                            <option value="1">Shift : 1</option>
                            <option value="2">Shift : 2</option>
                        </select>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="jumlah" id="floatingInput"
                                placeholder="Jumlah" @required(true)>
                            <label for="floatingInput">Jumlah</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Add Transaction --}}
@endsection
@section('add-js')
    <script type="text/javascript">
        let table = new DataTable('#transaction', {
            "columnDefs": [{
                "width": "10%",
                "targets": 0
            }],
            responsive: true
        });

        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // var taskAssignedUserDataArray = [{
        //         id: 'apples',
        //         text: 1011
        //     },
        //     {
        //         id: 'oranges',
        //         text: 'User 2'
        //     },
        //     {
        //         id: 'pie',
        //         text: 'User 3'
        //     },
        //     {
        //         id: 3,
        //         text: 'User 4'
        //     },
        //     {
        //         id: 4,
        //         text: 'User 5'
        //     }
        // ];

        $('.update').editable({
            url: "{{ route('transactions.update') }}",
            type: 'text',
            pk: 1,
            name: 'name',
            title: 'Enter name',
            // select2: {
            //     multiple: true,
            //     separator: ","
            // },
            // source: taskAssignedUserDataArray
            // display: function(value) {
            //     var output = [];

            //     if (!$.isArray(value)) {
            //         value = value.split(',');
            //     }

            //     $.each(value, function(i) {
            //         output.push("<span class='label'>" + $('<p>' + value[i] + '</p>').text() +
            //             "</span>");
            //     });

            //     $(this).html(output.join(" "));
            // }
        });

        $(".deleteTxs").click(function() {
            var name = $(this).data("name");
            var date = $(this).data("date");
            if (confirm('Are you sure to delete '+date +' / ' + name + ' from table?')) {
                $(this).parents('tr').hide();
                var id = $(this).data("id");
                var token = '{{ csrf_token() }}';
                $.ajax({
                    method: 'POST',
                    url: "transactions/delete/" + id,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        alert('Successfully!');
                    },
                    failure: function(data) {
                        alert('Failed!');
                    }
                });
            }
        });
    </script>
@endsection
