<?php
include_once '../dto/DtoCompetition.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoCompetition
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "competition";
    
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
		$competitions = array();
		try
		{
			// select all query
			$query = "SELECT  p.id, p.libelle, p.type_competition as typeCompetition, p.categorie, p.division, p.saison, p.equipe, p.actif,
			c.libelle as libelleCategorie, d.libelle as libelleDivision, e.libelle as libelleEquipe, s.libelle as libelleSaison, t.libelle as libelleTypeCompetition
			FROM " . $this->bdd_table . " p, categorie c, division d, equipe e, saison s, type_competition t
			WHERE p.categorie = c.id
			AND p.division = d.id
			AND p.equipe = e.id
			AND p.saison = s.id
			AND p.type_competition = t.id
			ORDER BY 1";

			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$competitions[] = new DtoCompetition($donnees);
			}
		
			return $competitions;
		}
		catch(PDOException $ex)
		{
			return $competitions;
		}
	}
	
	public function get($id)
	{
		$competitions = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT  p.id, p.libelle, p.type_competition as typeCompetition, p.categorie, p.division, p.saison, p.equipe, p.actif,
				c.libelle as libelleCategorie, d.libelle as libelleDivision, e.libelle as libelleEquipe, s.libelle as libelleSaison, t.libelle as libelleTypeCompetition
				FROM " . $this->bdd_table . " p, categorie c, division d, equipe e, saison s, type_competition t
				WHERE p.categorie = c.id
				AND p.division = d.id
				AND p.equipe = e.id
				AND p.saison = s.id
				AND p.type_competition = t.id
				AND p.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$competitions[] = new DtoCompetition($donnees);		
				}
				
				return $competitions;
			}
			else
			{
				return $competitions;
			}
		}
		catch(PDOException $ex)
		{
			return $competitions;
		}
    }
    
    public function creerCompetition(DtoCategorie $competition)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				type_competition=:typeCompetition,
				categorie=:categorie,
				division=:division,
				saison=:saison,
				equipe=:equipe,
				actif=:actif,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$libelle=(htmlspecialchars(strip_tags($competition->getLibelle())));
		$typeCompetition = $competition->getTypeCompetition();
		$categorie = $competition->getCategorie();
		$division = $competition->getDivision();
		$saison = $competition->getSaison();
		$equipe = $competition->getEquipe();
		$actif = $competition->getActif();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":typeCompetition", $typeCompetition);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":division", $division);
		$stmt->bindParam(":saison", $saison);
		$stmt->bindParam(":equipe", $equipe);
		$stmt->bindParam(":actif", $actif);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerCompetition($id)
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

	public function modifierCompetition(DtoCategorie $competition)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				libelle=:libelle,
				type_competition=:typeCompetition,
				categorie=:categorie,
				division=:division,
				saison=:saison,
				equipe=:equipe,
				actif=:actif,
				auteur_maj=:userLogin,
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$competition->getId();
		$libelle=(htmlspecialchars(strip_tags($competition->getLibelle())));
		$typeCompetition = $competition->getTypeCompetition();
		$categorie = $competition->getCategorie();
		$division = $competition->getDivision();
		$saison = $competition->getSaison();
		$equipe = $competition->getEquipe();
		$actif = $competition->getActif();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":libelle", $libelle);
		$stmt->bindParam(":typeCompetition", $typeCompetition);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":division", $division);
		$stmt->bindParam(":saison", $saison);
		$stmt->bindParam(":equipe", $equipe);
		$stmt->bindParam(":actif", $actif);
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