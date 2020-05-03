<?php
include_once '../dto/DtoCategorie.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoCategorie
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "categorie";
    
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
		$categories = array();
		try
		{
			// select all query
			$query = "SELECT  id, libelle, annee_debut as anneeDebut, annee_fin as anneeFin
			FROM " . $this->bdd_table . " p
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$categories[] = new DtoCategorie($donnees);		
			}
		
			return $categories;
		}
		catch(PDOException $ex)
		{
			return $categories;
		}
	}
	
	public function get($id)
	{
		$categories = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT id, libelle, annee_debut as anneeDebut, annee_fin as anneeFin
				FROM " . $this->bdd_table . " p
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$categories[] = new DtoCategorie($donnees);		
				}
				
				return $categories;
			}
			else
			{
				return $categories;
			}
		}
		catch(PDOException $ex)
		{
			return $categories;
		}
    }
    
    public function creerCategorie(DtoCategorie $categorie)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				annee_debut=:anneeDebut,
				annee_fin=:anneeFin,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($categorie->getLibelle())));
		$anneeDebut=(htmlspecialchars(strip_tags($categorie->getAnneeDebut())));
		$anneeFin=(htmlspecialchars(strip_tags($categorie->getAnneeFin())));
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":anneeDebut", $anneeDebut);
		$stmt->bindParam(":anneeFin", $anneeFin);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerCategorie($id)
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

	public function modifierCategorie(DtoCategorie $categorie)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				annee_debut=:anneeDebut,
				annee_fin=:anneeFin, 
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$categorie->getId();
		$libelle=(htmlspecialchars(strip_tags($categorie->getLibelle())));
		$anneeDebut=(htmlspecialchars(strip_tags($categorie->getAnneeDebut())));
		$anneeFin=(htmlspecialchars(strip_tags($categorie->getAnneeFin())));
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":anneeDebut", $anneeDebut);
		$stmt->bindParam(":anneeFin", $anneeFin);
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