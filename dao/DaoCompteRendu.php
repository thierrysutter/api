<?php
include_once '../dto/DtoCompteRendu.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoCompteRendu
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "compte_rendu";
    
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
		$compteRendu = array();
		try
		{
			// select all query
			$query = "SELECT rencontre, texte, auteur_maj as auteur, derniere_maj as derniereMaj
			FROM " . $this->bdd_table . " p
			ORDER BY 1";

			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$compteRendus[] = new DtoCompteRendu($donnees);
			}
		
			return $compteRendus;
		}
		catch(PDOException $ex)
		{
			return $compteRendus;
		}
	}
	
	public function get($id)
	{
		$compteRendus = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT rencontre, texte, auteur_maj as auteur, derniere_maj as derniereMaj
				FROM " . $this->bdd_table . " p
				WHERE p.rencontre = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$compteRendus[] = new DtoCompteRendu($donnees);		
				}
				
				return $compteRendus;
			}
			else
			{
				return $compteRendus;
			}
		}
		catch(PDOException $ex)
		{
			return $compteRendus;
		}
    }
    
    public function creerCompteRendu(DtoCategorie $compteRendu)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				rencontre=:rencontre,
				texte=:texte,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$texte=(htmlspecialchars(strip_tags($compteRendu->getTexte())));
		$rencontre = $compteRendu->getRencontre();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":rencontre", $rencontre);
		$stmt->bindParam(":texte", $texte);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerCompteRendu($id)
	{
		// delete query
		$query = "DELETE FROM " . $this->bdd_table . " WHERE rencontre = ?";
 
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

	public function modifierCompteRendu(DtoCategorie $compteRendu)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				texte=:texte,
				auteur_maj=:userLogin,
				derniere_maj=now()
			WHERE rencontre = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$compteRendu->getRencontre();
		$texte=(htmlspecialchars(strip_tags($compteRendu->getTexte())));
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":texte", $texte);
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