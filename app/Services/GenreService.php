<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\MovieGenre;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class GenreService
{
    public function getGenres(int $perPage = null)
    {
        return Genre::paginate($perPage);
    }

    public function createGenre(array $data)
    {
        try {
            $genre = Genre::create([
                'name' => $data['name'],
            ]);

            return $this->successResponse($genre, 'Жанр успешно создан.');
        } catch (Exception $e) {
            return $this->errorResponse('Ошибка при созданий: ' . $e->getMessage());
        }
    }

    public function updateGenre($id, array $data)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->name = $data['name'];
            $genre->save();
            return $this->successResponse($genre, 'Жанр успешно обновлен.');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Жанр не найден', 404);
        } catch (Exception $e) {
            return $this->errorResponse('Ошибки при обновлений жанра: ' . $e->getMessage());
        }
    }

    public function getGenreById(int $id, int $perPage = null)
    {
        try {
            $genre = Genre::findOrFail($id);
            $movies = $genre->movies()->paginate($perPage);

            return $this->successResponse([
                'genre' => $genre,
                'movies' => $movies
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Жанр не найден', 404);
        } catch (Exception $e) {
            return $this->errorResponse('Ошибка при получений жанра: ' . $e->getMessage());
        }
    }


    public function deleteGenre(Genre $genre)
    {
        $genre->delete();
        MovieGenre::where('genre_id', $genre->id)->delete();
        return $this->successResponse();
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
