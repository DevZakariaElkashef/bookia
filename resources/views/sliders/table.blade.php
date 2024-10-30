<div class="table-responsive">
    <table class="table mg-b-0 text-md-nowrap">
        <thead>
            <tr>
                <th class="text-end p-1"><input type="checkbox" id="selectAllInputs"></th>
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('image') }}</th>
                <th>{{ __('url') }}</th>
                <th>{{ __('status') }}</th>
                <th>{{ __('actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sliders as $slider)
                <tr>
                    <th class="text-end p-1"><input type="checkbox" class="checkbox-input" value="{{ $slider->id }}"></th>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $slider->title }}</td>
                    <td>
                        <a href="{{ asset($slider->image) }}" download>
                            <img src="{{ asset($slider->image) }}" style="display: inline-block; border-radius: 50%;"
                                width="40" height="40" alt="">
                        </a>
                    </td>
                    <td>{{ $slider->url }}</td>
                    <td>
                        <label class="custom-toggle-switch">
                            <input type="checkbox" class="custom-toggle-input" data-id="{{ $slider->id }}"
                                data-url="{{ route('slider.toggleStatus', $slider->id) }}"
                                {{ $slider->is_active ? 'checked' : '' }}>
                            <span class="custom-toggle-slider"></span>
                        </label>
                    </td>



                    <td>
                        <a href="{{ route('sliders.edit', $slider->id) }}"
                            class="btn btn-secondary">{{ __('edit') }}</a>

                        <a href="#" class="btn btn-danger delete-btn" data-toggle="modal"
                            data-effect="effect-flip-vertical"
                            data-url="{{ route('sliders.destroy', $slider->id) }}"
                            data-target="#deletemodal">{{ __('delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="my-3">{{ $sliders->links() }}</div>
</div>
