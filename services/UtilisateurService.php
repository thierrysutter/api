<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../dao/DaoUtilisateur.php';
include_once '../dto/DtoUtilisateur.php';
include_once '../config/core.php';

$request_method = $_SERVER["REQUEST_METHOD"];
 
switch($request_method)
{
  case 'GET':
    // Récupérer les produits
    if(!empty($_GET["login"]))
    {
      $login = isset($_GET["login"]) ? $_GET["login"] : "";
      getUser($login);
    }
    else if(!empty($_GET["keywords"]))
    {
      $keywords = isset($_GET["keywords"]) ? $_GET["keywords"] : "";
      RechercherUtilisateur($keywords);
    }
    else if(!empty($_GET["paging"]))
    {
      $keywords = isset($_GET["paging"]) ? $_GET["paging"] : "";
      GetUsersWithPaging();
    }
    else
    {
      getUsers();
    }
    break;
  case 'POST':
    // Ajouter un produit
    AjouterUtilisateur();
    break;
  case 'PUT':
    ModifierUtilisateur();
    break;
  case 'DELETE':
    SupprimerUtilisateur();
    break;
  default:
    // Requête invalide
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

function getUsers()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // query products
    $stmt = $utilisateur->getAll();
    $count = count($stmt);
    if($count > 0)
    {
      // set response code - 200 OK
      http_response_code(200);

      // show products data in json format
      echo json_encode(array("message" => "OK", "results" => $stmt), JSON_PRETTY_PRINT);
    }
    else
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no utilisateurs found
      echo json_encode(array("message" => "Aucun utilisateur trouvé.", "results" => null), JSON_PRETTY_PRINT);
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la recherche.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function GetUsersWithPaging()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // query products
    $stmt = $utilisateur->getPaging();
    
    $count = count($stmt);
    
    if($count > 0)
    {
      // set response code - 200 OK
      http_response_code(200);

      // show products data in json format
      echo json_encode(array("message" => "OK", "results" => $stmt), JSON_PRETTY_PRINT);
    }
    else
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no utilisateurs found
      echo json_encode(array("message" => "Aucun utilisateur trouvé.", "results" => null), JSON_PRETTY_PRINT);
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la recherche.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function getUser($login='')
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // query products
    $stmt = $utilisateur->get($login);
    
    $count = count($stmt);
    
    if ($count == 0)
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no utilisateurs found
      echo json_encode(array("message" => "Aucun utilisateur trouvé.", "results" => null), JSON_PRETTY_PRINT);
    }
    else if ($count == 1)
    {
      // set response code - 200 OK
      http_response_code(200);

      // show products data in json format
      echo json_encode(array("message" => "OK", "results" => $stmt), JSON_PRETTY_PRINT);
    }
    else
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no utilisateurs found
      echo json_encode(array("message" => "Trop d'utilisateur trouvés.", "results" => null), JSON_PRETTY_PRINT);
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la recherche.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function AjouterUtilisateur()
{
  try
  {
    $data = json_decode(file_get_contents("php://input"));

    // make sure data is not empty
    if(!empty($data->login) && !empty($data->password) && !empty($data->email) && !empty($data->nom) && !empty($data->prenom))
    {
      // instantiate database and product object
      $database = new Database();
      $db = $database->getConnection();

      // initialize object
      $utilisateur = new DaoUtilisateur($db);

      // set product property values
      $user = new DtoUtilisateur(array());
      $user->setLogin($data->login);
      $user->setPassword($data->password);
      $user->setEmail($data->email);
      $user->setNom($data->nom);
      $user->setPrenom($data->prenom);
      $user->setDateNaissance("");
      $user->setAdresse("");
      $user->setCodePostal("");
      $user->setVille("");
      $user->setTelFixe("");
      $user->setTelPortable("");
      $user->setPhoto("");
      $user->setSuperAdmin(0);

      // create the product
      if($utilisateur->creerUtilisateur($user))
      {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Utilisateur créé avec succès."));
      }
      // if unable to create the product, tell the user
      else
      {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Erreur lors de la création de l'utilisateur."));
      }
    }
    // tell the user data is incomplete
    else
    { 
      // set response code - 400 bad request
      http_response_code(400);

      // tell the user
      echo json_encode(array("message" => "Création impossible. Données incomplètes."));
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la création de l'utilisateur.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function SupprimerUtilisateur()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    if($utilisateur->supprimerUtilisateur($data->login))
    {
      // set response code - 200 ok
      http_response_code(200);
  
      // tell the user
      echo json_encode(array("message" => "Utilisateur supprimé avec succès."));
    }
    else
    {
      // set response code - 503 service unavailable
      http_response_code(503);
  
      // tell the user
      echo json_encode(array("message" => "Erreur lors de la suppression de l'utilisateur."));
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la suppression de l'utilisateur.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function ModifierUtilisateur()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set product property values
    $user = new DtoUtilisateur(array());
    $user->setLogin($data->login);
    $user->setEmail($data->email);
    $user->setNom($data->nom);
    $user->setPrenom($data->prenom);
    $user->setDateNaissance($data->dateNaissance);
    $user->setAdresse($data->adresse);
    $user->setCodePostal($data->codePostal);
    $user->setVille($data->ville);
    $user->setTelFixe($data->telFixe);
    $user->setTelPortable($data->telPortable);
    $user->setPhoto($data->photo);
    
    // create the product
    if($utilisateur->modifierUtilisateur($user))
    {
      // set response code - 201 created
      http_response_code(201);

      // tell the user
      echo json_encode(array("message" => "Utilisateur mis à jour avec succès."));
    }
    // if unable to create the product, tell the user
    else
    {
      // set response code - 503 service unavailable
      http_response_code(503);

      // tell the user
      echo json_encode(array("message" => "Erreur lors de la mise à jour de l'utilisateur."));
    }

    
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la mise à jour de l'utilisateur.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function RechercherUtilisateur($keywords='')
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $utilisateur = new DaoUtilisateur($db);

    // query products
    $stmt = $utilisateur->search($keywords);
    
    $count = count($stmt);
    
    if ($count == 0)
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no utilisateurs found
      echo json_encode(array("message" => "Aucun utilisateur trouvé.", "results" => null), JSON_PRETTY_PRINT);
    }
    else
    {
      // set response code - 200 OK
      http_response_code(200);

      // show products data in json format
      echo json_encode(array("message" => "OK", "results" => $stmt), JSON_PRETTY_PRINT);
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la recherche.", "results" => null), JSON_PRETTY_PRINT);
  }
}
?>