<?php
include_once '../dto/DtoDivision.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoDivision
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "division";
    
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
		$divisions = array();
		try
		{
			// select all query
			$query = "SELECT  p.id, p.libelle, p.categorie, c.libelle as libelleCategorie
            FROM " . $this->bdd_table . " p , categorie c
            WHERE p.categorie = c.id
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$divisions[] = new DtoDivision($donnees);		
			}
		
			return $divisions;
		}
		catch(PDOException $ex)
		{
			return $divisions;
		}
	}
	
	public function get($id)
	{
		$divisions = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT  p.id, p.libelle, p.categorie, c.libelle as libelleCategorie
                FROM " . $this->bdd_table . " p , categorie c
				WHERE p.categorie = c.id and p.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$divisions[] = new DtoDivision($donnees);		
				}
				
				return $divisions;
			}
			else
			{
				return $divisions;
			}
		}
		catch(PDOException $ex)
		{
			return $divisions;
		}
    }
    
    public function creerDivision(DtoDivision $division)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
                categorie=:categorie,
                auteur_maj='',
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($division->getLibelle())));
		$categorie=$division->getCategorie();
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":categorie", $categorie);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerDivision($id)
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

	public function modifierDivision(DtoDivision $division)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				categorie=:categorie,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$division->getId();
		$libelle=(htmlspecialchars(strip_tags($division->getLibelle())));
		$categorie=$division->getCategorie();
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