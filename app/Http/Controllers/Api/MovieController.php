<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->movieService->getMovies($request->input('perPage', 1)), 200
        );
    }

    public function store(MovieRequest $request): JsonResponse
    {
        $response = $this->movieService->createMovie($request->validated());
        return response()->json($response, $response['success'] ? 201 : 400);
    }

    public function show(int $id): JsonResponse
    {
        $response = $this->movieService->getMovieById($id);
        return response()->json($response, $response['success'] ? 200 : 404);
    }

    public function update(MovieRequest $request, Movie $movie): JsonResponse
    {
        $response = $this->movieService->updateMovie($movie, $request->validated());
        return response()->json($response, $response['success'] ? 200 : 400);
    }

    public function destroy($id): JsonResponse
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json([
                'error' => 'Фильм не найден',
            ], 404);
        }
        $response = $this->movieService->deleteMovie($movie);
        return response()->json($response['success'] ? 204 : $response['code'] ?? 400);
    }

    public function public($id) {
        $response = $this->movieService->publicMovieById($id);
        return response()->json($response, $response['success'] ? 200 : 404);
    }
}
