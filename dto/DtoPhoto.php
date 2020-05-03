<?php
class DtoPhoto
{
	// ATTRIBUTS PRIVES
	public $_article;
	public $_photo;

	// GETTER
	public function getArticle() {
		return $this->_article;
	}

	public function getPhoto() {
		return $this->_photo;
	}

	//SETTER
	public function setArticle($article) {
		$this->_article = $article;
	}

	public function setPhoto($photo) {
		if (is_string($photo)) {
			$this->_photo = $photo;
		}
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