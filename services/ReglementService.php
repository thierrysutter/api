<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../dao/DaoReglement.php';
include_once '../dto/DtoReglement.php';
include_once '../config/core.php';

$request_method = $_SERVER["REQUEST_METHOD"];
 
switch($request_method)
{
  case 'GET':
    // Récupérer les produits
    if(!empty($_GET["id"]))
    {
      $id = isset($_GET["id"]) ? $_GET["id"] : 0;
      getReglement($id);
    }
    else if(!empty($_GET["keywords"]))
    {
      $keywords = isset($_GET["keywords"]) ? $_GET["keywords"] : "";
      RechercherReglement($keywords);
    }
    else
    {
      getReglements();
    }
    break;
  case 'POST':
    // Ajouter un produit
    AjouterReglement();
    break;
  case 'PUT':
    ModifierReglement();
    break;
  case 'DELETE':
    SupprimerReglement();
    break;
  default:
    // Requête invalide
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

function getReglements()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $reglement = new DaoReglement($db);

    // query products
    $stmt = $reglement->getAll();
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

function getReglement($id=0)
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $reglement = new DaoReglement($db);

    // query products
    $stmt = $reglement->get($id);
    
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

function AjouterReglement()
{
  try
  {
    $data = json_decode(file_get_contents("php://input"));

    // make sure data is not empty
    if(!empty($data->titre))
    {
      // instantiate database and product object
      $database = new Database();
      $db = $database->getConnection();

      // initialize object
      $reglement = new DaoReglement($db);

      // set product property values
      $cat = new DtoReglement(array());
      $cat->setTitre($data->titre);
      $cat->setTexte($data->texte);
      
      // create the product
      if($reglement->creerReglement($cat))
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

function SupprimerReglement()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $reglement = new DaoReglement($db);

    // get Reglementd data
    $data = json_decode(file_get_contents("php://input"));
    
    if($reglement->supprimerReglement($data->id))
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

function ModifierReglement()
{
  try
  {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $reglement = new DaoReglement($db);

    // get Reglementd data
    $data = json_decode(file_get_contents("php://input"));
    
    // set product property values
    $cat = new DtoReglement(array());
    $cat->setId($data->id);
    $cat->setTitre($data->titre);
    $cat->setTexte($data->texte);
    
    // create the product
    if($reglement->modifierReglement($cat))
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