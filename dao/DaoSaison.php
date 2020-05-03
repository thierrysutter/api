<?php
include_once '../dto/DtoSaison.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoSaison
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

    // read products
	public function getAll()
	{
		$saisons = array();
		try
		{
			// select all query
			$query = "SELECT s.id, s.libelle, s.etat FROM saison s ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$saisons[] = new DtoSaison($donnees);		
			}
		
			return $saisons;
		}
		catch(PDOException $ex)
		{
			return $saisons;
		}
	}
	
	public function get($id)
	{
		$saisons = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT s.id, s.libelle, s.etat FROM saison s WHERE s.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$saisons[] = new DtoSaison($donnees);		
				}
				
				return $saisons;
			}
			else
			{
				return $saisons;
			}
		}
		catch(PDOException $ex)
		{
			return $saisons;
		}
    }

	public function count()
	{
		$query = "SELECT COUNT(*) as total_rows FROM saison";
 
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
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM saison WHERE id = ".$id."")->fetchColumn();
	  }
	  return false;
	}
    
    public function creerSaison(DtoSaison $saison)
	{
		// query to insert record
		$query = "INSERT INTO saison SET libelle=:libelle, etat=:etat, auteur_maj=:auteurMaj, derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($saison->getLibelle())));
		$etat=$saison->getEtat();
		$auteurMaj=$saison->getAuteurMaj();
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":categorie", $etat);
		$stmt->bindParam(":auteurMaj", $auteurMaj);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function modifierSaison(DtoSaison $saison)
	{		
		// query to insert record
		$query = "UPDATE saison SET libelle=:libelle, etat=:etat, auteur_maj=:auteurMaj, derniere_maj=now() WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$saison->getId();
		$libelle=(htmlspecialchars(strip_tags($saison->getLibelle())));
		$etat=$saison->getEtat();
		$auteurMaj=$saison->getAuteurMaj();
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":etat", $etat);
		$stmt->bindParam(":auteurMaj", $auteurMaj);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerSaison($id)
	{
		// delete query
		$query = "DELETE FROM saison WHERE id = ?";
 
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