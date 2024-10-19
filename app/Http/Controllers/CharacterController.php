<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $characters = [
            [
                'name' => 'Cyclops',
                'image' => 'http://i.annihil.us/u/prod/marvel/i/mg/6/70/526547e2d90ad.jpg',
            ],
            [
                'name' => 'Captain America',
                'image' => 'http://i.annihil.us/u/prod/marvel/i/mg/3/50/537ba56d31087.jpg',
            ],
            [
                'name' => 'Thor',
                'image' => 'http://i.annihil.us/u/prod/marvel/i/mg/d/d0/5269657a74350.jpg',
            ],
        ];

        $search = $request->input('search');
        $sort = $request->input('sort');

        if ($search) {
            $characters = array_filter($characters, function ($character) use ($search) {
                return stripos($character['name'], $search) !== false;
            });
        }

        if ($sort === 'asc') {
            usort($characters, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        } elseif ($sort === 'desc') {
            usort($characters, function ($a, $b) {
                return strcmp($b['name'], $a['name']);
            });
        }

        return view('characters.index', compact('characters', 'search', 'sort'));
    }
}
