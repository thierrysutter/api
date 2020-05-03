<?php
class DtoEquipe
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_categorie;
	public $_libelleCategorie;
	public $_libelle;
	public $_entraineur;
	public $_adjoint;
	public $_delegue;
	public $_lienClassement;
	
	// GETTER
	public function getId() {
		return $this->_id;
	}
	
	public function getCategorie() {
		return $this->_categorie;
	}
	
	public function getLibelleCategorie() {
		return $this->_libelleCategorie;
	}
	
	public function getLibelle() {
		return $this->_libelle;
	}
	
	public function getEntraineur() {
		return $this->_entraineur;
	}
	
	public function getAdjoint() {
		return $this->_adjoint;
	}
	
	public function getDelegue() {
		return $this->_delegue;
	}
	
	public function getLienClassement() {
		return $this->_lienClassement;
	}
	
	// SETTER
	public function setId($id) {
		$this->_id = $id;
	}
	
	public function setCategorie($categorie) {
		$this->_categorie = $categorie;
	}
	
	public function setLibelleCategorie($libelleCategorie) {
		$this->_libelleCategorie = $libelleCategorie;
	}
	
	public function setLibelle($libelle) {
		if (is_string($libelle)) {
			$this->_libelle = $libelle;
		}
	}
	
	public function setEntraineur($entraineur) {
		$this->_entraineur = $entraineur;
	}
	
	public function setAdjoint($adjoint) {
		$this->_adjoint = $adjoint;
	}
	
	public function setDelegue($delegue) {
		$this->_delegue = $delegue;
	}
	
	public function setLienClassement($lienClassement) {
		$this->_lienClassement = $lienClassement;
	}
	
	// CONSTRUCTEUR
	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}
	
	// HYDRATE
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