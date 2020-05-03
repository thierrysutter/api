<?php
class DtoCompteRendu
{
	// ATTRIBUTS PRIVES
	
	public $_rencontre;
	public $_texte;
	public $_auteur;
	public $_derniereMaj;
	
	// GETTER
	public function getRencontre() {
		return $this->_rencontre;
	}
	
	public function getTexte() {
		return $this->_texte;
	}
	
	public function getAuteur() {
		return $this->_auteur;
	}
	
	public function getDerniereMaj() {
		return $this->_derniereMaj;
	}
	
	//SETTER
	public function setRencontre($rencontre) {
		$this->_rencontre = $rencontre;
	}
	
	public function setTexte($texte) {
		if (is_string($texte)) {
			$this->_texte = $texte;
		}
	}
	
	public function setAuteur($auteur) {
		if (is_string($auteur)) {
			$this->_auteur = $auteur;
		}
	}

	public function setDerniereMaj($derniereMaj) {
		//if (is_date($dateParution)) {
			$this->_derniereMaj = $derniereMaj;
		//}
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