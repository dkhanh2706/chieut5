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
        <p>Welcome, {{ auth()->user()->name }}! Here's an overview of your projects.</p>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="fas fa-folder-open" style="color: #3498db;"></i>
                <div class="number">0</div>
                <div class="label">Total Projects</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="fas fa-hourglass-half" style="color: #f39c12;"></i>
                <div class="number">0</div>
                <div class="label">In Progress</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <div class="number">0</div>
                <div class="label">Completed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="fas fa-users" style="color: #e74c3c;"></i>
                <div class="number">0</div>
                <div class="label">Team Members</div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="row">
        <div class="col-12">
            <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h2 style="margin: 0; color: #2c3e50;">Recent Projects</h2>
                    <button class="btn-add-project" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                        <i class="fas fa-plus"></i> New Project
                    </button>
                </div>

                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Projects Yet</h3>
                    <p>Get started by creating your first project. Click the "New Project" button to begin!</p>
                </div>

                <!-- Projects will be displayed here -->
                <div id="projectsList"></div>
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
                    <div class="mb-3">
                        <label for="projectStatus" class="form-label">Status</label>
                        <select class="form-control" id="projectStatus">
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="projectDeadline" class="form-label">Deadline</label>
                        <input type="date" class="form-control" id="projectDeadline">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addProject()">Create Project</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Sample projects for demonstration
    const projects = [];

    function displayProjects() {
        const projectsList = document.getElementById('projectsList');
        
        if (projects.length === 0) {
            projectsList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Projects Yet</h3>
                    <p>Get started by creating your first project. Click the "New Project" button to begin!</p>
                </div>
            `;
            return;
        }

        let html = '';
        projects.forEach((project, index) => {
            const statusClass = `status-${project.status}`;
            html += `
                <div class="project-card">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h5>${project.name}</h5>
                            <p style="color: #7f8c8d; margin-bottom: 10px;">${project.description}</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${Math.random() * 100}%"></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="${statusClass}">${project.status.charAt(0).toUpperCase() + project.status.slice(1)}</span>
                                ${project.deadline ? `<small style="color: #7f8c8d;"><i class="fas fa-calendar"></i> ${project.deadline}</small>` : ''}
                            </div>
                        </div>
                        <div style="margin-left: 20px;">
                            <button class="btn btn-sm btn-outline-primary" onclick="editProject(${index})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        projectsList.innerHTML = html;
    }

    function addProject() {
        const name = document.getElementById('projectName').value;
        const description = document.getElementById('projectDescription').value;
        const status = document.getElementById('projectStatus').value;
        const deadline = document.getElementById('projectDeadline').value;

        if (!name) {
            alert('Please enter a project name');
            return;
        }

        projects.push({
            name: name,
            description: description,
            status: status,
            deadline: deadline
        });

        // Reset form and close modal
        document.getElementById('addProjectForm').reset();
        bootstrap.Modal.getInstance(document.getElementById('addProjectModal')).hide();

        // Display projects
        displayProjects();
    }

    function editProject(index) {
        alert('Edit functionality can be implemented');
    }

    function deleteProject(index) {
        if (confirm('Are you sure you want to delete this project?')) {
            projects.splice(index, 1);
            displayProjects();
        }
    }

    // Initialize
    displayProjects();
</script>
@endsection
