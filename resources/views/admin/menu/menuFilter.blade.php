@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Menu')
@push('script')
    <script src="{{ asset('js/sortablejs/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/sortablejs/jquery.mjs.nestedSortable.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.sortable').nestedSortable({
                handle: 'div',
                items: 'li',
                toleranceElement: '> div'
            });

            $('#serialize').on('click', function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                var serialized = $('ol.sortable').nestedSortable('serialize');
                console.log(serialized);
                axios.post("{{ route('menu.sortable') }}", {
                        _token: "{{ csrf_token() }}",
                        sort: serialized
                    }).then((response) => {
                        toastr.info(response.data.message);
                    })
                    .finally(() => {
                        $('#serialize').prop('disabled', false);
                    });
            })

        });
    </script>
@endpush
@section('content')
    <!-- brand start -->

    <section class="app-user-list">
        <form action="">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Menu Sort</h4>
                    <hr />
                </div>
                <div class="card-body border-bottom">
                    <ol class="sortable">
                        @include('admin.menu._nestedMenu', ['menus' => $menus])
                    </ol>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="serialize">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection


@push('style')
    <style>
        .sortable li {
            margin-left: 10px;
        }

        .sortable li div {
            background: linear-gradient(118deg, #7367f0, rgba(115, 103, 240, 0.7));
            margin: 10px 0px;
            color:white;
            padding: 5px 14px;
            box-shadow: 2px 2px 2px rgb(243, 242, 242);
            font-size: 14px;
            font-weight: bold;
            border-radius: 100px;
        }

        .sortable li div::after {
            content: "\205C";
            float: right;
        }

        ol.sortable li {
            list-style:none;
            margin-left: 10px;

        }

    </style>
@endpush
