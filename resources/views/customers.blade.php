@extends('./layout/default')
@section('add-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <button class="btn btn-primary w-auto" type="button" data-bs-toggle="modal" data-bs-target="#add_customer">Add Customer</button>
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
    <table id="customer" class="table table-hover">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Cust Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customer as $cust)
                <tr>
                    <td class="text-center pt-4">
                        <a class="deleteCust btn btn-xs btn-danger" data-name="{{ $cust->cust_name }}"
                            data-id="{{ $cust->id }}">Delete</a>
                    </td>
                    <td>
                        <a href="#" class="updateCust" data-name="cust_name" data-type="text"
                            data-pk="{{ $cust->id }}" data-title="Edit customer name">{{ $cust->cust_name }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Add Customer  --}}
    <div class="modal fade" id="add_customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><b>Add Item</b></h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <form action="{{ route('customers.add') }}" id="form" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="cust_name" id="floatingInput"
                                placeholder="Customer Name" @required(true)>
                            <label for="floatingInput">Customer Name</label>
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
    {{-- End Modal Add Customer --}}
@endsection
@section('add-js')
    <script type="text/javascript">
        let table = new DataTable('#customer', {
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

        $('.updateCust').editable({
            url: "{{ route('customers.update') }}",
            type: 'text',
            pk: 1,
            name: 'name',
            title: 'Enter name'
        });

        $(".deleteCust").click(function() {
            var name = $(this).data("name");
            if (confirm('Are you sure to delete "' + name + '" from table?')) {
                $(this).parents('tr').hide();
                var id = $(this).data("id");
                var token = '{{ csrf_token() }}';
                $.ajax({
                    method: 'POST',
                    url: "customers/delete/" + id,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        alert('Successfully!');
                    }
                });
            }
        });
    </script>
@endsection
