<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_category as $key => $cat)
            <tr>
                <td>
                    {{$key+1}}
                </td>
                <td>
                    {{ucwords(@$cat->getCategory->title)}}
                </td>
                <td>
                    @if($cat->status == 1)
                    <span class="btn btn-info waves-effect waves-float waves-light">On</span>
                    @else
                    <span class="btn btn-warning waves-effect waves-float waves-light">Off</span>
                    @endif 
                </td>
                <td>
                    <a href="{{route('top-category.edit',$cat->id)}}" class="btn btn-info waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Edit">Edit</a>
                    <a href="javascript:;" class="btn btn-warning waves-effect waves-float waves-light delete-banner" data-toggle="tooltip" data-original-title="Delete" >Delete</a>


                    {{ Form::open(['url'=>route('top-category.delete',$cat->id),'class'=>'delete-form'])}}
                        @method('delete')
                    {{ Form::close()}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- {{$cat->links()}} --}}

</div>

@push('script')
<script>
    $(document).on('click','.delete-banner',function(e){

    e.preventDefault();
    let clicked=confirm('Are You Sure Want To Delete Banner');

    if(clicked)
    {
        $(this).parent().find('form').submit();
    }
    });
</script>
@endpush