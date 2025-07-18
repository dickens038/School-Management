@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold mb-3">Welcome to Manyama High School</h1>
            <p class="lead">Empowering students for a brighter future. Excellence in academics, discipline, sports, and culture.</p>
            <a href="/login" class="btn btn-primary btn-lg me-2">Login</a>
            <a href="/register" class="btn btn-outline-primary btn-lg">Register</a>
        </div>
        <div class="col-md-6 text-center">
            <img src="/favicon.svg" alt="School Logo" style="max-width: 220px;">
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Our Mission</h5>
                    <p class="card-text">To provide holistic education that nurtures academic excellence, discipline, and character development in every student.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Our Vision</h5>
                    <p class="card-text">To be a leading institution producing responsible, innovative, and successful graduates who contribute positively to society.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Core Values</h5>
                    <ul class="mb-0">
                        <li>Integrity & Discipline</li>
                        <li>Academic Excellence</li>
                        <li>Teamwork & Leadership</li>
                        <li>Respect & Diversity</li>
                        <li>Community Service</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <h3>About Manyama High School</h3>
            <p>Manyama High School is a center of excellence in education, offering a wide range of academic and co-curricular programs. Our dedicated staff and modern facilities ensure that every student receives the support and opportunities they need to succeed.</p>
        </div>
        <div class="col-md-6">
            <h3>Contact Information</h3>
            <ul class="list-unstyled">
                <li><strong>Address:</strong> 123 School Lane, Manyama City</li>
                <li><strong>Phone:</strong> +254 700 000 000</li>
                <li><strong>Email:</strong> info@manyamahigh.ac.ke</li>
                <li><strong>Website:</strong> www.manyamahigh.ac.ke</li>
            </ul>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <h3>Latest News</h3>
            @forelse($news as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">{{ Str::limit($item->body, 120) }}</p>
                        <p class="card-text"><small class="text-muted">{{ $item->published_at ? $item->published_at->format('F j, Y') : '' }}</small></p>
                    </div>
                </div>
            @empty
                <div class="text-muted">No news available.</div>
            @endforelse
        </div>
        <div class="col-md-6">
            <h3>Upcoming Events</h3>
            <ul class="list-group mb-3">
                @forelse($events as $event)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $event->title }}</strong><br>
                            <small>{{ $event->description }}</small>
                        </span>
                        <span class="badge bg-primary rounded-pill">{{ $event->event_date ? $event->event_date->format('M d') : '' }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No upcoming events.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="mb-5">
        <h3 class="mb-4">Gallery</h3>
        <div class="row g-3">
            @forelse($galleryImages as $image)
                <div class="col-6 col-md-3">
                    <img src="{{ asset('storage/gallery/' . $image->image_path) }}" class="img-fluid rounded shadow-sm" alt="{{ $image->caption }}">
                </div>
            @empty
                <div class="text-muted">No images in the gallery yet.</div>
            @endforelse
        </div>
    </div>
    <div class="text-center text-muted small">
        &copy; {{ date('Y') }} Manyama High School. All rights reserved.
    </div>
</div>
@endsection 