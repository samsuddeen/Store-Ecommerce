@push('style')
<style>
    .scrollable {
      height: 500px;
      overflow: scroll;
    }
</style>
@endpush

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Category Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form action="{{ route('seller-product.update-category', $product) }}" method="post" id="category-edit-form">
                <div class="modal-body">
                    @method('PATCH')
                    @csrf
                    <h5 class="bg-danger text-white">Note: Please Update Attribute as well as price and stock after edit
                        of the category other wise the old data will be display</h5>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input data-bs-toggle="modal" data-bs-target="#exampleModal1" type="text"
                            name="category_element" class="form-control form-control-sm" id="category_element"
                            value="{{ old('category_element', @$category_element) }}" readonly>
                        <input type="hidden" name="category_id" id="category_id"
                            value="{{ old('category_id', @$product->category_id) }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form> --}}
            <form action="javascript:;" method="post" id="category-edit-form">
                <div class="modal-body">
                    @method('PATCH')
                    @csrf
                    <h5 class="bg-danger text-white">Note: Please Update Attribute as well as price and stock after edit
                        of the category other wise the old data will be display</h5>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input data-bs-toggle="modal" data-bs-target="#exampleModal1" type="text"
                            name="category_element" class="form-control form-control-sm" id="category_element"
                            value="{{ old('category_element', @$category_element) }}" readonly>
                        <input type="hidden" name="category_id" id="category_id"
                            value="{{ old('category_id', @$product->category_id) }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary updateCategoryNew">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



{{-- category modal --}}

<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h5 class="modal-title" id="exampleModalLabel1">Choose Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">

                <h1 class="text-center mb-1" id="CategegoryTypes">Select Category</h1>
                <p class="text-center">Search and select the category to assign</p>
                <form action="#" method="post" class="row gy-1 gx-2 mt-75" id="category-edit-form">
                    <div class="col-12">
                        <div class="form-floating">
                          <input
                            type="text"
                            class="form-control"
                            id="floating-label1"
                            placeholder="Search"
                          />
                          <label for="floating-label1">Search</label>
                        </div>
                      </div>
                      <hr />


                    <div class="col-12 row mt-2">
                        <div class="col-md-2 scrollable" id="first-level">
                            <div>
                                <ul class="list-group">
                                    @foreach ($categories as $category)
                                        <li class="list-group-item d-flex align-items-center category-selection"  data-parent_id=""
                                        data-id="{{ $category->id }}"
                                        data-child_count="{{ count($category->getDescendants()) }}" data-level="1">
                                            <span> {{ $category->title }}</span>
                                            <span class="badge bg-primary rounded-pill ms-auto">{{count($category->getDescendants())}}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2 scrollable" id="second-level">

                        </div>
                        <div class="col-md-2 scrollable" id="third-level">

                        </div>
                        <div class="col-md-2 scrollable" id="fourth-level">

                        </div>
                        <div class="col-md-2 scrollable" id="fifth-level">

                        </div>
                        <div class="col-md-2 scrollable" id="sixth-level">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="category-confirm"
                    data-bs-dismiss="modal">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


{{-- end category modal --}}



@push('script')
    <script>
        $(document).on('click','.updateCategoryNew',function(){
            let catFormData=document.getElementById('category-edit-form');
            let catId=catFormData['category_id'].value;
            let productId="{{@$product->id}}" ?? 0;
            $.ajax({
                url:"{{route('update-new-cat')}}",
                type:"get",
                data:{
                    catId:catId,
                    productId:productId
                },
                success:function(response){
                    $('#updateCatTable').replaceWith(response);
                    $('#exampleModal').modal('hide');
                    $('#updateCatIdValue').val(catId);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#category_element').on('click', function(e) {
                e.preventDefault();
            })
        });
        let category_id = null;
        let parent_ids = [];
        $(document).on('click', '.category-selection', function(e) {
            let level = $(this).data('level');
            category_id = $(this).data('id');
            let category_element = "";
            let count = $(this).data('child_count');
            let parent_id = $(this).data('parent_id');
            parent_ids.push(parent_id);
            $('.category-selection').css({
                "background-color": "",
                "color": "",
            });
            $(this).css({
                "background-color": "#7367f0",
                "color":"white"
            });
            $("[data-id]").filter(function() {
                return parent_ids.includes($(this).data("id"));
            }).css({
                "background-color": "#7367f0",
                "color":"white"
            });

            $.ajax({
                url: "{{ route('get-decendent') }}",
                type: 'get',
                data: {
                    category_id: category_id,
                    level: level
                },
                success: function(response) {
                    switch (parseInt(level)) {
                        case 1:
                            $('#second-level').html("");
                            $('#third-level').html("");
                            $('#fourth-level').html("");
                            $('#fifth-level').html("");
                            $('#sixth-level').html("");
                            $('#second-level').html(response);
                            break;
                        case 2:
                            $('#third-level').html("");
                            $('#fourth-level').html("");
                            $('#fifth-level').html("");
                            $('#sixth-level').html("");
                            $('#third-level').html(response);
                            break;
                        case 3:
                            $('#fourth-level').html("");
                            $('#fifth-level').html("");
                            $('#sixth-level').html("");
                            $('#fourth-level').html(response);
                            break;
                        case 4:
                            $('#fifth-level').html("");
                            $('#sixth-level').html("");
                            $('#fifth-level').html(response);
                            break;
                        case 5:
                            $('#sixth-level').html("");
                            $('#sixth-level').html(response);
                            break;
                        default:
                            break;
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
        $(document).on('click', '#category-confirm', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('get-acendent') }}",
                type: 'get',
                data: {
                    category_id: category_id,
                    product_id: "{{ $product->id }}"
                },
                success: function(response) {
                    console.log(response);
                    $('#category_element').val(response);
                    $('#category_id').val(category_id);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    </script>
@endpush
