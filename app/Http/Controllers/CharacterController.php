<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarvelApiService;

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

        try {
            $response = $this->marvelApiService->getCharacters($search, $sort);

            if ($response) {
                $characters = $response['data']['results'];
            } else {
                $characters = [];
            }
        } catch (\Exception $e) {
            // recuperar de la db
            $error = $e->getMessage();
            $characters = [];
            return view('characters.index', compact('error', 'search', 'sort', 'characters'));
        }
        return view('characters.index', compact('characters', 'search', 'sort'));
    }
}
