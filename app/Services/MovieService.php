<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\MovieGenre;
use Exception;
use Illuminate\Support\Facades\Storage;

class MovieService
{
    public function getMovies(int $perPage = null)
    {
        return Movie::with('genres')->paginate($perPage);
    }

    public function createMovie(array $data)
    {
        try {
            $posterPath = $this->handlePosterUpload($data['poster'] ?? null);

            $movie = Movie::create([
                'title' => $data['title'],
                'poster_url' => $posterPath,
            ]);

            $this->syncGenres($movie, $data['genres']);

            return $this->successResponse($movie, 'Movie created successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Error creating movie: ' . $e->getMessage());
        }
    }

    public function updateMovie(Movie $movie, array $data)
    {
        try {
            if (!empty($data['poster'])) {
                $this->deletePoster($movie->poster_url);
                $movie->poster_url = $this->handlePosterUpload($data['poster']);
            }

            $movie->title = $data['title'];
            $this->syncGenres($movie, $data['genres']);
            $movie->save();

            return $this->successResponse($movie, 'Movie updated successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Error updating movie: ' . $e->getMessage());
        }
    }

    public function getMovieById(int $id)
    {
        try {
            $movie = Movie::with('genres')->findOrFail($id);
            return $this->successResponse($movie);
        } catch (Exception $e) {
            return $this->errorResponse('Фильм не найден.', 404);
        }
    }

    public function deleteMovie(Movie $movie)
    {
        $this->deletePoster($movie->poster_url);
        $movie->delete();
        MovieGenre::where('movie_id', $movie->id)->delete();
        return $this->successResponse();
    }

    public function publicMovieById($id) {
        try {
            $movie = Movie::findOrFail($id);
            $movie->update([
                'is_published' => !$movie->is_published
            ]);
            return $this->successResponse($movie);
        } catch (Exception $e) {
            return $this->errorResponse('Фильм не найден.', 404);
        }
    }

    private function handlePosterUpload($poster = null): string
    {
        return $poster ? $poster->store('posters', 'public') : 'posters/default.jpg';
    }

    private function deletePoster(?string $posterPath): void
    {
        if ($posterPath && $posterPath !== 'posters/default.jpg' && Storage::exists('public/' . $posterPath)) {
            Storage::delete('public/' . $posterPath);
        }
    }

    private function syncGenres(Movie $movie, $genres): void
    {
        $genres = is_string($genres) ? explode(',', $genres) : $genres;
        $movie->genres()->sync($genres);
    }

    private function successResponse($data = null, string $message = 'Success')
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'code' => 200,
        ];
    }

    private function errorResponse(string $message, int $code = 400)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];
    }
}
