<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonagensController extends Controller
{

    public function listaDePersonagens(Request $request)
    {
        $nome = $request->input('nome') ? $request->input('nome') : null;
        $genero = $request->input('gender') ? $request->input('gender') : null;

        $filtroPorPersonagem = $this->filtroPorPersonagem($nome, $genero);
        $listaDePersonagens = self::toPersonagem($filtroPorPersonagem);

        return response(json_encode($listaDePersonagens), 200)
            ->header('Content-Type', 'application/json');
    }

    public function filtroPorPersonagem($nome, $genero)
    {

        $personagensSwapi = Http::get('https://swapi.dev/api/people/')->json()['results'];

        $listaDePersonagens = [];

        foreach ($personagensSwapi as $personagens) {

            if ($nome != null) {
                if ($personagens['name'] == $nome) {
                    array_push($listaDePersonagens, $personagens);
                }
            } else if ($genero != null) {
                if ($personagens['gender'] == $genero) {
                    array_push($listaDePersonagens, $personagens);
                }
            }

            if ($nome == null && $genero == null) {
                array_push($listaDePersonagens, $personagens);
            }
        }

        return $listaDePersonagens;
    }

    public static function toPersonagem($personagens)
    {
        $listaDePersonagens = [];

        foreach ($personagens as $key => $personagem) {

            $listaDePersonagens[$key]['nome'] = $personagem['name'];
            $listaDePersonagens[$key]['ano_aniversario'] = $personagem['birth_year'];
            $listaDePersonagens[$key]['gender'] = $personagem['gender'];
            $listaDePersonagens[$key]['nome'] = $personagem['name'];
            $listaDePersonagens[$key]['filmes'] = $personagem['films'];
        }

        return $listaDePersonagens;
    }
}
