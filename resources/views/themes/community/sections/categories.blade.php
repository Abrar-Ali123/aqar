<section class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.explore_categories') }}</h2>
            <p class="section-subtitle">{{ __('pages.community_categories_subtitle') }}</p>
        </div>

        <div class="categories-grid">
            <div class="row g-4">
                @foreach($communityCategories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="category-card text-center">
                        <div class="category-icon mb-4">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                        <h3 class="h5 mb-2">{{ $category->name }}</h3>
                        <p class="category-stats small text-muted mb-3">
                            <span class="me-2">
                                <i class="fas fa-users me-1"></i>
                                {{ $category->members_count }}
                            </span>
                            <span>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $category->events_count }}
                            </span>
                        </p>
                        <div class="category-tags d-flex flex-wrap gap-2 justify-content-center mb-3">
                            @foreach($category->popular_tags->take(2) as $tag)
                            <span class="badge bg-light text-dark">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('community.category', ['locale' => app()->getLocale(), 'category' => $category->slug]) }}" 
                           class="stretched-link"></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="featured-groups mt-5">
            <h3 class="text-center h4 mb-4">{{ __('pages.active_groups') }}</h3>
            <div class="row g-4">
                @foreach($activeGroups as $group)
                <div class="col-md-6 col-lg-4">
                    <div class="group-card">
                        <div class="group-banner">
                            <img src="{{ $group->banner_url }}" 
                                 alt="{{ $group->name }}"
                                 class="img-fluid">
                        </div>
                        <div class="group-info p-4">
                            <div class="group-avatar">
                                <img src="{{ $group->avatar_url }}" 
                                     alt="{{ $group->name }}"
                                     class="rounded-circle">
                            </div>
                            <h4 class="group-name h5 mb-2">{{ $group->name }}</h4>
                            <p class="group-description text-muted small mb-3">
                                {{ Str::limit($group->description, 100) }}
                            </p>
                            <div class="group-stats d-flex justify-content-between small text-muted mb-3">
                                <span>
                                    <i class="fas fa-users me-1"></i>
                                    {{ $group->members_count }} {{ __('pages.members') }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $group->events_count }} {{ __('pages.events') }}
                                </span>
                            </div>
                            <div class="group-footer d-flex justify-content-between align-items-center">
                                <div class="group-category small">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $group->category->name }}
                                </div>
                                <a href="{{ route('community.groups.show', ['locale' => app()->getLocale(), 'group' => $group->slug]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    {{ __('pages.join_group') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
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
    margin: 0 auto;
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

.group-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.group-card:hover {
    transform: translateY(-5px);
}

.group-banner {
    height: 120px;
    overflow: hidden;
    position: relative;
}

.group-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.group-avatar {
    width: 60px;
    height: 60px;
    margin: -50px auto 1rem;
    position: relative;
    z-index: 1;
}

.group-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 3px solid white;
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

.category-card {
    animation: fadeInUp 0.5s ease backwards;
}

.category-card:nth-child(1) { animation-delay: 0.1s; }
.category-card:nth-child(2) { animation-delay: 0.2s; }
.category-card:nth-child(3) { animation-delay: 0.3s; }
.category-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush
