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
    $array["nom"] = $args['name'];
    $response->getBody()->write(json_encode($array));
    return $response;
}

function  getSearchCalatogue(Request $request, Response $response, $args)
{
    $filtre = $args['filtre'];
    $flux = '[{"id":1,"name":"Chaussures de course","description":"C\'est la description du produit 1","price":19.99,"category":"Categorie 1"},{"id":2,"name":"Balle de foot","description":"C\'est la description du produit 2","price":9.99,"category":"Categorie 1"},{"id":3,"name":"Casque vélo","description":"C\'est la description du produit 3","price":7.99,"category":"Categorie 1"},{"id":4,"name":"Chaussettes de foot","description":"C\'est la description du produit 4","price":5.99,"category":"Categorie 1"},{"id":5,"name":"Vin rouge de Bourgogne","description":"C\'est la description du produit 5","price":25.99,"category":"Categorie 3"},{"id":6,"name":"Foie gras","description":"C\'est la description du produit 6","price":39.99,"category":"Categorie 3"},{"id":7,"name":"Montre-bracelet connectée","description":"C\'est la description du produit 7","price":49.99,"category":"Categorie 2"},{"id":8,"name":"Machine à café","description":"C\'est la description du produit 8","price":89.99,"category":"Categorie 2"},{"id":9,"name":"Enceinte Bluetooth","description":"C\'est la description du produit 9","price":13.99,"category":"Categorie 2"},{"id":10,"name":"Sac à dos","description":"C\'est la description du produit 10","price":18.99,"category":"Categorie 1"},{"id":11,"name":"Tablette tactile","description":"C\'est la description du produit 11","price":99.99,"category":"Categorie 2"},{"id":12,"name":"Crémant d\'Alsace","description":"C\'est la description du produit 12","price":37.99,"category":"Categorie 3"},{"id":13,"name":"Écran d\'ordinateur ViewScreen","description":"C\'est la description du produit 13","price":59.99,"category":"Categorie 2"},{"id":14,"name":"Téléphone portable TechMaster","description":"C\'est la description du produit 14","price":79.99,"category":"Categorie 2"},{"id":15,"name":"Dindon de saison","description":"C\'est la description du produit 15","price":25.99,"category":"Categorie 3"},{"id":16,"name":"Appareil photo numérique","description":"C\'est la description du produit 16","price":35.99,"category":"Categorie 2"},{"id":17,"name":"Gewurztraminer vendanges tardives d\'Alsace","description":"C\'est la description du produit 17","price":27.99,"category":"Categorie 3"},{"id":18,"name":"Console de jeux","description":"C\'est la description du produit 18","price":299.99,"category":"Categorie 2"},{"id":19,"name":"Casque audio sans fil","description":"C\'est la description du produit 19","price":31.99,"category":"Categorie 2"},{"id":20,"name":"Blouson en cuir","description":"C\'est la description du produit 20","price":48.99,"category":"Categorie 1"}]';

    if ($filtre) {
        $data = json_decode($flux, true);

        $res = array_filter($data, function ($obj) use ($filtre) {
            return strpos($obj["titre"], $filtre) !== false;
        });
        $response->getBody()->write(json_encode(array_values($res)));
    } else {
        $response->getBody()->write($flux);
    }

    return addHeaders($response);
}

