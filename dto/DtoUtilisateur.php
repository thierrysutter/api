<?php
class DtoUtilisateur
{
	// ATTRIBUTS PRIVES
	public $_login;
	public $_actif;
	public $_nbEchec;
	public $_pwdUsageUnique;
	public $_dateExpiration;
	public $_email;
	public $_password;
	public $_nom;
	public $_prenom;
	public $_sexe;
	public $_dateNaissance;
	public $_adresse;
	public $_codePostal;
	public $_ville;
	public $_pays;
	public $_telFixe;
	public $_telPortable;
	public $_photo;
	public $_superAdmin;	
	public $_categorie;
	
	public $_dateDerniereConnexion;
	
	//SETTER
	public function setLogin($login) {
		if (is_string($login)) {
			$this->_login = $login;
		}
	}

	public function setPassword($password) {
		if (is_string($password)) {
			$this->_password = $password;
		}
	}
	
	public function setActif($actif) {
		$actif = (int) $actif;
		$this->_actif = $actif;
	}
	
	public function setNbEchec($nbEchec) {
		$nbEchec = (int) $nbEchec;
		$this->_nbEchec = $nbEchec;
	}
	
	public function setPwdUsageUnique($pwdUsageUnique) {
		$pwdUsageUnique = (int) $pwdUsageUnique;
		$this->_pwdUsageUnique = $pwdUsageUnique;
	}
	
	public function setDateExpiration($dateExpiration) {
		$this->_dateExpiration = $dateExpiration;
	}
	
	public function setEmail($email) {
		if (is_string($email)) {
			$this->_email = $email;
		}
	}
	
	public function setNom($nom) {
		if (is_string($nom)) {
			$this->_nom = $nom;
		}
	}
	
	public function setPrenom($prenom) {
		if (is_string($prenom)) {
			$this->_prenom = $prenom;
		}
	}
	
	public function setSexe($sexe) {
		if (is_string($sexe)) {
			$this->_sexe = $sexe;
		}
	}
	
	public function setDateNaissance($dateNaissance) {
		$this->_dateNaissance = $dateNaissance;
	}
	
	public function setAdresse($adresse) {
		if (is_string($adresse)) {
			$this->_adresse = $adresse;
		}
	}
	
	public function setCodePostal($codePostal) {
		if (is_string($codePostal)) {
			$this->_codePostal = $codePostal;
		}
	}
	
	public function setVille($ville) {
		if (is_string($ville)) {
			$this->_ville = $ville;
		}
	}
	
	public function setPays($pays) {
		if (is_string($pays)) {
			$this->_pays = $pays;
		}
	}
	
	public function setTelFixe($telFixe) {
		if (is_string($telFixe)) {
			$this->_telFixe = $telFixe;
		}
	}
	
	public function setTelPortable($telPortable) {
		if (is_string($telPortable)) {
			$this->_telPortable = $telPortable;
		}
	}
	
	public function setPhoto($photo) {
		if (is_string($photo)) {
			$this->_photo = $photo;
		}
	}
	
	public function setSuperAdmin($superAdmin) {
		$this->_superAdmin = $superAdmin;
	}
	
	public function setCategorie($categorie) {
		$this->_categorie = $categorie;
	}
	
	public function setDateDerniereConnexion($dateDerniereConnexion) {
		$this->_dateDerniereConnexion = $dateDerniereConnexion;
	}
	
	// GETTER
	public function getLogin() {
		return $this->_login;
	}

	public function getPassword() {
		return $this->_password;
	}
	
	public function getActif() {
		return $this->_actif;
	}
	
	public function getNbEchec() {
		return $this->_nbEchec;
	}
	
	public function getPwdUsageUnique() {
		return $this->_pwdUsageUnique;
	}
	
	public function getDateExpiration() {
		return $this->_dateExpiration;
	}
	
	public function getEmail() {
		return $this->_email;
	}
	
	public function getNom() {
		return $this->_nom;
	}
	
	public function getPrenom() {
		return $this->_prenom;
	}
	
	public function getSexe() {
		return $this->_sexe;
	}
	
	public function getDateNaissance() {
		return $this->_dateNaissance;
	}
	
	public function getAdresse() {
		return $this->_adresse;
	}
	
	public function getCodePostal() {
		return $this->_codePostal;
	}
	
	public function getVille() {
		return $this->_ville;
	}
	
	public function getPays() {
		return $this->_pays;
	}
	
	public function getTelFixe() {
		return $this->_telFixe;
	}
	
	public function getTelPortable() {
		return $this->_telPortable;
	}
	
	public function getPhoto() {
		return $this->_photo;
	}
	
	public function getSuperAdmin() {
		return $this->_superAdmin;
	}
	
	public function getCategorie() {
		return $this->_categorie;
	}
	
	public function getDateDerniereConnexion() {
		return $this->_dateDerniereConnexion;
	}
	
	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}
  
	// HYDRATATION
	// Un tableau de donn�es doit �tre pass� � la fonction (d'o� le pr�fixe � array �).
	public function hydrate(array $donnees)
	{
		//echo "Entrée dans l'hydratation de la classe Utilisateur<br>";
		foreach ($donnees as $key => $value) {
			// On r�cup�re le nom du setter correspondant � l'attribut.
			$method = 'set'.ucfirst($key);
			$getMethod = 'get'.ucfirst($key);
			//echo $method."<br>";
			// Si le setter correspondant existe.
			if (method_exists($this, $method))
			{
				// echo $method." existe <br>";
			  // On appelle le setter.
			  $this->$method($value);
			}
		}
	}
	
	public function isVerrouille() {
		return (bool) ($this->_nbEchec > 3);
	}
	
	public function isExpire() {
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);
		$expire_dt = new DateTime($this->_dateExpiration);
		return (bool) ($expire_dt < $today_dt);
	}
	
	public function isPwdUsageUnique() {
		return (bool) ($this->getPwdUsageUnique() == 1);
	}
	
	public function getStatut() {
		if ($this->getActif() == 0) {
			return "Supprim�";
		} else if ($this->isVerrouille()) {
			return "Verrouill�";
		} else if ($this->isExpire()) {
			return "Expir�";
		} else {
			return "OK";
		}
	}
	
	public function isSuperAdmin() {
		return (bool) ($this->getSuperAdmin() == 1);
	}
}
?>