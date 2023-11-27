<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

function optionsCatalogue(Request $request, Response $response, $args)
{

    // Evite que le front demande une confirmation à chaque modification
    $response = $response->withHeader("Access-Control-Max-Age", 600);

    return addHeaders($response);
}

function hello(Request $request, Response $response, $args)
{
    $array = [];
    $array["Name"] = $args['name'];
    $response->getBody()->write(json_encode($array));
    return $response;
}

function  getSearchCalatogue(Request $request, Response $response, $args)
{
    $flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';

    $response->getBody()->write($flux);

    return addHeaders($response);
}

// API Nécessitant un Jwt valide
function getCatalogue(Request $request, Response $response, $args)
{
    session_start();

    // Vérifier l'état de connexion
    if (!isset($_SESSION['user_id'])) {
        // L'utilisateur n'est pas connecté, renvoyer une réponse vide
        $response->getBody()->write('');
        return addHeaders($response, 401); // Renvoyer une réponse 401 (Non autorisé)
    }

    // Si l'utilisateur est connecté, renvoyer le catalogue
    $flux = '[{"id":1,"name":"Chaussures de course","description":"C\'est la description du produit 1","price":19.99,"category":"Categorie 1"}, ... (rest of your products)]';
    $response->getBody()->write($flux);

    return addHeaders($response);
}


function optionsUtilisateur(Request $request, Response $response, $args)
{

    // Evite que le front demande une confirmation à chaque modification
    $response = $response->withHeader("Access-Control-Max-Age", 600);

    return addHeaders($response);
}

// API Nécessitant un Jwt valide
function getUtilisateur(Request $request, Response $response, $args)
{

    $payload = getJWTToken($request);
    $login  = $payload->userid;

    $flux = '{"lastName":"Watson","firstName":"Emma"}';

    $response->getBody()->write($flux);

    return addHeaders($response);
}

// APi d\'Authentification générant un JWT
function postLogin(Request $request, Response $response, $args)
{
    $body = $request->getParsedBody();

    if (isset($body['username']) && isset($body['password'])) {
        $username = $body['username'];
        $password = $body['password'];

        if ($username === 'emma' && $password === 'toto') {
            $token = createJWT($response);

            $userData = [
                'lastName' => 'Watson',
                'firstName' => 'Emma',
            ];

            $flux = json_encode($userData);

            $response = createJwt($response, $token);

            $response->getBody()->write($flux);

            return addHeaders($response);
        }
    }

    $response->getBody()->write(json_encode(['error' => 'Identifiants incorrects']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
}

function postLogout(Request $request, Response $response, $args)
{
    $response = $response->withHeader('Set-Cookie', 'jwt=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT');

    $response->getBody()->write(json_encode(['message' => 'Déconnexion réussie']));

    return addHeaders($response);
}