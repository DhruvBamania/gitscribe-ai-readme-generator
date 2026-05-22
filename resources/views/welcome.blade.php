@extends('layouts.app')

@section('content')
<style>
    .hero-section { padding: 100px 0; background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); }
    .feature-icon { font-size: 2.5rem; color: #ff2d20; margin-bottom: 15px; }
    .btn-github { background-color: #24292e; color: white; transition: 0.3s; }
    .btn-github:hover { background-color: #000000; color: white; transform: translateY(-2px); }
</style>

<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill fs-5">Free Developer Tool</span>
                <h1 class="display-4 fw-bold text-dark mb-4">Write Perfect READMEs in Seconds.</h1>
                <p class="lead text-muted mb-5">
                    Connect your repository. Our AI analyzes your code structure, tech stack, and dependencies to instantly generate a professional, standardized Markdown documentation file.
                </p>
                <a href="{{ url('/auth/github') }}" class="btn btn-github btn-lg px-5 py-3 shadow">
                    <i class="fa-brands fa-github me-2"></i> Get Started with GitHub
                </a>
                <p class="text-muted mt-3 small">No credit card required. 100% Free.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fa-solid fa-bolt feature-icon"></i>
                <h4 class="fw-bold">Lightning Fast</h4>
                <p class="text-muted">Powered by Gemini AI, generating comprehensive documentation takes less than 5 seconds.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fa-brands fa-markdown feature-icon"></i>
                <h4 class="fw-bold">Standardized Format</h4>
                <p class="text-muted">Follows the beautiful structure of the official Laravel repository documentation.
                </p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fa-solid fa-code-pull-request feature-icon"></i>
                <h4 class="fw-bold">Auto Pull Requests</h4>
                <p class="text-muted">Directly pushes the generated README.md to your repository via a clean Pull Request.</p>
            </div>
        </div>
    </div>
</section>
@endsection