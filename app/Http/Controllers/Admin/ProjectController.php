<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    // ==================== PROJECT CRUD METHODS ====================
    
    public function index()
    {
        $projects = Project::with(['projectManager', 'teamMembers'])->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $managers = User::where('role', 'admin')->get();
        $employees = User::where('role', 'employee')->get();
        return view('admin.projects.create', compact('managers', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'priority' => 'required|in:low,medium,high',
            'budget' => 'nullable|numeric|min:0',
            'project_manager_id' => 'required|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id'
        ]);

        DB::transaction(function () use ($request) {
            $project = Project::create($request->only([
                'name', 'description', 'start_date', 'end_date', 
                'priority', 'budget', 'project_manager_id'
            ]));

            // Add project manager to team
            $project->teamMembers()->attach($request->project_manager_id, ['role' => 'project_manager']);

            // Add other team members
            if ($request->team_members) {
                foreach ($request->team_members as $memberId) {
                    if ($memberId != $request->project_manager_id) {
                        $project->teamMembers()->attach($memberId, ['role' => 'team_member']);
                    }
                }
            }
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['projectManager', 'teamMembers', 'tasks.assignedUser']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $managers = User::where('role', 'admin')->get();
        $employees = User::where('role', 'employee')->get();
        $project->load('teamMembers');
        return view('admin.projects.edit', compact('project', 'managers', 'employees'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high',
            'budget' => 'nullable|numeric|min:0',
            'project_manager_id' => 'required|exists:users,id',
        ]);

        $project->update($request->all());
        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    // ==================== PROJECT TEAM METHODS ====================
    
    public function teams()
    {
        $projects = Project::with(['projectManager', 'teamMembers'])->latest()->get();
        return view('admin.project-teams.index', compact('projects'));
    }

    public function teamShow(Project $project)
    {
        $project->load('teamMembers');
        $availableEmployees = User::where('role', 'employee')
                                ->whereNotIn('id', $project->teamMembers->pluck('id'))
                                ->get();
        return view('admin.project-teams.show', compact('project', 'availableEmployees'));
    }

    public function addTeamMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:project_manager,team_lead,team_member'
        ]);

        // Remove if already exists
        $project->teamMembers()->detach($request->user_id);
        
        // Add with new role
        $project->teamMembers()->attach($request->user_id, ['role' => $request->role]);

        return redirect()->back()->with('success', 'Team member added successfully.');
    }

    public function removeTeamMember(Project $project, User $user)
    {
        $project->teamMembers()->detach($user->id);
        return redirect()->back()->with('success', 'Team member removed successfully.');
    }

    public function updateTeamMemberRole(Request $request, Project $project, User $user)
    {
        $request->validate([
            'role' => 'required|in:project_manager,team_lead,team_member'
        ]);

        $project->teamMembers()->updateExistingPivot($user->id, ['role' => $request->role]);

        return redirect()->back()->with('success', 'Team member role updated successfully.');
    }

    // ==================== KANBAN METHODS ====================
    
    public function kanban()
    {
        $projects = Project::whereIn('status', ['planning', 'active'])->get();
        return view('admin.kanban.index', compact('projects'));
    }

    public function projectKanban(Project $project)
    {
        $project->load(['tasks.assignedUser']);
        return view('admin.kanban.project', compact('project'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:1,2,3',
            'due_date' => 'nullable|date',
        ]);

        $position = Task::where('project_id', $request->project_id)
                       ->where('status', 'todo')
                       ->count();

        Task::create($request->all() + ['position' => $position]);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function updateTask(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:1,2,3',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroyTask(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function moveTask(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed',
            'position' => 'required|integer'
        ]);

        try {
            $task->update([
                'status' => $request->status,
                'position' => $request->position
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task moved successfully',
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                    'position' => $task->position
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error moving task: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== EMPLOYEE METHODS ====================
    
    public function employeeProjects()
    {
        $projects = auth()->user()->projects()->with(['projectManager', 'tasks'])->get();
        return view('employee.projects.index', compact('projects'));
    }

    public function employeeProjectShow(Project $project)
    {
        $project->load(['projectManager', 'teamMembers', 'tasks.assignedUser']);
        return view('employee.projects.show', compact('project'));
    }

    public function employeeKanban(Project $project)
    {
        // Check if user is part of the project
        if (!auth()->user()->projects->contains($project)) {
            abort(403);
        }
        
        $project->load(['tasks.assignedUser']);
        return view('employee.kanban.project', compact('project'));
    }
}