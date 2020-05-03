<?php
include_once '../dao/DaoBase.php';
include_once '../dto/DtoTypeCompetition.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoTypeCompetition
{
	// connexion à la base de données
    private $connexion;
    
    // constructor with $db as database connection
	public function __construct($db)
	{
		$this->setDb($db);
	}

	public function setDb(PDO $db)
	{
		$this->connexion = $db;
	}

	public function Compte()
	{
		$query = "SELECT COUNT(*) as total_rows FROM type_competition";
 
		$stmt = $this->connexion->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function exists($id)
	{
	  if ($id > 0)
	  {
		// On exécute alors une requète COUNT() avec une clause WHERE, et on retourne un boolean.
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM type_competition WHERE id = ".$id."")->fetchColumn();
	  }
	  return false;
	}
	
	// read products
	public function getAll()
	{
		$typeCompetitions = array();
		try
		{
			// select all query
			$query = "SELECT  p.id, p.libelle, p.categorie, c.libelle as libelleCategorie FROM type_competition p , categorie c WHERE p.categorie = c.id ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$typeCompetitions[] = new DtoTypeCompetition($donnees);		
			}
		
			return $typeCompetitions;
		}
		catch(PDOException $ex)
		{
			return $typeCompetitions;
		}
	}
	
	public function get($id)
	{
		$typeCompetitions = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT  p.id, p.libelle, p.categorie, c.libelle as libelleCategorie FROM type_competition p , categorie c WHERE p.categorie = c.id and p.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$typeCompetitions[] = new DtoTypeCompetition($donnees);		
				}
				
				return $typeCompetitions;
			}
			else
			{
				return $typeCompetitions;
			}
		}
		catch(PDOException $ex)
		{
			return $typeCompetitions;
		}
    }
    
    public function creerTypeCompetition(DtoTypeCompetition $typeCompetition)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			type_competition
			SET
				libelle=:libelle,
                categorie=:categorie,
                auteur_maj='',
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($typeCompetition->getLibelle())));
		$categorie=$typeCompetition->getCategorie();
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":categorie", $categorie);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function modifierTypeCompetition(DtoTypeCompetition $typeCompetition)
	{		
		// query to insert record
		$query = "UPDATE
			type_competition
			SET
				libelle=:libelle,
				categorie=:categorie,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$typeCompetition->getId();
		$libelle=(htmlspecialchars(strip_tags($typeCompetition->getLibelle())));
		$categorie=$typeCompetition->getCategorie();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerTypeCompetition($id)
	{
		// delete query
		$query = "DELETE FROM type_competition WHERE id = ?";
 
		// prepare query
		$stmt = $this->connexion->prepare($query);
	 
		// bind id of record to delete
		$stmt->bindParam(1, $id);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
    }
}
?>