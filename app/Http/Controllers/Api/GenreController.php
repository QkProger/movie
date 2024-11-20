<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Services\GenreService;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    protected GenreService $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->genreService->getGenres($request->input('perPage', 1)), 200
        );
    }

    public function store(GenreRequest $request): JsonResponse
    {
        $response = $this->genreService->createGenre($request->validated());
        return response()->json($response, $response['success'] ? 201 : 400);   
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $perPage = $request->input('perPage', 2);
        $response = $this->genreService->getGenreById($id, $perPage);
    
        return response()->json($response, $response['success'] ? 200 : $response['code'] ?? 400);
    }
    

    public function update(GenreRequest $request, $id): JsonResponse
    {
        $response = $this->genreService->updateGenre($id, $request->validated());
        return response()->json($response, $response['success'] ? 200 : $response['code'] ?? 400);
    }

    public function destroy($id): JsonResponse
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json([
                'error' => 'Жанр не найден',
            ], 404);
        }
        $response = $this->genreService->deleteGenre($genre);
        return response()->json($response['success'] ? 204 : $response['code'] ?? 400);
    }
}
