<div>
     <form wire:submit.prevent="{{ $editMode ? 'updateCategory' : 'storeCategory' }}">
        <div>
            <label for="isSubcategory">Is Subcategory?</label>
            <input type="checkbox" id="isSubcategory" wire:model="isSubcategory" wire:change="updateSubcategory">
        </div>

        @if ($isSubcategory)
            <div>
                <label for="parent_id">Parent Category:</label>
                <select id="parent_id" wire:model="parent_id">
                    <option value="">Select Parent</option>
                    @foreach ($categories as $categoryOption)
                        @if (!$category_id || $categoryOption->id != $category_id)
                            <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id') <span class="error">{{ $message }}</span> @enderror
            </div>
        @endif

        @foreach (config('app.locales') as $locale)
            <div>
                <label for="name_{{ $locale }}">Name ({{ $locale }}):</label>
                <input type="text" id="name_{{ $locale }}" wire:model="translations.{{ $locale }}" required>
                @error('translations.' . $locale) <span class="error">{{ $message }}</span> @enderror
            </div>
        @endforeach

        <div>
            <label for="image">Image:</label>
            <input type="file" id="image" wire:model="tempImage">
            @error('tempImage') <span class="error">{{ $message }}</span> @enderror
            @if ($tempImage)
             @endif
        </div>

        <button type="submit">{{ $editMode ? 'Update Category' : 'Save Category' }}</button>
    </form>

    <hr>

     <h2>Categories List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent ? $category->parent->name : 'No Parent' }}</td>
                    <td>
                        @if ($category->image)
                            <img src="{{ Storage::url($category->image) }}" width="50">
                        @endif
                    </td>
                    <td>
                        <button wire:click="editCategory({{ $category->id }})">Edit</button>
                        <button wire:click="deleteCategory({{ $category->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
