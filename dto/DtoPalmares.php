<?php
class DtoPalmares
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_annee;
	public $_texte;
		
	//SETTER
	public function setId($id) {
		$this->_id = $id;
	}
	
	public function setAnnee($annee) {
		if (is_string($annee)) {
			$this->_annee = $annee;
		}
	}
	
	public function setTexte($texte) {
		if (is_string($texte)) {
			$this->_texte = $texte;
		}
	}
	
	// GETTER
	public function getId() {
		return $this->_id;
	}
	
	public function getAnnee() {
		return $this->_annee;
	}
	
	public function getTexte() {
		return $this->_texte;
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