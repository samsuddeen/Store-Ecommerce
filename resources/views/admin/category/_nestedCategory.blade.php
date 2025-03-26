        @foreach ($categories as $category)
            <li id="test_{{ $category->id }}">
                <div class="nestedlist">{{ $category->title }}</div>
                @if (count($category->children))
                    <ol>
                        @include('admin.category._nestedCategory', ['categories' => $category->children])
                    </ol>
                @endif
            </li>
        @endforeach
