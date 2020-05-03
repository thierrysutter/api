<?php
class DtoCategorie
{
    // ATTRIBUTS PRIVES
	public $_id;
	public $_libelle;
	public $_anneeDebut;
	public $_anneeFin;
	
	public $_entrainements;

	// GETTER
	public function getId() {
		return $this->_id;
	}

	public function getLibelle() {
		return $this->_libelle;
	}

	public function getAnneeDebut() {
		return $this->_anneeDebut;
	}

	public function getAnneeFin() {
		return $this->_anneeFin;
	}
	
	public function getEntrainements() {
		return $this->_entrainements;
	}

	//SETTER
	public function setId($id) {
		$this->_id = $id;
	}

	public function setLibelle($libelle) {
		if (is_string($libelle)) {
			$this->_libelle = $libelle;
		}
	}

	public function setAnneeDebut($anneeDebut) {
		if (is_string($anneeDebut)) {
			$this->_anneeDebut = $anneeDebut;
		}
	}

	public function setAnneeFin($anneeFin) {
		if (is_string($anneeFin)) {
			$this->_anneeFin = $anneeFin;
		}
	}

	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}
	
	public function setEntrainements($entrainements) {
		$this->_entrainements = $entrainements;
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