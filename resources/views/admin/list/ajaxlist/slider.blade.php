<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>S.N</th>
                <th>Position</th>
                <th>Title</th>
                <th>Image</th>
                <th>Status</th>
                <th>Hide Text</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="sortable">
            @foreach($sliders as $key => $slider)
            <tr  data-id="{{$slider->id}}">
                <td>
                    {{$key+1}}
                </td>
                <td>{{$slider->order}}</td>
                <td>
                    {{ucwords($slider->title)}}
                </td>
                <td>
                    <img src="{{asset($slider->image)}}" alt="{{$slider->name}}" height="100" width="100">
                </td>
                <td>
                    @if($slider->publish_status == 1)
                    <span class="btn btn-info waves-effect waves-float waves-light">Active</span>
                    @else
                    <span class="btn btn-warning waves-effect waves-float waves-light">Banned</span>
                    @endif
                </td>
                <td>
                    @if($slider->hide_status == 1)
                    <span class="btn btn-info waves-effect waves-float waves-light">On</span>
                    @else
                    <span class="btn btn-warning waves-effect waves-float waves-light">Off</span>
                    @endif
                </td>
                <td>
                    <a href="{{route('slider.edit',$slider->id)}}" class="btn btn-info waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Edit">Edit</a>
                    <a href="{{route('slider.destroy',$slider->id)}}" class="btn btn-warning waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do You Really Wanna Delete?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$sliders->links()}}

</div>




@push('script')
<script src="{{ asset('js/sortablejs/jquery-ui.min.js') }}"></script>
<script>
    $( function() {
        $( "#sortable" ).sortable({
            stop: function(event, ui) {
                var data = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('slider-order.update') }}",
                    data: {
                        order: data
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            }
        });
    });
</script>
@endpush