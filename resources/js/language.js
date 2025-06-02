document.addEventListener('DOMContentLoaded', () => {
    // التحقق من وجود رسالة تغيير اللغة في الجلسة
    const languageChanged = JSON.parse(localStorage.getItem('language-changed'));
    if (languageChanged) {
        // إرسال حدث تغيير اللغة
        window.dispatchEvent(new CustomEvent('language-changed', {
            detail: languageChanged
        }));
        // حذف الرسالة من التخزين المحلي
        localStorage.removeItem('language-changed');
    }

    // الاستماع لتغييرات الجلسة
    const observer = new MutationObserver(() => {
        const languageChanged = JSON.parse(localStorage.getItem('language-changed'));
        if (languageChanged) {
            window.dispatchEvent(new CustomEvent('language-changed', {
                detail: languageChanged
            }));
            localStorage.removeItem('language-changed');
        }
    });

    // مراقبة تغييرات الجلسة
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['dir', 'lang']
    });

    // تحديث اتجاه الصفحة عند تغيير اللغة
    window.addEventListener('language-changed', (e) => {
        document.documentElement.dir = e.detail.direction;
        document.documentElement.lang = e.detail.locale;
        
        // تحديث ملف CSS bootstrap حسب الاتجاه
        const bootstrapLink = document.querySelector('link[href*="bootstrap"]');
        if (bootstrapLink) {
            const isRTL = e.detail.direction === 'rtl';
            bootstrapLink.href = isRTL
                ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css'
                : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css';
        }
    });
});
