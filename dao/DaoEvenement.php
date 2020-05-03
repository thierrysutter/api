<?php
include_once '../dto/DtoEvenement.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoEntrainement
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "evenement";
    
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
		$evenements = array();
		try
		{
			// select all query
			$query = "SELECT id, titre, texte, photo, lien, debut, fin, lieu, statut, document
			FROM " . $this->bdd_table . " p
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$evenements[] = new DtoEvenement($donnees);		
			}
		
			return $evenements;
		}
		catch(PDOException $ex)
		{
			return $evenements;
		}
	}
	
	public function get($id)
	{
		$evenements = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT id, titre, texte, photo, lien, debut, fin, lieu, statut, document
				FROM " . $this->bdd_table . " p
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$evenements[] = new DtoEvenement($donnees);		
				}
				
				return $evenements;
			}
			else
			{
				return $evenements;
			}
		}
		catch(PDOException $ex)
		{
			return $evenements;
		}
    }
    
    public function creerEvenement(DtoEvenement $evenement)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				titre=:titre,
				texte=:texte,
				photo=:photo,
				lien=:lien,
				debut=:debut,
				fin=:fin,
				lieu=:lieu,
				statut=:statut,
				document=:document,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$titre=$evenement->getTitre();
		$texte=$evenement->getTexte();
		$photo=(htmlspecialchars(strip_tags($evenement->getPhoto())));
		$lien=(htmlspecialchars(strip_tags($evenement->getLien())));
		$debut=$evenement->getDebut();
		$fin=$evenement->getFin();
		$lieu=$evenement->getLieu();
		$statut=$evenement->getStatut();
		$document=$evenement->getDocument();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":titre", $titre);
		$stmt->bindParam(":texte", $texte);
		$stmt->bindParam(":photo", $photo);
		$stmt->bindParam(":lien", $lien);
		$stmt->bindParam(":debut", $debut);
		$stmt->bindParam(":fin", $fin);
		$stmt->bindParam(":lieu", $lieu);
		$stmt->bindParam(":statut", $statut);
		$stmt->bindParam(":document", $document);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerEvenement($id)
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

	public function modifierEvenement(DtoEvenement $evenement)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				titre=:titre,
				texte=:texte,
				photo=:photo,
				lien=:lien,
				debut=:debut,
				fin=:fin,
				lieu=:lieu,
				statut=:statut,
				document=:document,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$evenement->getId();
		$titre=$evenement->getTitre();
		$texte=$evenement->getTexte();
		$photo=(htmlspecialchars(strip_tags($evenement->getPhoto())));
		$lien=(htmlspecialchars(strip_tags($evenement->getLien())));
		$debut=$evenement->getDebut();
		$fin=$evenement->getFin();
		$lieu=$evenement->getLieu();
		$statut=$evenement->getStatut();
		$document=$evenement->getDocument();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":titre", $titre);
		$stmt->bindParam(":texte", $texte);
		$stmt->bindParam(":photo", $photo);
		$stmt->bindParam(":lien", $lien);
		$stmt->bindParam(":debut", $debut);
		$stmt->bindParam(":fin", $fin);
		$stmt->bindParam(":lieu", $lieu);
		$stmt->bindParam(":statut", $statut);
		$stmt->bindParam(":document", $document);
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