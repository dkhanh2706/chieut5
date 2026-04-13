@extends('layouts.project')

@section('project-content')

<div class="p-4 w-100">
    <h3>🗑️ Thùng rác</h3>

    @if($tasks->count() > 0)
        <div class="row">
            @foreach($tasks as $t)
                <div class="col-md-4 mb-3">
                    <div class="card shadow h-100 d-flex flex-column justify-content-between">

                      <div class="card-body">
    <h5 class="mb-2">{{ $t->name }}</h5>
    <p class="text-muted mb-0">{{ $t->assigned_to }}</p>
</div>

                      <div class="card-footer bg-white d-flex justify-content-between">

                            {{-- 🔥 KHÔI PHỤC --}}
                            <form method="POST" action="{{ route('tasks.restore', $t->id) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">♻️ Khôi phục</button>
                            </form>

                            {{-- 🔥 XÓA VĨNH VIỄN --}}
                            <form method="POST" action="{{ route('tasks.forceDelete', $t->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">❌ Xóa luôn</button>
                            </form>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Thùng rác trống</p>
    @endif

</div>

@endsection