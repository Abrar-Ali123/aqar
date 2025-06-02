@push('styles')
<style>
:root {
    --community-primary: var(--bs-primary);
    --community-secondary: var(--bs-secondary);
}

/* Hero Section */
.hero-section {
    position: relative;
    overflow: hidden;
}

.event-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.event-card:hover {
    transform: translateY(-5px);
}

/* Stats Section */
.stat-item {
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 2rem;
    color: var(--community-primary);
}

/* Group Cards */
.group-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.group-card:hover {
    transform: translateY(-5px);
}

.group-banner {
    height: 120px;
    overflow: hidden;
}

.group-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.group-avatar {
    width: 60px;
    height: 60px;
    margin: -30px auto 1rem;
    position: relative;
    z-index: 1;
}

.group-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 3px solid white;
    border-radius: 50%;
}

/* Service Cards */
.service-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
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
    color: var(--community-primary);
}

/* Feature Cards */
.feature-card {
    height: 300px;
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
}

.feature-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-image {
    transform: scale(1.1);
}

.feature-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.8));
    padding: 2rem;
}

/* Animations */
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

.stat-item,
.group-card,
.service-card {
    animation: fadeInUp 0.5s ease backwards;
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

/* RTL Support */
[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}

[dir="rtl"] .ms-2 {
    margin-left: 0 !important;
    margin-right: 0.5rem !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-item {
        padding: 1.5rem;
    }

    .feature-card {
        height: 250px;
    }

    .group-banner {
        height: 100px;
    }
}
</style>
@endpush
