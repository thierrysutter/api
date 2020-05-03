<?php
include_once '../dto/DtoUtilisateur.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoUtilisateur
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "utilisateur";
    
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
		$utilisateurs = array();
		try
		{
			// select all query
			$query = "SELECT login, actif, nb_echec as nbEchec, date_expiration as dateExpiration, email, nom, prenom, sexe, date_naissance as dateNaissance, adresse, code_postal as codePostal, ville, pays, tel_fixe as telFixe, tel_portable as telPortable, photo, super_admin as superAdmin, categorie, derniere_connexion as dateDerniereConnexion 
			FROM " . $this->bdd_table . " p
			ORDER BY login";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$utilisateurs[] = new DtoUtilisateur($donnees);		
			}
		
			return $utilisateurs;
		}
		catch(PDOException $ex)
		{
			return $utilisateurs;
		}
	}
	
	public function get($login)
	{
		$utilisateurs = array();
		try
		{			
			if (is_string($login)) {
		
				// select all query
				$query = "SELECT login, actif, nb_echec as nbEchec, date_expiration as dateExpiration, email, nom, prenom, sexe, date_naissance as dateNaissance, adresse, code_postal as codePostal, ville, pays, tel_fixe as telFixe, tel_portable as telPortable, photo, super_admin as superAdmin, categorie, derniere_connexion as dateDerniereConnexion 
				FROM " . $this->bdd_table . " p
				WHERE login like '%" . $login . "%'";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$utilisateurs[] = new DtoUtilisateur($donnees);		
				}
				
				return $utilisateurs;
			}
			else
			{
				return $utilisateurs;
			}
		}
		catch(PDOException $ex)
		{
			return $utilisateurs;
		}
	}

	public function add($login, $password, $email)
	{
		$created = date_create(date('Y-m-d H:i:s'));
    	date_add($created, date_interval_create_from_date_string('1 year'));
	
		// Pr�paration de la requ�te d'insertion.
		$q = $this->connexion->query("INSERT INTO utilisateur (LOGIN, PASSWORD, ACTIF, NB_ECHEC, PWD_USAGE_UNIQUE, DATE_EXPIRATION, EMAIL, DATE_MAJ) VALUES ('".$login."','".$password."','1','0','1','".$created."','".$email."',now())");
	}
	
	public function ajouterUtilisateur($login, $password, $email, $nom, $prenom, $dateNaissance, $adresse, $codePostal, $ville, $telFixe, $telPortable, $superAdmin, $photo)
	{
		$created = date_create(date('Y-m-d H:i:s'));
    	date_add($created, date_interval_create_from_date_string('1 year'));
	
		// Pr�paration de la requ�te d'insertion.
		$sql = "INSERT INTO utilisateur (LOGIN, PASSWORD, ACTIF, NB_ECHEC, PWD_USAGE_UNIQUE, DATE_EXPIRATION, EMAIL, NOM, PRENOM, DATE_NAISSANCE, ADRESSE, CODE_POSTAL, VILLE, TEL_FIXE, TEL_PORTABLE, PHOTO, SUPER_ADMIN, DERNIERE_MAJ) ";
		$sql = $sql."VALUES ('".$login."', '".$password."', '1', '0', '1', '".$created."', '".$email."', '".strtoupper($nom)."', '".strtoupper($prenom)."', '".$dateNaissance."', '".strtoupper($adresse)."', '".strtoupper($codePostal)."', '".strtoupper($ville)."', '".$telFixe."', '".$telPortable."', '".$photo."', '".$superAdmin."', now())";
		$q = $this->connexion->query($sql);
	}
	
	public function creerUtilisateur(DtoUtilisateur $utilisateur)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				login=:login,
				password=:password,
				actif=:actif,
				nb_echec=:nb_echec,
				pwd_usage_unique=:pwd_usage_unique,
				date_expiration=:date_expiration,
				email=:email,
				nom=:nom,
				prenom=:prenom, 
				date_naissance=:date_naissance, 
				adresse=:adresse, 
				code_postal=:code_postal, 
				ville=:ville, 
				tel_fixe=:tel_fixe, 
				tel_portable=:tel_portable, 
				photo=:photo, 
				super_admin=:super_admin,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($utilisateur->getLogin())));
		$password=(htmlspecialchars(strip_tags($utilisateur->getPassword())));
		$actif=1;
		$nbEchec=0;
		$pwdUsageUnique=0;
		$email=(htmlspecialchars(strip_tags($utilisateur->getEmail())));
		$nom=(htmlspecialchars(strip_tags($utilisateur->getNom())));
		$prenom=(htmlspecialchars(strip_tags($utilisateur->getPrenom())));
		$dateNaissance=$utilisateur->getDateNaissance();
		$adresse=$utilisateur->getAdresse();
		$codePostal=$utilisateur->getCodePostal();
		$ville=$utilisateur->getVille();
		$telFixe=$utilisateur->getTelFixe();
		$telPortable=$utilisateur->getTelPortable();
		$photo=$utilisateur->getPhoto();
		$superAdmin=$utilisateur->getSuperAdmin();

		// bind values
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":password", $password);
		$stmt->bindParam(":actif", $actif);
		$stmt->bindParam(":nb_echec", $nbEchec);
		$stmt->bindParam(":pwd_usage_unique", $pwdUsageUnique);
		$stmt->bindParam(":date_expiration", $expirationDate);		
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":nom", $nom);
		$stmt->bindParam(":prenom", $prenom);
		$stmt->bindParam(":date_naissance", $dateNaissance);
		$stmt->bindParam(":adresse", $adresse);
		$stmt->bindParam(":code_postal", $codePostal);
		$stmt->bindParam(":ville", $ville);
		$stmt->bindParam(":tel_fixe", $telFixe);
		$stmt->bindParam(":tel_portable", $telPortable);
		$stmt->bindParam(":photo", $photo);
		$stmt->bindParam(":super_admin", $superAdmin);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerUtilisateur($login)
	{
		//$this->connexion->exec('DELETE FROM utilisateur WHERE login = '.$utilisateur->getLogin());

		// delete query
		$query = "DELETE FROM " . $this->bdd_table . " WHERE login = ?";
 
		// prepare query
		$stmt = $this->connexion->prepare($query);
	 
		// sanitize
		$login=htmlspecialchars(strip_tags($login));

		// bind id of record to delete
		$stmt->bindParam(1, $login);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}

	public function modifierUtilisateur(DtoUtilisateur $utilisateur)
	{
		// // Pr�paration de la requ�te d'update.
		// $sql = "UPDATE utilisateur ";
		
		// $sql = $sql."SET email = '".$utilisateur->getEmail()."', ";
		
		// //$sql = $sql."login = '".$login."', ";
		
		// $sql = $sql."nom = '".strtoupper($utilisateur->getNom())."', ";
		
		// $sql = $sql."prenom = '".strtoupper($utilisateur->getPrenom())."', ";
		
		// $sql = $sql."date_naissance = '".$utilisateur->getDateNaissance()."', ";
		
		// $sql = $sql."adresse = '".strtoupper($utilisateur->getAdresse())."', ";
		
		// $sql = $sql."code_postal = '".strtoupper($utilisateur->getCodePostal())."', ";
		
		// $sql = $sql."ville = '".strtoupper($utilisateur->getVille())."', ";
		
		// $sql = $sql."tel_fixe = '".$utilisateur->getTelFixe()."', ";
		
		// $sql = $sql."tel_portable = '".$utilisateur->getTelPortable()."', ";
		
		// if ($utilisateur->getSuperAdmin() != null && $utilisateur->getSuperAdmin() != "") {
		// 	$sql = $sql."super_admin = '".$utilisateur->getSuperAdmin()."', ";
		// }
		
		// $sql = $sql."derniere_maj = now() ";
		// $sql = $sql."WHERE login='".$utilisateur->getLogin()."'";
  
		// $q = $this->connexion->query($sql);
		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				email=:email,
				nom=:nom,
				prenom=:prenom, 
				date_naissance=:date_naissance, 
				adresse=:adresse, 
				code_postal=:code_postal, 
				ville=:ville, 
				tel_fixe=:tel_fixe, 
				tel_portable=:tel_portable, 
				photo=:photo, 
				derniere_maj=now()
			WHERE login = :login";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($utilisateur->getLogin())));
		
		$email=(htmlspecialchars(strip_tags($utilisateur->getEmail())));
		$nom=(htmlspecialchars(strip_tags($utilisateur->getNom())));
		$prenom=(htmlspecialchars(strip_tags($utilisateur->getPrenom())));
		$dateNaissance=$utilisateur->getDateNaissance();
		//$dateNaissance=$dateNaissance->format('Y-m-d');
		$adresse=$utilisateur->getAdresse();
		$codePostal=$utilisateur->getCodePostal();
		$ville=$utilisateur->getVille();
		$telFixe=$utilisateur->getTelFixe();
		$telPortable=$utilisateur->getTelPortable();
		$photo=$utilisateur->getPhoto();
		
		// bind values
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":nom", $nom);
		$stmt->bindParam(":prenom", $prenom);
		$stmt->bindParam(":date_naissance", $dateNaissance);
		$stmt->bindParam(":adresse", $adresse);
		$stmt->bindParam(":code_postal", $codePostal);
		$stmt->bindParam(":ville", $ville);
		$stmt->bindParam(":tel_fixe", $telFixe);
		$stmt->bindParam(":tel_portable", $telPortable);
		$stmt->bindParam(":photo", $photo);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function search($keywords)
	{
		$utilisateurs = array();
		try
		{
			// select all query
			$query = "SELECT login, actif, nb_echec as nbEchec, date_expiration as dateExpiration, email, nom, prenom, sexe, date_naissance as dateNaissance, adresse, code_postal as codePostal, ville, pays, tel_fixe as telFixe, tel_portable as telPortable, photo, super_admin as superAdmin, categorie, derniere_connexion as dateDerniereConnexion 
			FROM " . $this->bdd_table . " p
			WHERE p.nom LIKE ? OR p.prenom LIKE ? OR p.email LIKE ? OR p.login LIKE ?
			ORDER BY login";

			// prepare query
			$stmt = $this->connexion->prepare($query);

			// sanitize
			$keywords=htmlspecialchars(strip_tags($keywords));
			$keywords = "%{$keywords}%";

			// bind id of record to delete
			$stmt->bindParam(1, $keywords);
			$stmt->bindParam(2, $keywords);
			$stmt->bindParam(3, $keywords);
			$stmt->bindParam(4, $keywords);
		
			$stmt->execute();
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$utilisateurs[] = new DtoUtilisateur($donnees);		
			}
		
			return $utilisateurs;
		}
		catch(PDOException $ex)
		{
			return $utilisateurs;
		}
	}

	public function getPaging()
	{
		$utilisateurs = array();
		$utilisateurs["records"]=array();
		$utilisateurs["paging"]=array();
		
		// utilities
		$utilities = new Utilities();

		// home page url
		$home_url="http://localhost/api/";
		
		// page given in URL parameter, default page is one
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		// set number of records per page
		$records_per_page = 5;
		
		// calculate for the query LIMIT clause
		$from_record_num = ($records_per_page * $page) - $records_per_page;

		try
		{
			// $from_record_num, $records_per_page

			// select query
			$query = "SELECT login, actif, nb_echec as nbEchec, date_expiration as dateExpiration, email, nom, prenom, sexe, date_naissance as dateNaissance, 
					adresse, code_postal as codePostal, ville, pays, tel_fixe as telFixe, tel_portable as telPortable, photo, super_admin as superAdmin, 
					categorie, derniere_connexion as dateDerniereConnexion 
				FROM " . $this->bdd_table . " p
				ORDER BY login 
				LIMIT ?, ?";

			// prepare query statement
			$stmt = $this->connexion->prepare( $query );

			// bind variable values
			$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
			$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

			// execute query
			$stmt->execute();

			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$utilisateurs["records"][] = new DtoUtilisateur($donnees);		
			}

			// paging
			$total_rows=$this->count();
			$page_url="{$home_url}services/UtilisateurService.php?paging=true&";
			$paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
			$utilisateurs["paging"]=$paging;
		
			return $utilisateurs;
		}
		catch(PDOException $ex)
		{
			return $utilisateurs;
		}
	}

	public function count()
	{
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->bdd_table . "";
 
		$stmt = $this->connexion->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function exists($login, $password)
	{
	  // Si le param�tre est un string, c'est qu'on a fourni un login.
	  if (is_string($login)) // On veut voir si tel utilisateur ayant pour login $login existe.
	  {
		// On ex�cute alors une requ�te COUNT() avec une clause WHERE, et on retourne un boolean.
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM " . $this->bdd_table . " WHERE login = '".$login."' and password = '".$password."'")->fetchColumn();
	  }
	  return false;
	}

	public function updatePassword($login, $password)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
	  	date_add($expirationDate, date_interval_create_from_date_string('1 year'));
	  	$expirationDate = $expirationDate->format('Y-m-d H:i:s');

	  	$query = "UPDATE
			" . $this->bdd_table . "
			SET
				password=:password,
				date_expiration=:date_expiration,
				nb_echec=:nb_echec,
	 			pwd_usage_unique=:pwd_usage_unique,
				derniere_maj=now()
			WHERE login = :login";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($login)));
		$password=(htmlspecialchars(strip_tags($password)));
		$nbEchec=0;
		$pwdUsageUnique=0;

		// bind values
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":password", $password);
		$stmt->bindParam(":date_expiration", $expirationDate);
		$stmt->bindParam(":nb_echec", $nbEchec);
		$stmt->bindParam(":pwd_usage_unique", $pwdUsageUnique);
	
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}
  
	public function updateEmail($login, $email)
	{
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				email=:email,
				derniere_maj=now()
			WHERE login = :login";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($login)));
		$email=(htmlspecialchars(strip_tags($email)));
		
		// bind values
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":email", $email);
			
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}
  
	public function updateActif($login, $actif)
	{
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				actif=:actif,
				derniere_maj=now()
			WHERE login = :login";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($login)));
		$actif=(htmlspecialchars(strip_tags($actif)));
		
		// bind values
		$stmt->bindParam(":login", $login);
		$stmt->bindParam(":actif", $actif);
			
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}
  
	public function updateDerniereConnexion($login)
	{
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				derniere_connexion=now()
			WHERE login = :login";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$login=(htmlspecialchars(strip_tags($login)));
		
		// bind values
		$stmt->bindParam(":login", $login);
			
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}
}
?>