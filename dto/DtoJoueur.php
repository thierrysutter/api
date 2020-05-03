<?php
class DtoJoueur
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_nom;
	public $_prenom;
	public $_age;
	public $_dateNaissance;
	public $_categorie;
	public $_libelleCategorie;
	public $_fonction;
	public $_libelleFonction;
	public $_poste;
	public $_libellePoste;
	public $_taille;
	public $_poids;
	public $_meilleurPied;
	public $_numeroLicence;
	public $_email;
	public $_photo;
	public $_video;
	
	public $_parcours;
	
	//SETTER
	public function setId($id) {
		$this->_id = $id;
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
	
	public function setAge($age) {
		$age = (int) $age;
		$this->_age = $age;
	}
	
	public function setDateNaissance($dateNaissance) {
		$this->_dateNaissance = $dateNaissance;
	}
	
	public function setCategorie($categorie) {
		$categorie = (int) $categorie;
		$this->_categorie = $categorie;
	}
	
	public function setLibelleCategorie($libelleCategorie) {
		if (is_string($libelleCategorie)) {
			$this->_libelleCategorie = $libelleCategorie;
		}
	}
	
	public function setFonction($fonction) {
		$fonction = (int) $fonction;
		$this->_fonction = $fonction;
	}
	
	public function setLibelleFonction($libelleFonction) {
		if (is_string($libelleFonction)) {
			$this->_libelleFonction = $libelleFonction;
		}
	}
	
	public function setPoste($poste) {
		$poste = (int) $poste;
		$this->_poste = $poste;
	}
	
	public function setLibellePoste($libellePoste) {
		if (is_string($libellePoste)) {
			$this->_libellePoste = $libellePoste;
		}
	}
	
	public function setTaille($taille) {
		$this->_taille = $taille;
	}
	
	public function setPoids($poids) {
		$this->_poids = $poids;
	}
	
	public function setMeilleurPied($meilleurPied) {
		$this->_meilleurPied = $meilleurPied;
	}
	
	public function setNumeroLicence($numeroLicence) {
		$this->_numeroLicence = $numeroLicence;
	}
	
	public function setEmail($email) {
		$this->_email = $email;
	}
	
	public function setPhoto($photo) {
		$this->_photo = $photo;
	}
	
	public function setVideo($video) {
		$this->_video = $video;
	}

	public function setParcours($parcours) {
		$this->_parcours = $parcours;
	}
	
	// GETTER
	public function getId() {
		return $this->_id;
	}
	
	public function getNom() {
		return $this->_nom;
	}
	
	public function getPrenom() {
		return $this->_prenom;
	}
	
	public function getAge() {
		return $this->_age;
	}
	
	public function getDateNaissance() {
		return $this->_dateNaissance;
	}
	
	public function getCategorie() {
		return $this->_categorie;
	}
	
	public function getLibelleCategorie() {
		return $this->_libelleCategorie;
	}
	
	public function getFonction() {
		return $this->_fonction;
	}
	
	public function getLibelleFonction() {
		return $this->_libelleFonction;
	}
	
	public function getPoste() {
		return $this->_poste;
	}
	
	public function getLibellePoste() {
		return $this->_libellePoste;
	}
	
	public function getTaille() {
		return $this->_taille;
	}
	
	public function getPoids() {
		return $this->_poids;
	}
	
	public function getMeilleurPied() {
		return $this->_meilleurPied;
	}
	
	public function getNumeroLicence() {
		return $this->_numeroLicence;
	}
	
	public function getEmail() {
		return $this->_email;
	}
	
	public function getPhoto() {
		return $this->_photo;
		//return strtoupper($this->_nom." ".$this->_prenom).".bmp";
	}
	
	public function getVideo() {
		return $this->_video;
	}
	
	public function getParcours() {
		return $this->_parcours;
	}
	
	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}
  
	// HYDRATATION
	// Un tableau de données doit être passé à la fonction (d'où le préfixe « array »).
	public function hydrate(array $donnees) {
		//echo "Entrée dans l'hydratation de la classe Joueur<br>";
		foreach ($donnees as $key => $value) {
			// On r�cup�re le nom du setter correspondant � l'attribut.
			$method = 'set'.ucfirst($key);
			// Si le setter correspondant existe.
			if (method_exists($this, $method))
			{
			  // On appelle le setter.
			  $this->$method($value);
			}
		}
	}
}
?>