<section class="services-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.our_services') }}</h2>
            <p class="section-subtitle">{{ __('pages.community_services_subtitle') }}</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.volunteer_opportunities') }}</h3>
                    <p class="text-muted mb-4">{{ __('pages.volunteer_opportunities_desc') }}</p>
                    <a href="{{ route('volunteer.opportunities', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary">
                        {{ __('pages.browse_opportunities') }}
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.workshops_training') }}</h3>
                    <p class="text-muted mb-4">{{ __('pages.workshops_training_desc') }}</p>
                    <a href="{{ route('workshops.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary">
                        {{ __('pages.view_workshops') }}
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.community_groups') }}</h3>
                    <p class="text-muted mb-4">{{ __('pages.community_groups_desc') }}</p>
                    <a href="{{ route('groups.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary">
                        {{ __('pages.join_groups') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="community-features mt-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card position-relative rounded-3 overflow-hidden">
                        <img src="{{ asset('images/community/mentorship.jpg') }}" 
                             alt="Mentorship Program"
                             class="feature-image">
                        <div class="feature-overlay d-flex align-items-center">
                            <div class="feature-content p-4">
                                <h3 class="text-white mb-3">{{ __('pages.mentorship_program') }}</h3>
                                <p class="text-white mb-4">{{ __('pages.mentorship_program_desc') }}</p>
                                <a href="{{ route('mentorship', ['locale' => app()->getLocale()]) }}" 
                                   class="btn btn-light">
                                    {{ __('pages.learn_more') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card position-relative rounded-3 overflow-hidden">
                        <img src="{{ asset('images/community/projects.jpg') }}" 
                             alt="Community Projects"
                             class="feature-image">
                        <div class="feature-overlay d-flex align-items-center">
                            <div class="feature-content p-4">
                                <h3 class="text-white mb-3">{{ __('pages.community_projects') }}</h3>
                                <p class="text-white mb-4">{{ __('pages.community_projects_desc') }}</p>
                                <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" 
                                   class="btn btn-light">
                                    {{ __('pages.view_projects') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="join-community text-center mt-5">
            <div class="cta-box bg-primary text-white rounded-3 p-5">
                <h3 class="mb-4">{{ __('pages.join_community_title') }}</h3>
                <p class="mb-4">{{ __('pages.join_community_desc') }}</p>
                <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" 
                   class="btn btn-light btn-lg">
                    {{ __('pages.get_started') }}
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.service-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
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
    font-size: 2rem;
    color: var(--bs-primary);
}

.feature-card {
    height: 300px;
}

.feature-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.feature-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.4));
}

.cta-box {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
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

.service-card {
    animation: fadeInUp 0.5s ease backwards;
}

.service-card:nth-child(1) { animation-delay: 0.1s; }
.service-card:nth-child(2) { animation-delay: 0.2s; }
.service-card:nth-child(3) { animation-delay: 0.3s; }
</style>
@endpush
