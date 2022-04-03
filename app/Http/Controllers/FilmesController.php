<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FilmesController extends Controller
{

    public function listaDeFilmes(Request $request)
    {
        $nome = $request->input('nome') ? $request->input('nome') : null;
        $dataLancamento = $request->input('dataLancamento') ? $request->input('dataLancamento') : null;

        $filtroPorFilme = $this->filtroPorFilme($nome, $dataLancamento);
        $listaDeFilme = self::toFilme($filtroPorFilme);

        return response(json_encode($listaDeFilme), 200)
            ->header('Content-Type', 'application/json');
    }

    public function filtroPorFilme($nome, $dataLancamento)
    {

        $filmesSwapi = Http::get('https://swapi.dev/api/films/')->json()['results'];

        $listaDeFilmes = [];

        foreach ($filmesSwapi as $filmes) {

            if ($nome != null) {
                if ($filmes['title'] == $nome) {
                    array_push($listaDeFilmes, $filmes);
                }
            } else if ($dataLancamento != null) {
                if ($filmes['release_date'] == $dataLancamento) {
                    array_push($listaDeFilmes, $filmes);
                }
            }

            if ($nome == null && $dataLancamento == null) {
                array_push($listaDeFilmes, $filmes);
            }
        }

        return $listaDeFilmes;
    }

    public static function toFilme($filmes)
    {
        $listaDeFilmes = [];

        foreach ($filmes as $key => $filme) {

            $listaDeFilmes[$key]['nome'] = $filme['title'];
            $listaDeFilmes[$key]['episodio'] = $filme['episode_id'];
            $listaDeFilmes[$key]['texto_abertura'] = $filme['opening_crawl'];
            $listaDeFilmes[$key]['data_lancamento'] = $filme['release_date'];
            $listaDeFilmes[$key]['personagens'] = $filme['characters'];
            $listaDeFilmes[$key]['planetas'] = $filme['planets'];
        }

        return $listaDeFilmes;
    }
}
