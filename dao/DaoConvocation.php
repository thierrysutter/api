<?php
include_once '../dto/DtoConvocation.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoConvocation
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "convocation";
    
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
		$convocations = array();
		try
		{
			// select all query
			$query = "SELECT rencontre.id, rencontre.competition, competition.libelle as libelleCompetition, categorie.libelle as libelleCategorie, rencontre.jour, rencontre.equipe_dom as equipeDom, rencontre.equipe_ext as equipeExt, rencontre.score_dom as scoreDom, rencontre.score_ext as scoreExt, rencontre.statut, rencontre.heure_rdv as heureRDV, rencontre.lieu_rdv as lieuRDV, rencontre.commentaire_rdv as commentaireRDV, rencontre.heure_match as heureMatch 
			FROM " . $this->bdd_table . " p, rencontre, competition, categorie
			WHERE convocation.rencontre=rencontre.id 
			AND rencontre.competition=competition.id 
			AND competition.categorie=categorie.id 
			AND rencontre.jour >  NOW() - INTERVAL 10 DAY 
  			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$convocations[] = new DtoConvocation($donnees);		
			}
		
			return $convocations;
		}
		catch(PDOException $ex)
		{
			return $convocations;
		}
	}
	
	public function get($id)
	{
		$convocations = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT rencontre.id, rencontre.competition, competition.libelle as libelleCompetition, categorie.libelle as libelleCategorie, rencontre.jour, rencontre.equipe_dom as equipeDom, rencontre.equipe_ext as equipeExt, rencontre.score_dom as scoreDom, rencontre.score_ext as scoreExt, rencontre.statut, rencontre.heure_rdv as heureRDV, rencontre.lieu_rdv as lieuRDV, rencontre.commentaire_rdv as commentaireRDV, rencontre.heure_match as heureMatch 
				FROM " . $this->bdd_table . " p, rencontre, competition, categorie
				WHERE convocation.rencontre=rencontre.id 
				AND rencontre.competition=competition.id 
				AND competition.categorie=categorie.id 
				AND rencontre.jour >  NOW() - INTERVAL 10 DAY 
				AND rencontre.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$convocations[] = new DtoConvocation($donnees);		
				}
				
				return $convocations;
			}
			else
			{
				return $convocations;
			}
		}
		catch(PDOException $ex)
		{
			return $convocations;
		}
    }
    
    public function creerConvocation(DtoConvocation $convocation)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				rencontre=:rencontre,
				joueur=:joueur,
				auteur_maj=:userLogin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$rencontre=$convocation->getRencontre();
		$joueur=$convocation->getJoueur();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":rencontre", $rencontre);
		$stmt->bindParam(":joueur", $joueur);
		$stmt->bindParam(":userLogin", $userLogin);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerConvocation($id)
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

	public function modifierConvocation(DtoCategorie $convocation)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
			rencontre=:rencontre,
			joueur=:joueur,
			auteur_maj=:userLogin,
			derniere_maj=now()
			WHERE rencontre = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$rencontre=$convocation->getRencontre();
		$joueur=$convocation->getJoueur();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":rencontre", $rencontre);
		$stmt->bindParam(":joueur", $joueur);
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