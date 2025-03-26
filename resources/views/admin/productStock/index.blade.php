@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="card">
                    <x-cardHeader :href="route('product.create')" name="product Stock" />
                    <div class="card-body">
                        <form action="{{ route('stock.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Color</th>

                                            <th>Quantity</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->sizes as $size)
                                            @foreach ($product->colors as $color)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" value="{{ $size->id }}" name='size[]'>
                                                        {{ $size->size }}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" value="{{ $color->id }}" name='color[]'>
                                                        <span class="menu-titles">{{ $color->title }}</span><span
                                                            class="badge badge-light-danger rounded-pill ms-auto me-1"
                                                            style="background-color: {{ $color->colorCode }}">&nbsp;</span>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name='stock[]'
                                                            value="{{ $product->stocks->where('product_size_id', $size->id)->where('color_id', $color->id)->first()?->stock }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10">
                                                <x-dashboard.button></x-dashboard.button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection
