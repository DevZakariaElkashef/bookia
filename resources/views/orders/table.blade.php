<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="text-end p-1"><input type="checkbox" id="selectAllInputs"></th>
                <th>{{ __('id') }}</th>
                <th>{{ __('date') }}</th>
                <th>{{ __('client') }}</th>
                <th>{{ __('payment_type') }}</th>
                <th>{{ __('status') }}</th>
                <th>{{ __('total') }}</th>
                <th>{{ __('actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <th class="text-end p-1"><input type="checkbox" class="checkbox-input" value="{{ $order->id }}">
                    </th>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <div class="">
                            {{ $order->user->name ?? '' }} <br>
                            <small>{{ $order->user->phone ?? '' }}</small>
                        </div>
                    </td>
                    <td>{{ $order->payment_type ? 'Online' : 'Cash' }}</td>
                    <td>{{
                        match ($order->status) {
                            0 => "pending",
                            1 => "complete",
                            2 => "return",
                        }
                    }}
                    </td>
                    <td>{{ $order->total }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}"
                            class="btn btn-primary">{{ __('show') }}</a>

                        <a href="#" class="btn btn-danger delete-btn" data-toggle="modal"
                            data-effect="effect-flip-vertical" data-url="{{ route('orders.destroy', $order->id) }}"
                            data-target="#deletemodal">{{ __('delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="my-3">{{ $orders->links() }}</div>
</div>
