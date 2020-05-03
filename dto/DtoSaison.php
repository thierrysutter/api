<?php
class DtoSaison
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_libelle;
	public $_etat;
	public $_auteurMaj;
	
	// GETTER
	public function getId() {
		return $this->_id;
	}

	public function getLibelle() {
		return $this->_libelle;
	}

	public function getEtat() {
		return $this->_etat;
	}

	public function getAuteurMaj() {
		return $this->_auteurMaj;
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

	public function setEtat($etat) {
		$this->_etat = $etat;
	}

	public function setAuteurMaj($auteurMaj) {
		$this->_auteurMaj = $auteurMaj;
	}
	
	public function __construct(array $donnees) {
		$this->hydrate($donnees);
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