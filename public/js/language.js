// تحديث اتجاه الصفحة بناءً على اللغة
document.addEventListener('DOMContentLoaded', function() {
    const currentLocale = document.documentElement.lang;
    const direction = currentLocale === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.dir = direction;
    document.body.dir = direction;

    // تحديث اتجاه Bootstrap
    if (direction === 'rtl') {
        const bootstrapCSS = document.querySelector('link[href*="bootstrap.min.css"]');
        if (bootstrapCSS) {
            bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css';
        }
    }
});
