<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Auth::guard('api')->user()
            ->courses()
            ->withCount('tasks')
            ->orderBy('semester')
            ->orderBy('nama_mk')
            ->get();

        return response()->json(['data' => $courses]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());
        $validated['user_id'] = Auth::guard('api')->id();

        $course = Course::create($validated);

        return response()->json(['data' => $course], 201);
    }

    public function show(string $course): JsonResponse
    {
        $course = $this->ownedCourse($course)
            ->load(['tasks.status', 'tasks.priority', 'tasks.submission']);

        return response()->json(['data' => $course]);
    }

    public function update(Request $request, string $course): JsonResponse
    {
        $course = $this->ownedCourse($course);
        $course->update($request->validate($this->rules($course, true)));

        return response()->json(['data' => $course->refresh()]);
    }

    public function destroy(string $course): JsonResponse
    {
        $this->ownedCourse($course)->delete();

        return response()->json([
            'message' => 'Mata kuliah berhasil dihapus.',
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(?Course $course = null, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';
        $uniqueCode = Rule::unique('courses', 'kode_mk')
            ->where(fn ($query) => $query->where('user_id', Auth::guard('api')->id()));

        if ($course) {
            $uniqueCode->ignore($course->id);
        }

        return [
            'kode_mk' => [$required, 'string', 'max:30', $uniqueCode],
            'nama_mk' => [$required, 'string', 'max:255'],
            'dosen' => ['nullable', 'string', 'max:255'],
            'semester' => [$required, 'integer', 'min:1', 'max:14'],
        ];
    }

    private function ownedCourse(string $id): Course
    {
        return Auth::guard('api')->user()
            ->courses()
            ->findOrFail($id);
    }
}
