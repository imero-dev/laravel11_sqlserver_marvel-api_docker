<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarvelApiService;
use App\Models\Character;

class CharacterController extends Controller
{
    protected $marvelApiService;

    public function __construct(MarvelApiService $marvelApiService)
    {
        $this->marvelApiService = $marvelApiService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');

        $orderBy = null;
        if ($sort === 'asc') {
            $orderBy = 'name';
        } elseif ($sort === 'desc') {
            $orderBy = '-name';
        }

        $error = null;

        try {
            $response = $this->marvelApiService->getCharacters($search, $sort);

            if ($response) {
                $charactersData = $response['data']['results'];

                // Guardar o actualizar personajes en la base de datos
                foreach ($charactersData as $characterData) {
                    Character::updateOrCreate(
                        ['character_id' => $characterData['id']],
                        [
                            'name' => $characterData['name'],
                            'description' => $characterData['description'],
                            'thumbnail_path' => $characterData['thumbnail']['path'],
                            'thumbnail_extension' => $characterData['thumbnail']['extension'],
                        ]
                    );
                }

                $characters = $charactersData;
            } else {
                // Si no hay respuesta, obtener personajes desde la base de datos
                $characters = $this->getCharactersFromDatabase($search, $sort);
            }
        } catch (\Exception $e) {
            // En caso de error, obtener personajes desde la base de datos
            $characters = $this->getCharactersFromDatabase($search, $sort);
            $error = "No se pudo conectar con la API. Mostrando datos almacenados.";
        }

        return view('characters.index', compact('characters', 'search', 'sort', 'error'));
    }

    private function getCharactersFromDatabase($search, $sort)
    {
        $query = Character::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($sort) {
            $query->orderBy('name', $sort);
        }

        $query->limit(10);

        $characters = $query->get();

        // Convertir los datos al formato necesario
        return $characters->map(function ($character) {
            return [
                'id' => $character->character_id,
                'name' => $character->name,
                'description' => $character->description,
                'thumbnail' => [
                    'path' => $character->thumbnail_path,
                    'extension' => $character->thumbnail_extension,
                ],
            ];
        })->toArray();
    }
}
