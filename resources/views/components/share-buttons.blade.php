@props(['title', 'url'])

<div class="share-buttons d-flex gap-2">
    <button class="btn btn-sm btn-outline-primary share-button" 
            data-platform="facebook" 
            data-url="{{ $url }}" 
            data-title="{{ $title }}">
        <i class="fab fa-facebook-f"></i>
    </button>
    
    <button class="btn btn-sm btn-outline-info share-button" 
            data-platform="twitter" 
            data-url="{{ $url }}" 
            data-title="{{ $title }}">
        <i class="fab fa-twitter"></i>
    </button>
    
    <button class="btn btn-sm btn-outline-success share-button" 
            data-platform="whatsapp" 
            data-url="{{ $url }}" 
            data-title="{{ $title }}">
        <i class="fab fa-whatsapp"></i>
    </button>
    
    <button class="btn btn-sm btn-outline-info share-button" 
            data-platform="telegram" 
            data-url="{{ $url }}" 
            data-title="{{ $title }}">
        <i class="fab fa-telegram-plane"></i>
    </button>
    
    <button class="btn btn-sm btn-outline-secondary copy-link" 
            data-url="{{ $url }}">
        <i class="fas fa-link"></i>
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // مشاركة على منصات التواصل الاجتماعي
    document.querySelectorAll('.share-button').forEach(button => {
        button.addEventListener('click', function() {
            const platform = this.dataset.platform;
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            fetch('{{ route("products.share", ["product" => "PRODUCT_ID"]) }}'.replace('PRODUCT_ID', '{{ request()->route("product") }}'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    platform: platform,
                    url: url,
                    title: title
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.share_url) {
                    window.open(data.share_url, '_blank');
                }
            });
        });
    });

    // نسخ الرابط
    document.querySelectorAll('.copy-link').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            navigator.clipboard.writeText(url).then(() => {
                // تغيير شكل الزر مؤقتاً للإشارة إلى نجاح النسخ
                const icon = this.querySelector('i');
                icon.className = 'fas fa-check';
                this.classList.add('btn-success');
                this.classList.remove('btn-outline-secondary');
                
                setTimeout(() => {
                    icon.className = 'fas fa-link';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            });
        });
    });
});
</script>
@endpush
