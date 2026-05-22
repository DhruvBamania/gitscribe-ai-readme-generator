@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">
                <i class="fa-solid fa-eye text-primary me-2"></i> README Preview
            </h2>
            <p class="text-muted mb-0">
                Generated for <span class="badge bg-dark">{{ $owner }}/{{ $repo }}</span>
            </p>
        </div>
        <div>

            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Repos
            </a>
            
            <form action="{{ route('readme.push') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="owner" value="{{ $owner }}">
                <input type="hidden" name="repo" value="{{ $repo }}">
                <input type="hidden" name="content" value="{{ base64_encode($content) }}">
                
                <button type="submit" class="btn btn-success shadow-sm" onclick="showLoader(this)">
                    <span class="btn-text"><i class="fa-solid fa-code-commit"></i> Create Pull Request</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-light border-bottom d-flex align-items-center">
            <i class="fa-brands fa-markdown text-muted me-2"></i>
            <span class="fw-semibold text-muted">README.md</span>
        </div>
        
        <div class="card-body p-5 markdown-body">
           
<x-markdown>
{!! $content !!}
</x-markdown>

        </div>
    </div>
</div>

<style>
    .markdown-body h1 { font-weight: bold; border-bottom: 1px solid #eaecef; padding-bottom: 10px; margin-bottom: 16px; }
    .markdown-body h2 { font-weight: bold; border-bottom: 1px solid #eaecef; padding-bottom: 6px; margin-top: 24px; margin-bottom: 16px; }
    .markdown-body pre { background-color: #f6f8fa; padding: 16px; border-radius: 6px; overflow: auto; }
    .markdown-body code { background-color: rgba(27,31,35,0.05); padding: 0.2em 0.4em; border-radius: 3px; }
    .markdown-body img { max-width: 100%; }
</style>

<script>
    function showLoader(button) {
        button.classList.add('disabled');
        button.querySelector('.btn-text').innerText = ' Creating Pull Request...';
        button.querySelector('.spinner-border').classList.remove('d-none');
    }
</script>
@endsection