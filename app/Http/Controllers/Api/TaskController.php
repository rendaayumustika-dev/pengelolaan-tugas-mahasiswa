<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'course_id' => ['sometimes', 'integer'],
            'status_id' => ['sometimes', 'integer', 'exists:statuses,id'],
            'priority_id' => ['sometimes', 'integer', 'exists:task_priorities,id'],
        ]);

        $query = $this->ownedTaskQuery();

        if (array_key_exists('course_id', $filters)) {
            $this->ownedCourse((string) $filters['course_id']);
            $query->where('course_id', $filters['course_id']);
        }

        if (array_key_exists('status_id', $filters)) {
            $query->where('status_id', $filters['status_id']);
        }

        if (array_key_exists('priority_id', $filters)) {
            $query->where('priority_id', $filters['priority_id']);
        }

        return response()->json([
            'data' => $query->orderBy('deadline')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());
        $this->ownedCourse((string) $validated['course_id']);

        $validated['status_id'] ??= 1;
        $validated['priority_id'] ??= 2;

        $task = Task::create($validated)->load(['course', 'status', 'priority', 'submission']);

        return response()->json(['data' => $task], 201);
    }

    public function show(string $task): JsonResponse
    {
        return response()->json([
            'data' => $this->ownedTask($task),
        ]);
    }

    public function update(Request $request, string $task): JsonResponse
    {
        $task = $this->ownedTask($task);
        $validated = $request->validate($this->rules(true));

        if (array_key_exists('course_id', $validated)) {
            $this->ownedCourse((string) $validated['course_id']);
        }

        $task->update($validated);

        return response()->json([
            'data' => $task->refresh()->load(['course', 'status', 'priority', 'submission']),
        ]);
    }

    public function destroy(string $task): JsonResponse
    {
        $this->ownedTask($task)->delete();

        return response()->json([
            'message' => 'Tugas berhasil dihapus.',
        ]);
    }

    public function updateStatus(Request $request, string $task): JsonResponse
    {
        $validated = $request->validate([
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
        ]);

        $task = $this->ownedTask($task);
        $task->update($validated);

        return response()->json([
            'data' => $task->refresh()->load(['course', 'status', 'priority', 'submission']),
        ]);
    }

    public function deadlines(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'include_done' => ['nullable', 'boolean'],
        ]);

        $query = $this->ownedTaskQuery();
        $query->where('deadline', '>=', $filters['from'] ?? now());

        if (! empty($filters['to'])) {
            $query->where('deadline', '<=', $filters['to']);
        }

        if (! $request->boolean('include_done')) {
            $query->where('status_id', '!=', 3);
        }

        return response()->json([
            'data' => $query->orderBy('deadline')->get(),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    //isi body task
    private function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return [
            'course_id' => [$required, 'integer', 'exists:courses,id'],
            'judul_tugas' => [$required, 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'deadline' => [$required, 'date'],
            'status_id' => ['sometimes', 'integer', 'exists:statuses,id'],
            'priority_id' => ['sometimes', 'integer', 'exists:task_priorities,id'],
        ];
    }

    private function ownedCourse(string $id): Course
    {
        return Auth::guard('api')->user()
            ->courses()
            ->findOrFail($id);
    }

    private function ownedTask(string $id): Task
    {
        return $this->ownedTaskQuery()->findOrFail($id);
    }

    private function ownedTaskQuery()
    {
        return Task::query()
            ->whereHas('course', fn ($query) => $query->where('user_id', Auth::guard('api')->id()))
            ->with(['course', 'status', 'priority', 'submission']);
    }
}
