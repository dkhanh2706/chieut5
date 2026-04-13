@extends('layouts.project')

@section('project-content')

<div class="container mt-4">

{{-- SUCCESS --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ERROR --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    {{-- 🔥 HEADER PROJECT --}}
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h3>{{ $project->name }}</h3>

            <div class="row mt-3">
                <div class="col-md-3"><strong>Mã:</strong> {{ $project->project_code }}</div>
                <div class="col-md-3"><strong>Leader:</strong> {{ $leader->email }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> {{ $project->status }}</div>
                <div class="col-md-3"><strong>Số task:</strong> {{ $project->tasks->count() }}</div>
            </div>
        </div>
    </div>

    {{-- 🔥 DASHBOARD --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5>Tổng thời gian</h5>
                    <h3>{{ $totalHours }} giờ</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5>Tổng chi phí</h5>
                    <h3>{{ number_format($totalCost) }} VNĐ</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 THÀNH VIÊN --}}
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5>Thành viên</h5>

            @php
                $members = $project->users->where('id', '!=', $project->leader);
            @endphp

            @if($members->count() > 0)
                <ul>
                    @foreach($members as $u)
                        <li>{{ $u->email }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Chưa có thành viên</p>
            @endif

            {{-- FORM ADD MEMBER --}}
            @if(auth()->id() == $project->leader)
                <form method="POST" action="{{ route('projects.addMember', $project->id) }}" class="d-flex gap-2 mt-3">
                    @csrf
                    <input type="email" name="email" class="form-control" placeholder="Nhập email thành viên" required>
                    <button class="btn btn-primary">Thêm</button>
                </form>
            @endif
        </div>
    </div>

    {{-- 🔥 FORM THÊM TASK --}}
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5>Thêm công việc</h5>

            <form method="POST" action="{{ route('projects.addTask', $project->id) }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Tên công việc" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <input type="text" name="assigned_to" class="form-control" placeholder="Người làm" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <input type="number" name="estimated_hours" class="form-control" placeholder="Số giờ" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <input type="number" name="cost" class="form-control" placeholder="Chi phí" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <select name="status" class="form-control">
                            <option>Chưa làm</option>
                            <option>Đang làm</option>
                            <option>Hoàn thành</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <input type="date" name="start_date" class="form-control">
                    </div>

                    <div class="col-md-6 mb-2">
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                <button class="btn btn-success">Thêm Task</button>
            </form>
        </div>
    </div>

  {{-- 🔥 TASK LIST --}}
<div class="row">
    @foreach($project->tasks as $t)
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column justify-content-between">

                <div class="card-body">
                    <h5>{{ $t->name }}</h5>

                    <p><strong>Người làm:</strong> {{ $t->assigned_to }}</p>
                    <p><strong>Giờ:</strong> {{ $t->estimated_hours }}</p>
                    <p><strong>Chi phí:</strong> {{ number_format($t->cost) }}</p>

                    <span class="badge 
                        @if($t->status == 'Hoàn thành') bg-success
                        @elseif($t->status == 'Đang làm') bg-warning
                        @else bg-secondary
                        @endif
                    ">
                        {{ $t->status }}
                    </span>
                </div>

                {{-- 🔥 ACTION --}}
                <div class="card-footer bg-white">
                    <button 
                        class="btn btn-danger btn-sm w-100"
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal{{ $t->id }}">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>

            </div>
        </div>

        {{-- 🔥 MODAL XÓA --}}
        <div class="modal fade" id="deleteModal{{ $t->id }}" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                Bạn có chắc muốn xóa task <strong>{{ $t->name }}</strong> không?
              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>

                <form method="POST" action="{{ route('tasks.delete', $t->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Xóa</button>
                </form>
              </div>

            </div>
          </div>
        </div>

    @endforeach
</div>
</div>

@endsection