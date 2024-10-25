<div>

<div>
<form wire:submit.prevent="addPermission">
         @foreach(config('app.locales') as $locale)
            <div class="mb-3">
                <label for="name_{{ $locale }}" class="form-label">{{ strtoupper($locale) }} Name</label>
                <input type="text" class="form-control" id="name_{{ $locale }}" wire:model.live="translations.{{ $locale }}.name" placeholder="Enter permission name in {{ $locale }}">
                 @error('translations.' . $locale . '.name')

                <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @endforeach

          <div class="mb-3">
            <label class="form-label">Allowed Pages</label>
            @foreach($pages as $index => $page)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="page_{{ $index }}" wire:model="pages.{{ $index }}.is_allowed">

                    <label class="form-check-label" for="page_{{ $index }}">{{ $page['name'] }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>


  <button id="toggleView">تبديل العرض</button>




<script>
    document.getElementById('toggleView').addEventListener('click', function() {
   var tables = document.querySelectorAll('table');

   tables.forEach(function(table) {
    table.classList.toggle('grid-view');
  });
});

    </script>
</div>
