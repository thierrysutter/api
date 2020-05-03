<?php
class DtoArticle
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_titre;
	public $_texte;
	public $_photo;
	public $_video;
	public $_lien;
	public $_statut;
	public $_dateParution;
	public $_auteur;

	public $_listePhotos = array();


	// GETTER
	public function getId() {
		return $this->_id;
	}

	public function getTitre() {
		return $this->_titre;
	}

	public function getTexte() {
		return $this->_texte;
	}

	public function getPhoto() {
		return $this->_photo;
	}

	public function getVideo() {
		return $this->_video;
	}

	public function getLien() {
		return $this->_lien;
	}

	public function getStatut() {
		return $this->_statut;
	}

	public function getDateParution() {
		return $this->_dateParution;
	}

	public function getAuteur() {
		return $this->_auteur;
	}

	public function getListePhotos() {
		return $this->_listePhotos;
	}

	//SETTER
	public function setId($id) {
		$this->_id = $id;
	}

	public function setTitre($titre) {
		if (is_string($titre)) {
			$this->_titre = $titre;
		}
	}

	public function setTexte($texte) {
		if (is_string($texte)) {
			$this->_texte = $texte;
		}
	}

	public function setPhoto($photo) {
		if (is_string($photo)) {
			$this->_photo = $photo;
		}
	}

	public function setVideo($video) {
		if (is_string($video)) {
			$this->_video = $video;
		}
	}

	public function setLien($lien) {
		if (is_string($lien)) {
			$this->_lien = $lien;
		}
	}

	public function setStatut($statut) {
		$this->_statut = $statut;
	}

	public function setDateParution($dateParution) {
		//if (is_date($dateParution)) {
			$this->_dateParution = $dateParution;
		//}
	}

	public function setAuteur($auteur) {
		if (is_string($auteur)) {
			$this->_auteur = $auteur;
		}
	}

	public function setListePhotos($listePhotos) {
		$this->_listePhotos = $listePhotos;
	}

	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}

	public function ajouterPhoto($photo)
	{
		array_push($this->_listePhotos, $photo);
	}

	// HYDRATATION
	// Un tableau de donn�es doit �tre pass� � la fonction (d'o� le pr�fixe � array �).
	public function hydrate(array $donnees) {
		//echo "Entr�e dans l'hydratation de la classe Sponsor<br>";
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