// API Nécessitant un Jwt valide
function getCatalogue(Request $request, Response $response, $args)
{
    $flux = '[{"id":1,"name":"Chaussures de course","description":"C\'est la description du produit 1","price":19.99,"category":"Categorie 1"},{"id":2,"name":"Balle de foot","description":"C\'est la description du produit 2","price":9.99,"category":"Categorie 1"},{"id":3,"name":"Casque vélo","description":"C\'est la description du produit 3","price":7.99,"category":"Categorie 1"},{"id":4,"name":"Chaussettes de foot","description":"C\'est la description du produit 4","price":5.99,"category":"Categorie 1"},{"id":5,"name":"Vin rouge de Bourgogne","description":"C\'est la description du produit 5","price":25.99,"category":"Categorie 3"},{"id":6,"name":"Foie gras","description":"C\'est la description du produit 6","price":39.99,"category":"Categorie 3"},{"id":7,"name":"Montre-bracelet connectée","description":"C\'est la description du produit 7","price":49.99,"category":"Categorie 2"},{"id":8,"name":"Machine à café","description":"C\'est la description du produit 8","price":89.99,"category":"Categorie 2"},{"id":9,"name":"Enceinte Bluetooth","description":"C\'est la description du produit 9","price":13.99,"category":"Categorie 2"},{"id":10,"name":"Sac à dos","description":"C\'est la description du produit 10","price":18.99,"category":"Categorie 1"},{"id":11,"name":"Tablette tactile","description":"C\'est la description du produit 11","price":99.99,"category":"Categorie 2"},{"id":12,"name":"Crémant d\'Alsace","description":"C\'est la description du produit 12","price":37.99,"category":"Categorie 3"},{"id":13,"name":"Écran d\'ordinateur ViewScreen","description":"C\'est la description du produit 13","price":59.99,"category":"Categorie 2"},{"id":14,"name":"Téléphone portable TechMaster","description":"C\'est la description du produit 14","price":79.99,"category":"Categorie 2"},{"id":15,"name":"Dindon de saison","description":"C\'est la description du produit 15","price":25.99,"category":"Categorie 3"},{"id":16,"name":"Appareil photo numérique","description":"C\'est la description du produit 16","price":35.99,"category":"Categorie 2"},{"id":17,"name":"Gewurztraminer vendanges tardives d\'Alsace","description":"C\'est la description du produit 17","price":27.99,"category":"Categorie 3"},{"id":18,"name":"Console de jeux","description":"C\'est la description du produit 18","price":299.99,"category":"Categorie 2"},{"id":19,"name":"Casque audio sans fil","description":"C\'est la description du produit 19","price":31.99,"category":"Categorie 2"},{"id":20,"name":"Blouson en cuir","description":"C\'est la description du produit 20","price":48.99,"category":"Categorie 1"}]';
    $data = json_decode($flux, true);

    $response->getBody()->write(json_encode($data));

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
    global $entityManager;

    $payload = getJWTToken($request);
    $login  = $payload->userid;

    $utilisateurRepository = $entityManager->getRepository('Users');
    $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login));
    if ($utilisateur) {
        $data = array('nom' => $utilisateur->getLastname(), 'prenom' => $utilisateur->getFirstname());
        $response = addHeaders($response);
        $response = createJwT($response);
        $response->getBody()->write(json_encode($data));
    } else {
        $response = $response->withStatus(404);
    }

    return addHeaders($response);
}

// APi d'authentification générant un JWT
function postLogin(Request $request, Response $response, $args)
{
    global $entityManager;
    $err = false;
    $body = $request->getParsedBody();
    $login = $body['login'] ?? "";
    $pass = $body['password'] ?? "";

    if (!preg_match("/[a-zA-Z0-9]{1,20}/", $login)) {
        $err = true;
    }
    if (!preg_match("/[a-zA-Z0-9]{1,20}/", $pass)) {
        $err = true;
    }
    if (!$err) {
        $utilisateurRepository = $entityManager->getRepository('Users');
        $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login));
        if ($utilisateur && password_verify($pass, $utilisateur->getPassword())) {
            $response = addHeaders($response);
            $response = createJwT($response);
            $data = array('name' => $utilisateur->getLastname(), 'prenom' => $utilisateur->getFirstname());
            $response->getBody()->write(json_encode($data));
        } else {
            $response = $response->withStatus(403);
        }
    } else {
        $response = $response->withStatus(500);
    }

    return addHeaders($response);
}

