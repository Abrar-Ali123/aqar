@props(['facilityId' => null])

<div class="newsletter-section bg-light p-4 rounded-3 shadow-sm">
    <div class="text-center mb-4">
        <i class="fas fa-envelope-open-text fa-2x text-primary mb-3"></i>
        <h3 class="h5">{{ __('اشترك في النشرة البريدية') }}</h3>
        <p class="text-muted">
            {{ __('اشترك ليصلك كل جديد من العروض والتحديثات') }}
        </p>
    </div>

    <form id="newsletterForm" class="newsletter-form">
        @if($facilityId)
            <input type="hidden" name="facility_id" value="{{ $facilityId }}">
        @endif

        <div class="input-group">
            <input type="email" 
                   class="form-control" 
                   name="email" 
                   placeholder="{{ __('البريد الإلكتروني') }}" 
                   required>
            <button class="btn btn-primary" type="submit">
                {{ __('اشتراك') }}
            </button>
        </div>
        
        <div class="form-text text-center mt-2">
            {{ __('يمكنك إلغاء الاشتراك في أي وقت') }}
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletterForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إظهار رسالة نجاح
                const alert = document.createElement('div');
                alert.className = 'alert alert-success mt-3';
                alert.textContent = data.message;
                form.appendChild(alert);
                
                // مسح النموذج
                form.reset();
                
                // إزالة رسالة النجاح بعد 3 ثواني
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            } else {
                // إظهار رسالة الخطأ
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger mt-3';
                alert.textContent = data.message;
                form.appendChild(alert);
                
                // إزالة رسالة الخطأ بعد 3 ثواني
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }
        });
    });
});
</script>
@endpush
