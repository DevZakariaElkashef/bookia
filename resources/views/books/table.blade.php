<div class="table-responsive">
    <table class="table mg-b-0 text-md-nowrap">
        <thead>
            <tr>
                <th class="text-end p-1"><input type="checkbox" id="selectAllInputs"></th>
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('category') }}</th>
                <th>{{ __('image') }}</th>
                <th>{{ __('price') }}</th>
                <th>{{ __('offer') }}</th>
                <th>{{ __('status') }}</th>
                <th>{{ __('actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    <th class="text-end p-1"><input type="checkbox" class="checkbox-input" value="{{ $book->id }}"></th>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $book->name }}</td>
                    <td>{{ $book->category->name ?? '' }}</td>
                    <td>
                        <a href="{{ asset($book->image) }}" download>
                            <img src="{{ asset($book->image) }}" style="display: inline-block; border-radius: 50%;"
                                width="40" height="40" alt="">
                        </a>
                    </td>


                    <td>{{ $book->price }}</td>
                    <td>{{ $book->offer }}</td>
                    <td>
                        <label class="custom-toggle-switch">
                            <input type="checkbox" class="custom-toggle-input" data-id="{{ $book->id }}"
                                data-url="{{ route('book.toggleStatus', $book->id) }}"
                                {{ $book->is_active ? 'checked' : '' }}>
                            <span class="custom-toggle-slider"></span>
                        </label>
                    </td>

                    <td>
                        <a href="{{ route('books.edit', $book->id) }}"
                            class="btn btn-secondary">{{ __('edit') }}</a>

                        <a href="#" class="btn btn-danger delete-btn" data-toggle="modal"
                            data-effect="effect-flip-vertical"
                            data-url="{{ route('books.destroy', $book->id) }}"
                            data-target="#deletemodal">{{ __('delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="my-3">{{ $books->links() }}</div>
</div>
