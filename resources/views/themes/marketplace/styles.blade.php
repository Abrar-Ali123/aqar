@push('styles')
<style>
:root {
    --marketplace-primary: var(--bs-primary);
    --marketplace-secondary: var(--bs-secondary);
}

/* Global Marketplace Theme Styles */
.marketplace-theme {
    --swiper-theme-color: var(--marketplace-primary);
    --swiper-navigation-size: 2rem;
}

/* Hero Section */
.hero-section {
    background: var(--bs-light);
    overflow: hidden;
}

.hero-search {
    z-index: 10;
}

/* Product Cards */
.product-card {
    position: relative;
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-title a {
    color: var(--bs-body-color);
    text-decoration: none;
}

.product-title a:hover {
    color: var(--marketplace-primary);
}

.current-price {
    font-weight: bold;
    color: var(--marketplace-primary);
}

/* Shop Cards */
.shop-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-slider,
    .hero-slide {
        height: 400px;
    }

    .collection-card {
        height: 200px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card {
    animation: fadeInUp 0.5s ease backwards;
}

.product-card:nth-child(1) { animation-delay: 0.1s; }
.product-card:nth-child(2) { animation-delay: 0.2s; }
.product-card:nth-child(3) { animation-delay: 0.3s; }
.product-card:nth-child(4) { animation-delay: 0.4s; }

/* RTL Support */
[dir="rtl"] .product-badge {
    right: 1rem;
    left: auto;
}

[dir="rtl"] .product-actions {
    right: auto;
    left: 1rem;
}
</style>
@endpush
