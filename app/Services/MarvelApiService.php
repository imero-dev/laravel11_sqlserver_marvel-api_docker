<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MarvelApiService
{
    protected $publicKey;
    protected $privateKey;

    public function __construct()
    {
        $this->publicKey = config('services.marvel.public');
        $this->privateKey = config('services.marvel.private');
    }

    public function getCharacters($search = null, $order = null, $limit = 10, $offset = 0)
    {
        $timestamp = now()->timestamp;
        $hash = md5($timestamp . $this->privateKey . $this->publicKey);

        $params = [
            'ts' => $timestamp,
            'apikey' => $this->publicKey,
            'hash' => $hash,
            'limit' => $limit,
            'offset' => $offset,
        ];

        if ($search) {
            $params['nameStartsWith'] = $search;
        }

        if ($order) {
            $params['orderBy'] = $order === 'asc' ? 'name' : '-name';
        }

        $response = Http::get('https://gateway.marvel.com/v1/public/characters', $params);

        if ($response->successful()) {
            //guardar en la db
            return $response->json();
        } else {
            // recuperar de la db
            throw new \Exception('Error al conectar con la API de Marvel.');//. response()->json());
        }

        return null;
    }
}
