<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    public function show(string $task): JsonResponse
    {
        return response()->json([
            'data' => $this->ownedTask($task)->submission()->firstOrFail(),
        ]);
    }

    public function store(Request $request, string $task): JsonResponse
    {
        $task = $this->ownedTask($task);

        if ($task->submission()->exists()) {
            return response()->json([
                'message' => 'Submission untuk tugas ini sudah ada.',
            ], 422);
        }

        $submission = $task->submission()->create($request->validate($this->rules()));
        $task->update(['status_id' => 3]);

        return response()->json([
            'data' => $submission->load('task.status'),
        ], 201);
    }

    public function update(Request $request, string $task): JsonResponse
    {
        $submission = $this->ownedTask($task)->submission()->firstOrFail();
        $submission->update($request->validate($this->rules(true)));

        return response()->json([
            'data' => $submission->refresh()->load('task.status'),
        ]);
    }

    public function destroy(string $task): JsonResponse
    {
        $this->ownedTask($task)->submission()->firstOrFail()->delete();

        return response()->json([
            'message' => 'Submission berhasil dihapus.',
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return [
            'tanggal_submit' => [$required, 'date'],
            'catatan' => ['nullable', 'string'],
            'file_tugas' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function ownedTask(string $id): Task
    {
        return Task::query()
            ->whereHas('course', fn ($query) => $query->where('user_id', Auth::guard('api')->id()))
            ->with(['course', 'status', 'priority', 'submission'])
            ->findOrFail($id);
    }
}
