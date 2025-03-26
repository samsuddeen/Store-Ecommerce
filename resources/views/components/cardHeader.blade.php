@props(['href', 'name' => ''])

<div class="card-header d-flex justify-space-between">
    <h4 class="card-title text-capitalize">{{ $name }}</h4>
    <div class="div">
        <a {{ $attributes }} id="" class="btn btn-primary" href="{{ $href }}" role="button">
            create
        </a>
        {{ $slot }}
    </div>
</div>
