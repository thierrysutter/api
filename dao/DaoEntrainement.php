<?php
include_once '../dto/DtoEntrainement.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoEntrainement
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "entrainement";
    
    // constructor with $db as database connection
	public function __construct($db)
	{
		$this->setDb($db);
	}

	public function setDb(PDO $db)
	{
		$this->connexion = $db;
	}

    // read products
	public function getAll()
	{
		$entrainements = array();
		try
		{
			// select all query
			$query = "SELECT id, jour, heure_debut as heureDebut, heure_fin as heureFin, lieu
			FROM " . $this->bdd_table . " p
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$entrainements[] = new DtoEntrainement($donnees);		
			}
		
			return $entrainements;
		}
		catch(PDOException $ex)
		{
			return $entrainements;
		}
	}
	
	public function get($id)
	{
		$entrainements = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT id, jour, heure_debut as heureDebut, heure_fin as heureFin, lieu
				FROM " . $this->bdd_table . " p
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$entrainements[] = new DtoEntrainement($donnees);		
				}
				
				return $entrainements;
			}
			else
			{
				return $entrainements;
			}
		}
		catch(PDOException $ex)
		{
			return $entrainements;
		}
    }
    
    public function creerEntrainement(DtoEntrainement $entrainement)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				categorie=:categorie,
				jour=:jour,
				heure_debut=:heureDebut,
				heure_fin=:heureFin,
				lieu=:lieu,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$categorie=$entrainement->getCategorie();
		$jour=$entrainement->getJour();
		$heureDebut=(htmlspecialchars(strip_tags($entrainement->getHeureDebut())));
		$heureFin=(htmlspecialchars(strip_tags($entrainement->getHeureFin())));
		$lieu=$entrainement->getLieu();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":jour", $jour);
		$stmt->bindParam(":heureDebut", $heureDebut);
		$stmt->bindParam(":heureFin", $heureFin);
		$stmt->bindParam(":lieu", $lieu);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerEntrainement($id)
	{
		// delete query
		$query = "DELETE FROM " . $this->bdd_table . " WHERE id = ?";
 
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

	public function modifierEntrainement(DtoCategorie $entrainement)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				categorie=:categorie,
				jour=:jour,
				heure_debut=:heureDebut,
				heure_fin=:heureFin,
				lieu=:lieu,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$entrainement->getId();
		$categorie=$entrainement->getCategorie();
		$jour=$entrainement->getJour();
		$heureDebut=(htmlspecialchars(strip_tags($entrainement->getHeureDebut())));
		$heureFin=(htmlspecialchars(strip_tags($entrainement->getHeureFin())));
		$lieu=$entrainement->getLieu();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":jour", $jour);
		$stmt->bindParam(":heureDebut", $heureDebut);
		$stmt->bindParam(":heureFin", $heureFin);
		$stmt->bindParam(":lieu", $lieu);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function count()
	{
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->bdd_table . "";
 
		$stmt = $this->connexion->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function exists($id)
	{
	  // Si le param�tre est un string, c'est qu'on a fourni un login.
	  if ($id > 0) // On veut voir si tel utilisateur ayant pour login $login existe.
	  {
		// On ex�cute alors une requ�te COUNT() avec une clause WHERE, et on retourne un boolean.
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM " . $this->bdd_table . " WHERE id = ".$id."")->fetchColumn();
	  }
	  return false;
	}
}
?>