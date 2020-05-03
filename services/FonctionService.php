<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../dao/DaoFonction.php';
include_once '../dto/DtoFonction.php';
include_once '../config/core.php';

$request_method = $_SERVER["REQUEST_METHOD"];
 
switch($request_method)
{
  case 'GET':
    // Récupérer les produits
    if(!empty($_GET["id"]))
    {
      $id = isset($_GET["id"]) ? $_GET["id"] : 0;
      getFonction($id);
    }
    else if(!empty($_GET["keywords"]))
    {
      $keywords = isset($_GET["keywords"]) ? $_GET["keywords"] : "";
      RechercherFonction($keywords);
    }
    else
    {
      getFonctions();
    }
    break;
  case 'POST':
    // Ajouter un produit
    AjouterFonction();
    break;
  case 'PUT':
    ModifierFonction();
    break;
  case 'DELETE':
    SupprimerFonction();
    break;
  default:
    // Requête invalide
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

function getFonctions()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $fonction = new DaoFonction($db);

    // query products
    $stmt = $fonction->getAll();
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
    
      // tell the user no Categories found
      echo json_encode(array("message" => "Aucune catégorie trouvée.", "results" => null), JSON_PRETTY_PRINT);
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

function getFonction($id=0)
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $fonction = new DaoFonction($db);

    // query products
    $stmt = $fonction->get($id);
    
    $count = count($stmt);
    
    if ($count == 0)
    {
      // set response code - 404 Not found
      http_response_code(404);
    
      // tell the user no Categories found
      echo json_encode(array("message" => "Aucune catégorie trouvée.", "results" => null), JSON_PRETTY_PRINT);
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
    
      // tell the user no Categories found
      echo json_encode(array("message" => "Trop de catégories trouvées.", "results" => null), JSON_PRETTY_PRINT);
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

function AjouterFonction()
{
  try
  {
    $data = json_decode(file_get_contents("php://input"));

    // make sure data is not empty
    if(!empty($data->libelle))
    {
      // instantiate database and product object
      $database = new Database();
      $db = $database->getConnection();

      // initialize object
      $fonction = new DaoFonction($db);

      // set product property values
      $cat = new DtoFonction(array());
      $cat->setLibelle($data->libelle);
      
      // create the product
      if($fonction->creerFonction($cat))
      {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Catégorie créée avec succès."));
      }
      // if unable to create the product, tell the user
      else
      {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Erreur lors de la création de la catégorie."));
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
    echo json_encode(array("message" => "Erreur lors de la création de la catégorie.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function SupprimerFonction()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $fonction = new DaoFonction($db);

    // get Fonctiond data
    $data = json_decode(file_get_contents("php://input"));
    
    if($fonction->supprimerFonction($data->id))
    {
      // set response code - 200 ok
      http_response_code(200);
  
      // tell the user
      echo json_encode(array("message" => "Catégorie supprimée avec succès."));
    }
    else
    {
      // set response code - 503 service unavailable
      http_response_code(503);
  
      // tell the user
      echo json_encode(array("message" => "Erreur lors de la suppression de la catégorie."));
    }
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la suppression de la catégorie.", "results" => null), JSON_PRETTY_PRINT);
  }
}

function ModifierFonction()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $fonction = new DaoFonction($db);

    // get Fonctiond data
    $data = json_decode(file_get_contents("php://input"));
    
    // set product property values
    $cat = new DtoFonction(array());
    $cat->setId($data->id);
    $cat->setLibelle($data->libelle);
    
    // create the product
    if($fonction->modifierFonction($cat))
    {
      // set response code - 201 created
      http_response_code(201);

      // tell the user
      echo json_encode(array("message" => "Catégorie mis à jour avec succès."));
    }
    // if unable to create the product, tell the user
    else
    {
      // set response code - 503 service unavailable
      http_response_code(503);

      // tell the user
      echo json_encode(array("message" => "Erreur lors de la mise à jour de la catégorie."));
    }

    
  }
  catch (PDOException $ex)
  {
    // set response code - 404 KO
    http_response_code(404);

    // show products data in json format
    echo json_encode(array("message" => "Erreur lors de la mise à jour de la catégorie.", "results" => null), JSON_PRETTY_PRINT);
  }
}
?>