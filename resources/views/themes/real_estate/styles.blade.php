@push('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
}

.search-form {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.9);
}

/* Categories Section */
.category-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: relative;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 1.5rem;
    background: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-icon i {
    font-size: 2rem;
    color: var(--bs-primary);
}

/* Property Cards */
.property-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
}

.property-image {
    height: 240px;
    overflow: hidden;
}

.property-image img {
    height: 100%;
    object-fit: cover;
}

.property-price {
    background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
    border-radius: 1rem 0 0 0;
}

/* Agency Cards */
.agency-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.agency-card:hover {
    transform: translateY(-5px);
}

/* Service Cards */
.service-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.service-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-icon i {
    font-size: 2.5rem;
    color: var(--bs-primary);
}

/* RTL Support */
[dir="rtl"] .property-price {
    background: linear-gradient(to left, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
    border-radius: 0 1rem 0 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .property-image {
        height: 200px;
    }
}
</style>
@endpush
