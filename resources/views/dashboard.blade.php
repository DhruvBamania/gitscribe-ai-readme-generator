@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fa-brands fa-github me-2"></i> Your Repositories
        </h2>
        <div class="text-muted d-flex align-items-center">
            <i class="fa-solid fa-user-circle fs-4 me-2"></i>
            <span>{{ auth()->user()->name }}</span>
        </div>
    </div>

    <p class="text-muted mb-4">Select a repository below to automatically generate a professional README.md.</p>

    <div class="row">
        @forelse ($repos as $repo)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow border-0 bg-white">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark text-truncate">
                            <i class="fa-solid fa-book-bookmark text-danger me-2"></i> {{ $repo['name'] }}
                        </h5>
                        
                        <p class="card-text text-muted small flex-grow-1 mt-2">
                            {{ $repo['description'] ?? 'No description provided for this repository.' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <span class="badge bg-light text-dark border p-2">
                                <i class="fa-solid fa-code me-1"></i> {{ $repo['language'] ?? 'Code' }}
                            </span>
                            
                            <a href="{{ route('readme.generate', ['owner' => $repo['owner']['login'], 'repo' => $repo['name']]) }}" 
                            class="btn btn-sm btn-outline-danger generate-btn"
                            onclick="showLoader(this)">
                                <span class="btn-text"><i class="fa-solid fa-wand-magic-sparkles"></i> Generate README</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fa-solid fa-folder-open fa-4x mb-3 text-light"></i>
                    <h4 class="fw-bold">No repositories found.</h4>
                    <p>Create a repository on GitHub to get started.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    function showLoader(button) {
        button.classList.add('disabled');
        button.querySelector('.btn-text').innerText = ' Generating...';
        button.querySelector('.spinner-border').classList.remove('d-none');
    }
</script>
@endsection
