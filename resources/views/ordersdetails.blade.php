@extends('./layout/default')
@section('add-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @php
        foreach ($orders as $item) {
            $orderid = $item->id;
            $order_id = $item->order_id;
            $custname = $item->cust_name;
            $request = $item->request_date;
            $send = $item->send_date;
            $status = $item->status;
            $updateAt = $item->updated_at;
            $sendAt = $item->sending_at;
            $completeAt = $item->complete_at;
            $cancelAt = $item->cancel_at;
        }
    @endphp
    @if ($status == 'pending')
        <button class="btn btn-primary w-auto" type="button" data-bs-toggle="modal" data-bs-target="#add_item">Add
            Items</button>
    @endif
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

    <div class="row">
        <div class="col-12">
            <div class="row">
                <hr>
                <div class="col">ORDER ID</div>
                <div class="col">: {{ $order_id }}</div>
            </div>
            <div class="row">
                <div class="col">Cust Name</div>
                <div class="col">: {{ $custname }}</div>
            </div>
            <div class="row">
                <div class="col">Request date</div>
                <div class="col">: {{ $request }}</div>
            </div>
            <div class="row">
                <div class="col">Send date</div>
                <div class="col">: {{ $send }}</div>
            </div>

            <div class="row">
                <div class="col">Status</div>
                <div class="col">:
                    @switch($status)
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
                </div>
            </div>

            @if ($status == 'sending')
                <div class="row">
                    <div class="col">Send at</div>
                    <div class="col">: {{ $sendAt }}</div>
                </div>
            @endif
            @if ($status == 'cancel')
                <div class="row">
                    <div class="col">Cancel at</div>
                    <div class="col">: {{ $cancelAt }}</div>
                </div>
            @endif
            @if ($status == 'complete')
                <div class="row">
                    <div class="col">Send at</div>
                    <div class="col">: {{ $sendAt }}</div>
                </div>
                <div class="row">
                    <div class="col">Complete at</div>
                    <div class="col">: {{ $completeAt }}</div>
                </div>
            @endif
        </div>
        <div class="col-12 mt-2">
            <div class="row">
                <hr>
                <div class="col text-center">
                    <h3>ITEM LIST</h3><br>
                </div>
            </div>
            <div class="row">
                <table class="table table-hover table-striped">
                    <thead>
                        <th></th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price (IDR)</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        @php
                            $grand_total = 0;
                            $grand_qty = 0;
                            $grand_price = 0;
                        @endphp
                        @foreach ($items as $item)
                            @php
                                $total = $item->qty * $item->price;
                                $grand_total += $total;
                                $grand_qty += $item->qty;
                                $grand_price += $item->price;
                            @endphp
                            <tr>
                                <td>
                                    @if ($status == 'pending')
                                        <a class="deleteItem btn btn-xs btn-danger" data-name="{{ $item->name }}"
                                            data-id="{{ $item->orders_items_id }}">Delete</a>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ number_format($item->qty, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ 'IDR ' . number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Grand Total</th>
                            <th></th>
                            <th>{{ number_format($grand_qty, 0, ',', '.') }}</th>
                            <th>{{ number_format($grand_price, 0, ',', '.') }}</th>
                            <th>{{ 'IDR ' . number_format($grand_total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="float-end mt-2">
                @if ($status == 'pending')
                    <a href="#" id="cancelBtn" class="btn btn-lg btn-default">Cancel</a>
                    <a href="#" id="sendBtn" class="btn btn-lg btn-primary">Send Order</a>
                @endif

                @if ($status == 'sending')
                    <a href="#" id="cancelBtn" class="btn btn-lg btn-danger text-white">Cancel</a>
                    <a href="#" id="doneBtn" class="btn btn-lg btn-success text-white">Complete</a>
                @endif
            </div>
        </div>
    </div>
    {{-- Modal Add Item  --}}
    <div class="modal fade" id="add_item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><b>Add Item</b></h5>
                </div>
                <form action="{{ route('orders.add_order_item') }}" id="form" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="text" name="order_id" value="{{ $orderid }}" hidden />
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example" name="items_id">
                                @foreach ($items_list as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_code . ' - ' . $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="floatingInput">Item Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="price" id="floatingInput"
                                placeholder="Price/pcs" @required(true)>
                            <label for="floatingInput">Price/pcs</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" name="qty" id="floatingInput"
                                placeholder="Qty">
                            <label for="floatingInput">Qty</label>
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
    <script>
        document.getElementById('cancelBtn').onclick = function() {
            if (confirm('Are you sure?')) {
                window.location = 'cancel/{{ $orderid }}'
            }
        };
        document.getElementById('sendBtn').onclick = function() {
            if (confirm('Are you sure?')) {
                window.location = 'send/{{ $orderid }}'
            }
        };
    </script>
    <script>
        document.getElementById('doneBtn').onclick = function() {
            if (confirm('Are you sure?')) {
                window.location = 'complete/{{ $orderid }}'
            }
        };
    </script>
    <script>
        $(".deleteItem").click(function() {
            var name = $(this).data("name");
            if (confirm('Are you sure to delete Item "' + name + '" from table?')) {
                var id = $(this).data("id");
                $(this).parents('tr').hide();
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
