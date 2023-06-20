@extends('./layout/default')
@section('add-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <button class="btn btn-primary w-auto" type="button" data-bs-toggle="modal" data-bs-target="#add_item">Add Item</button>
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
                <th class="text-center">#</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Ukuran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemList as $item)
                <tr>
                    <td class="text-center pt-4">
                        <a class="deleteItem btn btn-xs btn-danger" data-name="{{ $item->name }}"
                            data-id="{{ $item->id }}">Delete</a>
                    </td>
                    <td>
                        <a href="#" class="update" data-name="item_code" data-type="text"
                            data-pk="{{ $item->id }}" data-title="Edit code">{{ $item->item_code }}
                        </a>
                    </td>
                    <td>
                        <a href="#" class="update" data-name="name" data-type="text" data-pk="{{ $item->id }}"
                            data-title="Edit name">{{ $item->name }}
                        </a>
                    </td>

                    <td>
                        <a href="#" class="update" data-name="ukuran" data-type="text" data-pk="{{ $item->id }}"
                            data-title="Edit ukuran">{{ $item->ukuran }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Add Item  --}}
    <div class="modal fade" id="add_item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><b>Add Item</b></h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <form action="{{ route('items.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="item_code" id="floatingInput"
                                placeholder="Item Code" @required(true)>
                            <label for="floatingInput">Item Code</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="item_name" id="floatingInput"
                                placeholder="Item Name" @required(true)>
                            <label for="floatingInput">Item Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" name="ukuran" id="floatingInput"
                                placeholder="Ukuran">
                            <label for="floatingInput">Ukuran</label>
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
