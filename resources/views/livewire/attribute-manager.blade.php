 <div>
     <form wire:submit.prevent="{{ $editMode ? 'updateAttribute' : 'storeAttribute' }}">
        <div>
        <div>
          <div>
            <label for="category_id">Category:</label>
            <select id="category_id" wire:model="categoryId" class="@error('categoryId') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('categoryId')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

             <label for="type">Type:</label>
            <select id="type" wire:model="type" required>
                <option value="text">Text 🔤</option>
                <option value="number">Number 🔢</option>
                <option value="date">Date 📅</option>
                <option value="time">Time ⏰</option>
                <option value="email">Email ✉️</option>
                <option value="password">Password 🔒</option>
                <option value="file">File 📁</option>
                <option value="image">Image 🖼️</option>
                <option value="select">Select ▼</option>
                <option value="checkbox">Checkbox ☑️</option>
                <option value="radio">Radio ◉</option>
                <option value="url">URL 🔗</option>
                <option value="color">Color 🎨</option>
                <option value="tel">Telephone ☎️</option>
                <option value="range">Range 🎚️</option>
                <option value="datetime-local">Datetime Local 📅</option>
                <option value="month">Month 📅</option>
                <option value="week">Week 📅</option>
                <option value="textarea">Textarea 📝</option>
                <option value="switch">Switch 🔄</option>
                <!-- يمكنك إضافة المزيد من الخيارات هنا حسب الحاجة -->
            </select>
            @error('type') <span class="error">{{ $message }}</span> @enderror

            <div>
                <label for="required">Required:</label>
                <input type="checkbox" id="required" wire:model="required">
                @error('required') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <label for="icon">Icon:</label>
        <input type="file" id="icon" wire:model="icon">
        @error('icon') <span class="error">{{ $message }}</span> @enderror
        @if ($icon)
            <img src="{{ $icon->temporaryUrl() }}" width="50">
        @endif

        @foreach (config('app.locales') as $locale)
            <div>
                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }}):</label>
                <input type="text" id="name_{{ $locale }}" wire:model="translations.{{ $locale }}.name" required>
                @error('translations.' . $locale . '.name') <span class="error">{{ $message }}</span> @enderror

                <label for="symbol_{{ $locale }}">Symbol ({{ strtoupper($locale) }}):</label>
                <input type="text" id="symbol_{{ $locale }}" wire:model="translations.{{ $locale }}.symbol">
                @error('translations.' . $locale . '.symbol') <span class="error">{{ $message }}</span> @enderror
            </div>
        @endforeach

        <button type="submit">{{ $editMode ? 'Update Attribute' : 'Save Attribute' }}</button>
    </form>

    <hr>

     <h2>Attributes List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Symbol</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attributeList as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->symbol }}</td>
                    <td>
                        @if ($attribute->icon)
                            <img src="{{ Storage::url($attribute->icon) }}" width="50">
                        @endif
                    </td>
                    <td>
                        <button wire:click="editAttribute({{ $attribute->id }})">Edit</button>
                        <button wire:click="deleteAttribute({{ $attribute->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
