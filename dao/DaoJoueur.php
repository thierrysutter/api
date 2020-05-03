<?php
include_once '../dto/DtoJoueur.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoDivision
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "membre";
    
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
		$joueurs = array();
		try
		{
			// select all query
			$query = "SELECT membre.id, membre.nom, membre.prenom, membre.age, membre.date_naissance as dateNaissance, categorie.libelle as libelleCategorie, poste.libelle as libellePoste, membre.numero_licence as numeroLicence, membre.taille, membre.poids, membre.meilleur_pied as meilleurPied, membre.photo 
            FROM " . $this->bdd_table . " membre left outer join poste on (poste.id=membre.poste) left outer join categorie on (categorie.id=membre.categorie)
            WHERE fonction=12
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$joueurs[] = new DtoJoueur($donnees);		
			}
		
			return $joueurs;
		}
		catch(PDOException $ex)
		{
			return $joueurs;
		}
	}
	
	public function get($id)
	{
		$joueurs = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT membre.id, membre.nom, membre.prenom, membre.age, membre.date_naissance as dateNaissance, categorie.libelle as libelleCategorie, poste.libelle as libellePoste, membre.numero_licence as numeroLicence, membre.taille, membre.poids, membre.meilleur_pied as meilleurPied, membre.photo 
            	FROM " . $this->bdd_table . " membre left outer join poste on (poste.id=membre.poste) left outer join categorie on (categorie.id=membre.categorie)
            	WHERE membre.id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$joueurs[] = new DtoJoueur($donnees);		
				}
				
				return $joueurs;
			}
			else
			{
				return $joueurs;
			}
		}
		catch(PDOException $ex)
		{
			return $joueurs;
		}
    }
    
    public function creerJoueur(DtoJoueur $joueur)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				nom=:nom,
				prenom=:prenom,
				date_naissance=:dateNaissance,
				fonction=12,
				categorie=:categorie,
				poste=:poste,
				taille=:taille,
				poids=:poids,
				meilleur_pied=:meilleurPied,
				numero_licence=:numeroLicence,
				email=:email,
				photo=:photo,
                auteur_maj='',
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$nom=(htmlspecialchars(strip_tags($joueur->getNom())));
		$prenom=(htmlspecialchars(strip_tags($joueur->getPrenom())));
		$dateNaissance=$joueur->getDateNaissance();
		$categorie=$joueur->getCategorie();
		$poste=$joueur->getPoste();
		$taille=$joueur->getTaille();
		$poids=$joueur->getPoids();
		$meilleurPied=$joueur->getMeilleurPied();
		$numeroLicence=$joueur->getNumeroLicence();
		$email=$joueur->getEmail();
		$photo=$joueur->getPhoto();
		
		// bind values
		$stmt->bindParam(":nom", $nom);
		$stmt->bindParam(":prenom", $prenom);
		$stmt->bindParam(":dateNaissance", $dateNaissance);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":poste", $poste);
		$stmt->bindParam(":taille", $taille);
		$stmt->bindParam(":poids", $poids);
		$stmt->bindParam(":meilleurPied", $meilleurPied);
		$stmt->bindParam(":numeroLicence", $numeroLicence);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":photo", $photo);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerJoueur($id)
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

	public function modifierJoueur(DtoJoueur $joueur)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				nom=:nom,
				prenom=:prenom,
				date_naissance=:dateNaissance,
				fonction=12,
				categorie=:categorie,
				poste=:poste,
				taille=:taille,
				poids=:poids,
				meilleur_pied=:meilleurPied,
				numero_licence=:numeroLicence,
				email=:email,
				photo=:photo,
				auteur_maj=:userLogin, 
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$joueur->getId();
		$nom=(htmlspecialchars(strip_tags($joueur->getNom())));
		$prenom=(htmlspecialchars(strip_tags($joueur->getPrenom())));
		$dateNaissance=$joueur->getDateNaissance();
		$categorie=$joueur->getCategorie();
		$poste=$joueur->getPoste();
		$taille=$joueur->getTaille();
		$poids=$joueur->getPoids();
		$meilleurPied=$joueur->getMeilleurPied();
		$numeroLicence=$joueur->getNumeroLicence();
		$email=$joueur->getEmail();
		$photo=$joueur->getPhoto();
		$userLogin='';
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":nom", $nom);
		$stmt->bindParam(":prenom", $prenom);
		$stmt->bindParam(":dateNaissance", $dateNaissance);
		$stmt->bindParam(":categorie", $categorie);
		$stmt->bindParam(":poste", $poste);
		$stmt->bindParam(":taille", $taille);
		$stmt->bindParam(":poids", $poids);
		$stmt->bindParam(":meilleurPied", $meilleurPied);
		$stmt->bindParam(":numeroLicence", $numeroLicence);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":photo", $photo);
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