// Création d'un produit nécessitant un JWT valide en utilisant Doctrine avec les champs : name, imgurl, description, price, category
function createProduct(Request $request, Response $response, $args)
{
    global $entityManager;

    $payload = getJWTToken($request);
    $login  = $payload->userid;

    $utilisateurRepository = $entityManager->getRepository('Users');
    $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login));

    $err = false;
    $body = $request->getParsedBody();
    $name = $body['name'] ?? "";
    $imgurl = $body['imgurl'] ?? "";
    $description = $body['description'] ?? "";
    $price = $body['price'] ?? "";
    $category = $body['category'] ?? "";

    if (!preg_match("/[a-zA-Z0-9]{1,20}/", $name)) {
        $err = true;
    }
    if (!preg_match("/[a-zA-Z0-9]{1,20}/", $description)) {
        $err = true;
    }
    if (!preg_match("/^[a-zA-Z0-9.,]{1,20}$/", $price)) {
        $err = true;
    }
    if (!preg_match("/[a-zA-Z0-9]{1,20}/", $category)) {
        $err = true;
    }
    if ($utilisateur && !$err) {
        $product = new Products();
        $product->setName($name);
        $product->setImgurl($imgurl);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setCategory($category);
        $entityManager->persist($product);
        $entityManager->flush();
        $response = addHeaders($response);
        $response = createJwT($response);
        $data = array('name' => $product->getName(), 'imgurl' => $product->getImgurl(), 'description' => $product->getDescription(), 'price' => $product->getPrice(), 'category' => $product->getCategory());
        $response->getBody()->write(json_encode($data));
    } else {
        $response = $response->withStatus(500);
    }

    return addHeaders($response);
}


// Création d'un utilisateur + génération d'un JWT en utilisant Doctrine avec les champs : lastname, firstname, adress, postalcode, city, email, sex, login, password, phonenumber
function createUtilisateur(Request $request, Response $response)
{
    global $entityManager;

    $err = false;
    $body = $request->getParsedBody();

    $lastname = $body['lastname'] ?? "";
    $firstname = $body['firstname'] ?? "";
    $adress = $body['adress'] ?? "";
    $postalcode = $body['postalcode'] ?? "";
    $city = $body['city'] ?? "";
    $email = $body['email'] ?? "";
    $sex = $body['sex'] ?? "";
    $login = $body['login'] ?? "";
    $password = $body['password'] ?? "";
    $phonenumber = $body['phonenumber'] ?? "";

    // Utilisation de filtres pour nettoyer les données
    $lastname = filter_var($lastname, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $firstname = filter_var($firstname, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $adress = filter_var($adress, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $postalcode = filter_var($postalcode, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $city = filter_var($city, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $sex = filter_var($sex, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $login = filter_var($login, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $password = password_hash($password, PASSWORD_BCRYPT);
    $phonenumber = filter_var($phonenumber, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    // Vérification des données
    if (empty($lastname) || empty($firstname) || empty($adress) || empty($postalcode) || empty($city) || empty($email) || empty($sex) || empty($login) || empty($password) || empty($phonenumber)) {
        $err = true;
    }

    if (!$err) {
        $user = new Users;
        $user->setLastname($lastname);
        $user->setFirstname($firstname);
        $user->setAdress($adress);
        $user->setPostalcode($postalcode);
        $user->setCity($city);
        $user->setEmail($email);
        $user->setSex($sex);
        $user->setLogin($login);
        $user->setPassword($password);
        $user->setPhonenumber($phonenumber);
        $entityManager->persist($user);
        $entityManager->flush();
        $response = createJwT($response);
        $data = array('lastname' => $user->getLastname(), 'firstname' => $user->getFirstname(), 'adress' => $user->getAdress(), 'postalcode' => $user->getPostalcode(), 'city' => $user->getCity(), 'email' => $user->getEmail(), 'sex' => $user->getSex(), 'login' => $user->getLogin(), 'password' => $user->getPassword(), 'phonenumber' => $user->getPhonenumber());
        $response->getBody()->write(json_encode($data));
    } else {
        $response = $response->withStatus(500);
    }
    return $response;
}
