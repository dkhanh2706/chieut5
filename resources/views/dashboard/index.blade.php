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

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-card i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: block;
    }

    .stat-card .number {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .stat-card .label {
        color: #7f8c8d;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .project-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #3498db;
        transition: all 0.3s ease;
    }

    .project-card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .project-card h5 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .project-card .badge {
        display: inline-block;
        margin-top: 10px;
    }

    .progress {
        height: 8px;
        border-radius: 5px;
        margin: 15px 0;
    }

    .progress-bar {
        background-color: #3498db;
        border-radius: 5px;
    }

    .btn-add-project {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: bold;
        transition: all 0.3s ease;
        float: right;
        margin-bottom: 20px;
    }

    .btn-add-project:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .status-active {
        color: #27ae60;
        font-weight: bold;
    }

    .status-pending {
        color: #f39c12;
        font-weight: bold;
    }

    .status-completed {
        color: #3498db;
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #bdc3c7;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .team-member {
        display: inline-block;
        margin: 5px;
    }

    .team-member-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> IT Project Management</h1>
        <p>Welcome, {{ auth()->user()->name }}! Manage your projects below.</p>
    </div>

    <!-- Project Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-folder-plus" style="font-size: 3rem; color: #3498db;"></i>
                    <h4 class="mt-3">Create New Project</h4>
                    <p class="text-muted">Create a new project and get a unique access code</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="fas fa-plus"></i> Create Project
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-key" style="font-size: 3rem; color: #27ae60;"></i>
                    <h4 class="mt-3">Enter Project Code</h4>
                    <p class="text-muted">View a project using its unique code</p>
                    <form id="accessProjectForm" class="d-flex gap-2 justify-content-center">
                        <input type="text" class="form-control" id="projectCode" placeholder="e.g., 001" maxlength="3" style="width: 120px;">
                        <button type="button" class="btn btn-success" onclick="accessProject()">
                            <i class="fas fa-sign-in-alt"></i> Access
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Project View (hidden until code is entered) -->
    <div class="row" id="projectViewSection" style="display: none;">
        <div class="col-12">
            <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #2c3e50;" id="projectTitle">Project Details</h2>
                    <button class="btn btn-outline-secondary" onclick="closeProjectView()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
                <div id="projectDetails"></div>
            </div>
        </div>
    </div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createProjectForm">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="projectDescription" rows="3"></textarea>
                    </div>
                </form>
                <div id="projectCodeDisplay" class="text-center p-3" style="display: none; background: #f8f9fa; border-radius: 5px;">
                    <p class="mb-2">Your Project Code:</p>
                    <h2 class="mb-0" id="generatedCode" style="color: #3498db; font-size: 2.5rem;"></h2>
                    <p class="text-muted small mt-2">Save this code to access your project later</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalBtn">Close</button>
                <button type="button" class="btn btn-primary" onclick="createProject()" id="createBtn">Create Project</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addProjectForm">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="projectDescription" rows="3"></textarea>
                    </div>
@section('js')
<script>
    let projects = [];
    let projectCounter = 0;

    function generateCode() {
        projectCounter++;
        return projectCounter.toString().padStart(3, '0');
    }

    

    function createProject() {
        const name = document.getElementById('projectName').value;
        const description = document.getElementById('projectDescription').value;

        if (!name) {
            alert('Please enter a project name');
            return;
        }

        const code = generateCode();
        
        projects.push({
            code: code,
            name: name,
            description: description
        });

        document.getElementById('generatedCode').textContent = code;
        document.getElementById('projectCodeDisplay').style.display = 'block';
        document.getElementById('createBtn').style.display = 'none';
        document.getElementById('closeModalBtn').textContent = 'Done';

        displayProjects();
    }

    function deleteProject(code) {
        if (confirm('Are you sure you want to delete this project?')) {
            projects = projects.filter(p => p.code !== code);
            displayProjects();
        }
    }

    function accessProject() {
        const code = document.getElementById('projectCode').value.trim();
        
        if (!code) {
            alert('Please enter a project code');
            return;
        }

        const project = projects.find(p => p.code === code);
        
        if (project) {
            document.getElementById('projectTitle').textContent = project.name;
            document.getElementById('projectDetails').innerHTML = `
                <div class="text-center">
                    <span style="background: #3498db; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 1.2rem;">Code: ${project.code}</span>
                </div>
                <div class="mt-4">
                    <p><strong>Description:</strong></p>
                    <p style="color: #7f8c8d;">${project.description || 'No description provided.'}</p>
                </div>
                <div class="mt-3">
                    <button class="btn btn-outline-danger" onclick="deleteProject('${project.code}')">
                        <i class="fas fa-trash"></i> Delete Project
                    </button>
                </div>
            `;
            document.getElementById('projectViewSection').style.display = 'block';
            document.getElementById('projectViewSection').scrollIntoView({ behavior: 'smooth' });
        } else {
            alert('Project not found with code: ' + code);
        }
    }

    function closeProjectView() {
        document.getElementById('projectViewSection').style.display = 'none';
        document.getElementById('projectCode').value = '';
    }

    document.getElementById('createProjectModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('createProjectForm').reset();
        document.getElementById('projectCodeDisplay').style.display = 'none';
        document.getElementById('createBtn').style.display = 'block';
        document.getElementById('closeModalBtn').textContent = 'Close';
    });
</script>
@endsection
