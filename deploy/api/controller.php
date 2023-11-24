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
    $flux = '[{"id":1,"name":"Chaussures de course","description":"C\'est la description du produit 1","price":19.99,"category":"Categorie 1"},{"id":2,"name":"Balle de foot","description":"C\'est la description du produit 2","price":9.99,"category":"Categorie 1"},{"id":3,"name":"Casque vélo","description":"C\'est la description du produit 3","price":7.99,"category":"Categorie 1"},{"id":4,"name":"Chaussettes de foot","description":"C\'est la description du produit 4","price":5.99,"category":"Categorie 1"},{"id":5,"name":"Vin rouge de Bourgogne","description":"C\'est la description du produit 5","price":25.99,"category":"Categorie 3"},{"id":6,"name":"Foie gras","description":"C\'est la description du produit 6","price":39.99,"category":"Categorie 3"},{"id":7,"name":"Montre-bracelet connectée","description":"C\'est la description du produit 7","price":49.99,"category":"Categorie 2"},{"id":8,"name":"Machine à café","description":"C\'est la description du produit 8","price":89.99,"category":"Categorie 2"},{"id":9,"name":"Enceinte Bluetooth","description":"C\'est la description du produit 9","price":13.99,"category":"Categorie 2"},{"id":10,"name":"Sac à dos","description":"C\'est la description du produit 10","price":18.99,"category":"Categorie 1"},{"id":11,"name":"Tablette tactile","description":"C\'est la description du produit 11","price":99.99,"category":"Categorie 2"},{"id":12,"name":"Crémant d\'Alsace","description":"C\'est la description du produit 12","price":37.99,"category":"Categorie 3"},{"id":13,"name":"Écran d\'ordinateur ViewScreen","description":"C\'est la description du produit 13","price":59.99,"category":"Categorie 2"},{"id":14,"name":"Téléphone portable TechMaster","description":"C\'est la description du produit 14","price":79.99,"category":"Categorie 2"},{"id":15,"name":"Dindon de saison","description":"C\'est la description du produit 15","price":25.99,"category":"Categorie 3"},{"id":16,"name":"Appareil photo numérique","description":"C\'est la description du produit 16","price":35.99,"category":"Categorie 2"},{"id":17,"name":"Gewurztraminer vendanges tardives d\'Alsace","description":"C\'est la description du produit 17","price":27.99,"category":"Categorie 3"},{"id":18,"name":"Console de jeux","description":"C\'est la description du produit 18","price":299.99,"category":"Categorie 2"},{"id":19,"name":"Casque audio sans fil","description":"C\'est la description du produit 19","price":31.99,"category":"Categorie 2"},{"id":20,"name":"Blouson en cuir","description":"C\'est la description du produit 20","price":48.99,"category":"Categorie 1"}]';

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

    $flux = '{"lastName":"Curtis","firstName":"emma"}';

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
                'lastName' => 'Curtis',
                'firstName' => 'emma',
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
