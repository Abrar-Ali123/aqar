<div class="category-node" style="margin-bottom: 10px; margin-right: 20px;">
    <strong>{{ $category->name }}</strong>
    @if ($category->permissions->count())
        <ul style="margin: 5px 0 5px 20px;">
            @foreach ($category->permissions as $permission)
                <li>{{ $permission->name }}</li>
            @endforeach
        </ul>
    @endif
    @if ($category->children->count())
        <div style="margin-right: 20px;">
            @foreach ($category->children as $child)
                @include('livewire.partials.permission-category-tree', ['category' => $child])
            @endforeach
        </div>
    @endif
</div>
