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
    global $entityManager;

    $payload = getJWTToken($request);
    $login  = $payload->userid;

    $utilisateurRepository = $entityManager->getRepository('Users');
    $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login));

    $filtre = $args['filtre'];

    $productRepository = $entityManager->getRepository('Products');
    $products = $productRepository->createQueryBuilder('p')
        ->where('p.name LIKE :filter OR p.price LIKE :filter OR p.category LIKE :filter')
        ->setParameter('filter', "%$filtre%")
        ->getQuery()
        ->getResult();

    $data = [];

    if ($utilisateur) {

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'category' => $product->getCategory(),
            ];
        }
    } else {
        $response = $response->withStatus(404);
    }

    $response->getBody()->write(json_encode($data));

    return addHeaders($response);
}

// API Nécessitant un Jwt valide
function getCatalogue(Request $request, Response $response, $args)
{
    global $entityManager;

    $payload = getJWTToken($request);
    $login  = $payload->userid;

    $utilisateurRepository = $entityManager->getRepository('Users');
    $utilisateur = $utilisateurRepository->findOneBy(array('login' => $login));

    $productRepository = $entityManager->getRepository('Products');
    $products = $productRepository->findAll();

    $data = [];

    if ($utilisateur) {

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'category' => $product->getCategory(),
            ];
        }
    } else {
        $response = $response->withStatus(404);
    }

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
