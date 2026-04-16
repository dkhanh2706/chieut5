<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class ProjectController extends Controller
{
    // 📌 Danh sách project của user
    public function index()
    {
        $projects = Auth::user()->projects()->with('users')->get();
        return view('projects.index', compact('projects'));
    }

    // 📌 Lịch trình dự án (calendar view)
    public function calendar()
    {
        $projects = Auth::user()->projects()->with('users')->get();
        return view('projects.calendar', compact('projects'));
    }

    // 📌 Trang tạo project
    public function create()
    {
        return view('projects.create');
    }

    // 📌 Lưu project
    public function store(Request $request)
    {
        $request->validate([
            'project_code' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);

        // 🔥 CHECK TRÙNG CODE THEO USER
        $exists = Project::where('project_code', $request->project_code)
            ->where('leader', Auth::id())
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'project_code' => 'Bạn đã tạo project với mã này rồi!'
            ])->withInput();
        }

        $project = Project::create([
            'project_code' => $request->project_code,
            'name' => $request->name,
            'description' => $request->description,
            'leader' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        // 🔥 add leader vào project
        $project->users()->attach(Auth::id());

        return redirect('/projects')->with('success', 'Tạo dự án thành công!');
    }

    // 🔥 ADD MEMBER
    public function addMember(Request $request, $id)
    {
        $project = Project::with('users')->findOrFail($id);

        // 🔐 Check quyền
        $this->checkAccess($project);

        // 🔐 Chỉ leader được thêm
        if ($project->leader != Auth::id()) {
            return back()->withErrors([
                'email' => 'Chỉ trưởng dự án mới được thêm thành viên'
            ]);
        }

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại'
            ])->withInput();
        }

        if ($project->users->contains($user->id)) {
            return back()->withErrors([
                'email' => 'User đã trong dự án'
            ])->withInput();
        }

        $project->users()->attach($user->id);

        return back()->with('success', 'Thêm thành viên thành công');
    }

    // 🔐 CHECK QUYỀN
    private function checkAccess($project)
    {
        if (!$project->users->contains(Auth::id())) {
            abort(403, 'Bạn không có quyền truy cập project này');
        }
    }

    // 📌 Truy cập project bằng mã
    public function access(Request $request)
    {
        $code = $request->query('code');
        
        $project = Project::where('project_code', $code)->first();

        if (!$project) {
            return back()->with('error', 'Mã dự án không tồn tại!');
        }

        // Check xem user có trong project không
        if (!$project->users->contains(Auth::id())) {
            return back()->with('error', 'Bạn không có quyền truy cập dự án này!');
        }

        return redirect()->route('projects.show', $project->id);
    }

    // 📌 Chi tiết project
    public function show($id)
    {
        $project = Project::with(['users', 'tasks'])->findOrFail($id);

        // 🔥 LẤY LEADER
        $leader = User::find($project->leader);

        // 🔥 TÍNH TOÁN
        $totalHours = $project->tasks->sum('estimated_hours');
        $totalCost = $project->tasks->sum('cost');

        return view('projects.show', compact(
            'project',
            'leader',
            'totalHours',
            'totalCost'
        ));
    }

    // 📌 Kanban Board
    public function kanban($id)
    {
        $project = Project::with(['users', 'tasks' => function($q) {
            $q->orderBy('start_date', 'asc');
        }])->findOrFail($id);

        $this->checkAccess($project);

        $leader = User::find($project->leader);

        $tasksByStatus = [
            'Chưa làm' => $project->tasks->where('status', 'Chưa làm')->sortBy('start_date'),
            'Đang làm' => $project->tasks->where('status', 'Đang làm')->sortBy('start_date'),
            'Hoàn thành' => $project->tasks->where('status', 'Hoàn thành')->sortBy('start_date'),
        ];

        return view('projects.kanban', compact('project', 'leader', 'tasksByStatus'));
    }

    // 📌 Cập nhật status task (drag & drop)
    public function updateTaskStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $project = Project::with('users')->findOrFail($task->project_id);
        $this->checkAccess($project);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    // 📌 Lịch trình công việc
    public function schedule($id)
    {
        $project = Project::with(['users', 'tasks' => function($q) {
            $q->whereNotNull('start_date')
              ->orWhereNotNull('end_date');
        }])->findOrFail($id);

        $this->checkAccess($project);

        $leader = User::find($project->leader);

        return view('projects.schedule', compact('project', 'leader'));
    }

    // 📌 Thành viên
    public function members($id)
    {
        $project = Project::with('users')->findOrFail($id);
        $this->checkAccess($project);

        $leader = User::find($project->leader);

        return view('projects.members', compact('project', 'leader'));
    }

    // 📌 THÊM TASK
    public function addTask(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'assigned_to' => 'required',
            'estimated_hours' => 'required|numeric',
            'cost' => 'required|numeric',
        ]);

        Task::create([
            'project_id' => $id,
            'name' => $request->name,
            'assigned_to' => $request->assigned_to,
            'estimated_hours' => $request->estimated_hours,
            'cost' => $request->cost,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Thêm task thành công');
    }
    public function deleteTask($id)
{
    $task = Task::findOrFail($id);

    // 🔒 check quyền (optional nhưng nên có)
    $project = Project::with('users')->findOrFail($task->project_id);
    $this->checkAccess($project);

    $task->delete(); // 🔥 soft delete

    return back()->with('success', 'Đã chuyển task vào thùng rác');
}

public function trash($id)
{
    $project = Project::findOrFail($id);

    // 🔥 lấy task đã bị xóa
    $tasks = Task::onlyTrashed()
        ->where('project_id', $id)
        ->get();

    return view('projects.trash', compact('project', 'tasks'));
}


// ♻️ Khôi phục
public function restoreTask($id)
{
    $task = Task::onlyTrashed()->findOrFail($id);

    $projectId = $task->project_id; // 🔥 lấy id project

    $task->restore();

    return redirect()->route('projects.trash', $projectId)
    ->with('success', 'Đã khôi phục task');
}

// ❌ Xóa vĩnh viễn
public function forceDeleteTask($id)
{
    $task = Task::onlyTrashed()->findOrFail($id);
    $task->forceDelete();

    return back()->with('success', 'Đã xóa vĩnh viễn');
}
}