class AnalyticsTracker {
    constructor() {
        this.startTime = Date.now();
        this.interactions = {
            clicks: [],
            scrolls: [],
            forms: []
        };
        this.setupEventListeners();
    }

    setupEventListeners() {
        // تتبع النقرات
        document.addEventListener('click', (e) => {
            this.trackClick(e);
        });

        // تتبع التمرير
        let scrollTimeout;
        document.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.trackScroll();
            }, 150);
        });

        // تتبع النماذج
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                this.trackForm(e);
            });
        });

        // إرسال البيانات عند مغادرة الصفحة
        window.addEventListener('beforeunload', () => {
            this.sendAnalytics();
        });

        // إرسال البيانات دورياً
        setInterval(() => {
            this.sendAnalytics();
        }, 30000); // كل 30 ثانية
    }

    trackClick(event) {
        const target = event.target;
        this.interactions.clicks.push({
            element: target.tagName,
            text: target.textContent?.trim(),
            class: target.className,
            id: target.id,
            timestamp: Date.now()
        });
    }

    trackScroll() {
        const scrollDepth = Math.round((window.scrollY + window.innerHeight) / document.documentElement.scrollHeight * 100);
        this.interactions.scrolls.push({
            depth: scrollDepth,
            timestamp: Date.now()
        });
    }

    trackForm(event) {
        const form = event.target;
        this.interactions.forms.push({
            formId: form.id,
            action: form.action,
            timestamp: Date.now()
        });
    }

    calculateMetrics() {
        return {
            timeOnPage: Math.round((Date.now() - this.startTime) / 1000),
            interactions: this.interactions
        };
    }

    async sendAnalytics() {
        const metrics = this.calculateMetrics();
        
        try {
            await fetch('/api/analytics/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(metrics)
            });

            // مسح التفاعلات المرسلة
            this.interactions = {
                clicks: [],
                scrolls: [],
                forms: []
            };
        } catch (error) {
            console.error('Error sending analytics:', error);
        }
    }
}

// بدء التتبع عند تحميل الصفحة
window.addEventListener('load', () => {
    window.analyticsTracker = new AnalyticsTracker();
});
