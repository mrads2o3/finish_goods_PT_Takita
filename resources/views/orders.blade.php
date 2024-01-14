@extends('./layout/default')
@section('add-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <button class="btn btn-primary w-auto" type="button" data-bs-toggle="modal" data-bs-target="#add_orders">New Order</button>
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
    <table id="items" class="table table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Cust Name</th>
                <th>Request Date</th>
                <th>Send Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $item)
                <tr>
                    <td>
                        {{ $item->order_id }}
                    </td>
                    <td>
                        <a href="{{ url('orders/'.$item->id) }}">{{ $item->cust_name }}</a>
                    </td>
                    <td>
                        {{ $item->request_date }}
                    </td>
                    <td>
                        {{ $item->send_date }}
                    </td>
                    <td>
                        @switch($item->status)
                            @case('pending')
                                <span class="badge rounded-pill bg-warning text-light">Pending</span>
                            @break

                            @case('sending')
                                <span class="badge rounded-pill bg-success text-light">Sending</span>
                            @break

                            @case('cancel')
                                <span class="badge rounded-pill bg-danger text-light">Cancel</span>
                            @break

                            @case('complete')
                                <span class="badge rounded-pill bg-primary text-light">Complete</span>
                            @break

                            @default
                        @endswitch

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Add Orders  --}}
    <div class="modal fade" id="add_orders" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><b>Add New Order</b></h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <form action="{{ route('order.add') }}" id="form" method="POST">
                    <div class="modal-body">
                        @csrf
                        <select name="cust_id" id="custId" class="form-select mb-3" @required(true)>
                            <option> -- Select Cust Name -- </option>
                            @foreach ($cust as $data)
                                <option value="{{ $data->id }}">{{ $data->cust_name }}</option>
                            @endforeach
                        </select>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="request_date" id="floatingInput"
                                placeholder="Item Code" @required(true)>
                            <label for="floatingInput">Request date</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="send_date" id="floatingInput"
                                placeholder="Item Code" @required(true)>
                            <label for="floatingInput">Send date</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnFormSubmit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Add Item --}}
@endsection
@section('add-js')
    <script type="text/javascript">
        let table = new DataTable('#items', {
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

        $('.update').editable({
            url: "{{ route('items.update') }}",
            type: 'text',
            pk: 1,
            name: 'name',
            title: 'Enter name'
        });

        $(".deleteItem").click(function() {
            var name = $(this).data("name");
            if (confirm('Are you sure to delete "' + name + '" from table?')) {
                $(this).parents('tr').hide();
                var id = $(this).data("id");
                var token = '{{ csrf_token() }}';
                $.ajax({
                    method: 'POST',
                    url: "items/delete/" + id,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        alert('Successfuly!');
                    }
                });
            }
        });
    </script>
@endsection
