@extends('layouts.app')

@section('title', 'Dashboard - IT Project Management')

@section('css')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .dashboard-header p {
        margin: 10px 0 0 0;
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">

    <!-- Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> IT Project Management</h1>
        <p>Welcome, {{ auth()->user()->name ?? 'Guest' }}! Manage your projects below.</p>
    </div>

    <!-- Actions -->
    <div class="row">

        <!-- Create Project -->
        <div class="col-md-6 mb-4">
            <div class="card text-center p-4">
                <i class="fas fa-folder-plus" style="font-size: 3rem; color: #3498db;"></i>
                <h4 class="mt-3">Create New Project</h4>
                <p class="text-muted">Create a new project and get a unique access code</p>

                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Project
                </a>
            </div>
        </div>

        <!-- Access Project -->
        <div class="col-md-6 mb-4">
            <div class="card text-center p-4">
                <i class="fas fa-key" style="font-size: 3rem; color: #27ae60;"></i>
                <h4 class="mt-3">Enter Project Code</h4>
                <p class="text-muted">View a project using its unique code</p>

                <form method="GET" action="#">
                    <div class="d-flex justify-content-center gap-2">
                        <input type="text" name="code" class="form-control" placeholder="e.g., 001" style="width:120px;">
                        <button class="btn btn-success">
                            <i class="fas fa-sign-in-alt"></i> Access
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</div>
@endsection