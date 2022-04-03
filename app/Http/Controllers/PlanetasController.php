<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlanetasController extends Controller
{

    /*
    | Endpoint: Planetas
    |
    */
    public function listaDePlanetas(Request $request, $array = false)
    {

        $nome = $request->input('nome') ? $request->input('nome') : null;
        $populacao = $request->input('populacao') ? $request->input('populacao') : null;
        $clima = $request->input('clima') ? $request->input('clima') : null;

        $filtroPorPlaneta = $this->filtroPorPlaneta($nome, $populacao, $clima);

        $listaDePlanetas = $this->toPlaneta($filtroPorPlaneta);

        if ($array) {
            return $listaDePlanetas;
        }

        return response(json_encode($listaDePlanetas), 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "GET")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
    }


    /*
    | Endpoint: Detalhe Planeta
    |
    */
    public function listaDePlanetaDetalhes($planeta_id)
    {
        $planeta = Http::get('https://swapi.dev/api/planets/' . $planeta_id)->json();

        $listaDePlanetaDetalhes = [];
        $listaDePlanetaDetalhes['id'] = $planeta_id;
        $listaDePlanetaDetalhes['nome'] = $planeta['name'];
        $listaDePlanetaDetalhes['periodo_rotacao'] = $planeta['rotation_period'];
        $listaDePlanetaDetalhes['periodo_orbita'] = $planeta['orbital_period'];
        $listaDePlanetaDetalhes['diametro'] = $planeta['diameter'];
        $listaDePlanetaDetalhes['clima'] = $planeta['climate'];
        $listaDePlanetaDetalhes['residentes'] = $planeta['residents'];
        $listaDePlanetaDetalhes['populacao'] = $planeta['population'];
        $listaDePlanetaDetalhes['filmes'] = $planeta['films'];


        return response(json_encode($listaDePlanetaDetalhes), 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "GET")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
    }

    /*
    | Endpoint: Planetas Filmes
    |
    */
    public function listaDePlanetasFilmes($planeta_id)
    {

        $listaDePlanetas = Http::get('https://swapi.dev/api/planets/' . $planeta_id)->json();

        $listaDeFilmes = [];

        foreach ($listaDePlanetas['films'] as $filme) {
            $getFilmes[] = Http::get($filme)->json();

            $listaDeFilmes = FilmesController::toFilme($getFilmes);
        }

        return response(json_encode($listaDeFilmes), 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "GET")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
    }

    /*
    | Endpoint: Planetas Personagens
    |
    */
    public function listaDePlanetasPersonagens($planeta_id)
    {

        $listaDePlanetas = Http::get('https://swapi.dev/api/planets/' . $planeta_id)->json();

        $listaDePersonagem = [];

        foreach ($listaDePlanetas['residents'] as $personagens) {
            $getPersonagens[] = Http::get($personagens)->json();
            $listaDePersonagem = PersonagensController::toPersonagem($getPersonagens);
        }

        return response(json_encode($listaDePersonagem), 200)
            ->header('Content-Type', 'application/json');
    }

    public function filtroPorPlaneta($nome, $populacao, $clima)
    {

        $planetasSwapi = Http::get('https://swapi.dev/api/planets/')->json()['results'];

        $listaDePlanetas = [];

        foreach ($planetasSwapi as $planetas) {

            if ($nome != null) {
                if ($planetas['name'] == $nome) {
                    array_push($listaDePlanetas, $planetas);
                }
            } else if ($populacao != null) {
                if ($planetas['population'] == $populacao) {
                    array_push($listaDePlanetas, $planetas);
                }
            } else if ($clima != null) {
                if ($planetas['climate'] == $clima) {
                    array_push($listaDePlanetas, $planetas);
                }
            }

            if ($nome == null && $populacao == null && $clima == null) {
                array_push($listaDePlanetas, $planetas);
            }
        }

        return $listaDePlanetas;
    }

    public static function toPlaneta($planetas)
    {
        $listaDePlanetas = [];

        $id = 1;

        foreach ($planetas as $key => $planeta) {

            $listaDePlanetas[$key]['id'] = $id;
            $listaDePlanetas[$key]['nome'] = $planeta['name'];
            //$listaDePlanetas[$key]['periodo_rotacao'] = $planeta['rotation_period'];
            //$listaDePlanetas[$key]['periodo_orbita'] = $planeta['orbital_period'];
            //$listaDePlanetas[$key]['diametro'] = $planeta['diameter'];
            //$listaDePlanetas[$key]['clima'] = $planeta['climate'];
            //$listaDePlanetas[$key]['residentes'] = $planeta['residents'];
            $listaDePlanetas[$key]['populacao'] = $planeta['population'];
            //$listaDePlanetas[$key]['filmes'] = $planeta['films'];

            $id++;
        }

        return $listaDePlanetas;
    }
}
