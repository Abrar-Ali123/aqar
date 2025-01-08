<div class="mb-3">
    <div>
        <input
            type="text"
            wire:model.live="search"
            class="form-control"
            placeholder="Search icons..."
            onkeydown="preventEnterSubmit(event)"
            style="margin-bottom: 20px;"
        />

        <div id="icon-gallery" class="d-flex flex-wrap">
            @foreach($showedIcons as $icon)
                <div class="icon-item {{ $selectedIcon == $icon ? 'selected' : '' }}" wire:click="selectIcon('{{ $icon }}')" style="margin: 10px; cursor: pointer;">
                    <i class="{{ $icon }}" style="font-size: 24px;"></i>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        .icon-item:hover {
            transform: scale(1.2);
        }
        .icon-item.selected{
            color: blue;
        }
    </style>

    <script>
        function preventEnterSubmit(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        }
    </script>

</div>
