@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Danh sách dự án</h2>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-4">
        + Tạo dự án
    </a>

    <div class="row">
        @foreach($projects as $p)

        <div class="col-md-4 mb-4">
            <div class="card project-card h-100"
                 onclick="window.location='/projects/{{ $p->id }}'">

                <div class="card-body">

                    <h5 class="card-title">📁 {{ $p->name }}</h5>

                    <p><strong>Mã:</strong> {{ $p->project_code }}</p>

                    <p><strong>👤 Leader:</strong> {{ $p->leader }}</p>

                    <p><strong>📊 Trạng thái:</strong> 
                        <span class="badge bg-primary">{{ $p->status }}</span>
                    </p>

                    <p><strong>📅:</strong> 
                        {{ $p->start_date }} → {{ $p->end_date }}
                    </p>

                    <hr>

                    <strong>👥 Thành viên:</strong>
                    <ul>
                        @foreach($p->users as $u)
                            <li>{{ $u->email }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

        @endforeach
    </div>

</div>

@endsection