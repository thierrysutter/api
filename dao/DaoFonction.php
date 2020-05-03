<?php
include_once '../dto/DtoFonction.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoFonction
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "fonction";
    
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
		$fonctions = array();
		try
		{
			// select all query
			$query = "SELECT  id, libelle
			FROM " . $this->bdd_table . " p
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$fonctions[] = new DtoFonction($donnees);		
			}
		
			return $fonctions;
		}
		catch(PDOException $ex)
		{
			return $fonctions;
		}
	}
	
	public function get($id)
	{
		$fonctions = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT id, libelle
				FROM " . $this->bdd_table . " p
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$fonctions[] = new DtoFonction($donnees);		
				}
				
				return $fonctions;
			}
			else
			{
				return $fonctions;
			}
		}
		catch(PDOException $ex)
		{
			return $fonctions;
		}
    }
    
    public function creerFonction(DtoFonction $fonction)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($fonction->getLibelle())));
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerFonction($id)
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

	public function modifierFonction(DtoFonction $fonction)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$fonction->getId();
		$libelle=(htmlspecialchars(strip_tags($fonction->getLibelle())));
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":libelle", $libelle);
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