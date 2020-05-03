<?php
class DtoCompetition
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_libelle;
	public $_typeCompetition;
	public $_categorie;
	public $_division;
	public $_saison;
	public $_equipe;

	public $_libelleCategorie;
	public $_libelleDivision;
	public $_libelleEquipe;
	public $_libelleSaison;
	public $_libelleTypeCompetition;

	// GETTER
	public function getId() {
		return $this->_id;
	}

	public function getLibelle() {
		return $this->_libelle;
	}

	public function getTypeCompetition() {
		return $this->_typeCompetition;
	}

	public function getCategorie() {
		return $this->_categorie;
	}

	public function getDivision() {
		return $this->_division;
	}

	public function getSaison() {
		return $this->_saison;
	}

	public function getEquipe() {
		return $this->_equipe;
	}

	public function getLibelleCategorie() {
		return $this->_libelleCategorie;
	}

	public function getLibelleDivision() {
		return $this->_libelleDivision;
	}

	public function getLibelleEquipe() {
		return $this->_libelleEquipe;
	}

	public function getLibelleSaison() {
		return $this->_libelleSaison;
	}

	public function getLibelleTypeCompetition() {
		return $this->_libelleTypecompetition;
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

	public function setTypeCompetition($typeCompetition) {
		if (is_string($typeCompetition)) {
			$this->_typeCompetition = $typeCompetition;
		}
	}

	public function setCategorie($categorie) {
		$this->_categorie = $categorie;
	}

	public function setDivision($division) {
		$this->_division = $division;
	}

	public function setSaison($saison) {
		$this->_saison = $saison;
	}

	public function setEquipe($equipe) {
		$this->_equipe = $equipe;
	}

	public function setLibelleCategorie($libelleCategorie) {
		if (is_string($libelleCategorie)) {
			$this->_libelleCategorie = $libelleCategorie;
		}
	}

	public function setLibelleDivision($libelleDivision) {
		if (is_string($libelleDivision)) {
			$this->_libelleDivision = $libelleDivision;
		}
	}

	public function setLibelleEquipe($libelleEquipe) {
		if (is_string($libelleEquipe)) {
			$this->_libelleEquipe = $libelleEquipe;
		}
	}

	public function setLibelleSaison($libelleSaison) {
			$this->_libelleSaison = $libelleSaison;
	}

	public function setLibelleTypeCompetition($libelleTypeCompetition) {
		$this->_libelleTypeCompetition = $libelleTypeCompetition;
	}

	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}

	// HYDRATATION
	// Un tableau de donn�es doit �tre pass� � la fonction (d'o� le pr�fixe � array �).
	public function hydrate(array $donnees) {
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