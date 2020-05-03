<?php
include_once '../dto/DtoEquipe.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoArticle
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "equipe";
    
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
		$equipes = array();
		try
		{
			// select all query
			$query = "SELECT p.id, p.categorie, categorie.libelle as libelleCategorie, p.libelle, p.entraineur, p.adjoint, p.delegue, p.lien_classement as lienClassement
			FROM " . $this->bdd_table . " p left outer join categorie on (categorie.id=p.categorie)
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$equipes[] = new DtoEquipe($donnees);		
			}
		
			return $equipes;
		}
		catch(PDOException $ex)
		{
			return $equipes;
		}
	}
	
	public function get($id)
	{
		$equipes = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT p.id, p.categorie, categorie.libelle as libelleCategorie, p.libelle, p.entraineur, p.adjoint, p.delegue, p.lien_classement as lienClassement
				FROM " . $this->bdd_table . " p left outer join categorie on (categorie.id=p.categorie)
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$equipes[] = new DtoEquipe($donnees);		
				}
				
				return $equipes;
			}
			else
			{
				return $equipes;
			}
		}
		catch(PDOException $ex)
		{
			return $equipes;
		}
    }
    
    public function creerEquipe(DtoEquipe $equipe)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				categorie=:categorie,
				libelle=:libelle,
				lien_classement=:lien_classement,
				auteur=:auteur,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$categorie=$equipe->getCategorie();
		$libelle=(htmlspecialchars(strip_tags($equipe->getLibelle())));
		$lienClassement=(htmlspecialchars(strip_tags($equipe->getLienClassement())));
		$auteur=(htmlspecialchars(strip_tags($equipe->getAuteur())));
		
		// bind values
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":lienClassement", $lienClassement);
		$stmt->bindParam(":auteur", $auteur);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerEquipe($id)
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

	public function modifierEquipe(DtoEquipe $equipe)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				categorie=:categorie,
				libelle=:libelle,
				lien_classement=:lien_classement,
				auteur=:auteur,
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$equipe->getId();
		$categorie=$equipe->getCategorie();
		$libelle=(htmlspecialchars(strip_tags($equipe->getLibelle())));
		$lienClassement=(htmlspecialchars(strip_tags($equipe->getLienClassement())));
		$auteur=(htmlspecialchars(strip_tags($equipe->getAuteur())));
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":lienClassement", $lienClassement);
		$stmt->bindParam(":auteur", $auteur);
		
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