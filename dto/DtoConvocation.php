<?php
class DtoConvocation
{
	// ATTRIBUTS PRIVES
	public $_id;
	public $_competition;
	public $_libelleCompetition;
	public $_categorie;
	public $_libelleCategorie;
	public $_jour;
	public $_equipe;
	public $_equipeDom;
	public $_equipeExt;
	public $_scoreDom;
	public $_scoreExt;
	public $_statut;

	public $_compteRendu;

	public $_listeJoueurs = array();
	public $_rencontre; /* type BoRencontre */

	// GETTER
	public function getId() {
		return $this->_id;
	}

	public function getCompetition() {
		return $this->_competition;
	}

	public function getLibelleCompetition() {
		return $this->_libelleCompetition;
	}

	public function getCategorie() {
		return $this->_categorie;
	}

	public function getLibelleCategorie() {
		return $this->_libelleCategorie;
	}

	public function getJour() {
		return $this->_jour;
	}

	public function getEquipe() {
		return $this->_equipe;
	}

	public function getEquipeDom() {
		return $this->_equipeDom;
	}

	public function getEquipeExt() {
		return $this->_equipeExt;
	}

	public function getScoreDom() {
		return $this->_scoreDom;
	}

	public function getScoreExt() {
		return $this->_scoreExt;
	}

	public function getStatut() {
		return $this->_statut;
	}

	public function getCompteRendu() {
		return $this->_compteRendu;
	}

	public function getListeJoueurs() {
		return $this->_listeJoueurs;
	}

	public function getRencontre() {
		return $this->_rencontre;
	}

	//SETTER
	public function setId($id) {
		$this->_id = $id;
	}

	public function setCompetition($competition) {
		$this->_competition = $competition;
	}

	public function setLibelleCompetition($libelleCompetition) {
		$this->_libelleCompetition = $libelleCompetition;
	}

	public function setCategorie($categorie) {
		$this->_categorie = $categorie;
	}

	public function setLibelleCategorie($libelleCategorie) {
		$this->_libelleCategorie = $libelleCategorie;
	}

	public function setJour($jour) {
		$this->_jour = $jour;
	}

	public function setEquipe($equipe) {
		$this->_equipe = $equipe;
	}

	public function setEquipeDom($equipeDom) {
		if (is_string($equipeDom)) {
			$this->_equipeDom = $equipeDom;
		}
	}

	public function setEquipeExt($equipeExt) {
		if (is_string($equipeExt)) {
			$this->_equipeExt = $equipeExt;
		}
	}

	public function setScoreDom($scoreDom) {
		$this->_scoreDom = $scoreDom;
	}

	public function setScoreExt($scoreExt) {
		$this->_scoreExt = $scoreExt;
	}

	public function setStatut($statut) {
		$this->_statut = $statut;
	}

	public function setCompteRendu($compteRendu) {
		$this->_compteRendu = $compteRendu;
	}

	public function setListeJoueurs($listeJoueurs) {
		$this->_listeJoueurs = $listeJoueurs;
	}

	public function setRencontre($rencontre) {
		$this->_rencontre = $rencontre;
	}

	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}

	public function ajouterJoueur($joueur)
	{
		array_push($this->_listeJoueurs, $joueur);
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