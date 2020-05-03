<?php
include_once '../dto/DtoArticle.php';
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoArticle
{
	// connexion à la base de données
    private $connexion;
    private $bdd_table = "article";
    
    // constructor with $db as database connection
	public function __construct($db)
	{
		$this->setDb($db);
	}

	public function setDb(PDO $db)
	{
		$this->connexion = $db;
	}

    // read products
	public function getAll()
	{
		$articles = array();
		try
		{
			// select all query
			$query = "SELECT  id, titre, texte, photo, video, lien, statut, date_parution as dateParution, auteur
			FROM " . $this->bdd_table . " p
			ORDER BY 1";
		
			$stmt = $this->connexion->query($query);
			
			while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$articles[] = new DtoArticle($donnees);		
			}
		
			return $articles;
		}
		catch(PDOException $ex)
		{
			return $articles;
		}
	}
	
	public function get($id)
	{
		$articles = array();
		try
		{			
			if ($id > 0) {
		
				// select all query
				$query = "SELECT id, titre, texte, photo, video, lien, statut, date_parution as dateParution, auteur
				FROM " . $this->bdd_table . " p
				WHERE id = " . $id . "";

				$stmt = $this->connexion->query($query);

				while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$articles[] = new DtoArticle($donnees);		
				}
				
				return $articles;
			}
			else
			{
				return $articles;
			}
		}
		catch(PDOException $ex)
		{
			return $articles;
		}
    }
    
    public function creerArticle(DtoArticle $article)
	{
		$expirationDate = date_create(date('Y-m-d H:i:s'));
		date_add($expirationDate, date_interval_create_from_date_string('1 year'));
		$expirationDate = $expirationDate->format('Y-m-d H:i:s');
		
		// query to insert record
		$query = "INSERT INTO
			" . $this->bdd_table . "
			SET
				titre=:titre,
				texte=:texte,
				photo=:photo,
				video=:video,
				lien=:lien,
				statut=:statut,
				date_parution=:dateParution,
				auteur=:auteur,
				derniere_maj=now()";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$titre=(htmlspecialchars(strip_tags($article->getTitre())));
		$texte=(htmlspecialchars(strip_tags($article->getTexte())));
		$photo=(htmlspecialchars(strip_tags($article->getPhoto())));
		$video=(htmlspecialchars(strip_tags($article->getVideo())));
		$lien=(htmlspecialchars(strip_tags($article->getLien())));
		$statut=(htmlspecialchars(strip_tags($article->getStatut())));
		$dateParution=$article->getDateParution();
		$auteur=(htmlspecialchars(strip_tags($article->getAuteur())));
		
		// bind values
		$stmt->bindParam(":titre", $titre);
		$stmt->bindParam(":texte", $texte);
		$stmt->bindParam(":photo", $photo);
		$stmt->bindParam(":video", $video);
		$stmt->bindParam(":lien", $lien);
		$stmt->bindParam(":statut", $statut);
		$stmt->bindParam(":dateParution", $dateParution);
		$stmt->bindParam(":auteur", $auteur);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function supprimerArticle($id)
	{
		// delete query
		$query = "DELETE FROM " . $this->bdd_table . " WHERE id = ?";
 
		// prepare query
		$stmt = $this->connexion->prepare($query);
	 
		// bind id of record to delete
		$stmt->bindParam(1, $id);
	 
		// execute query
		if($stmt->execute()){
			return true;
		}
	 
		return false;
    }

	public function modifierArticle(DtoArticle $article)
	{		
		// query to insert record
		$query = "UPDATE
			" . $this->bdd_table . "
			SET
				titre=:titre,
				texte=:texte,
				photo=:photo,
				video=:video,
				lien=:lien,
				statut=:statut,
				date_parution=:dateParution,
				auteur=:auteur,
				derniere_maj=now()
			WHERE id = :id";

		// prepare query
		$stmt = $this->connexion->prepare($query);

		// sanitize
		$id=$article->getId();
		$titre=(htmlspecialchars(strip_tags($article->getTitre())));
		$texte=(htmlspecialchars(strip_tags($article->getTexte())));
		$photo=(htmlspecialchars(strip_tags($article->getPhoto())));
		$video=(htmlspecialchars(strip_tags($article->getVideo())));
		$lien=(htmlspecialchars(strip_tags($article->getLien())));
		$statut=(htmlspecialchars(strip_tags($article->getStatut())));
		$dateParution=$article->getDateParution();
		$auteur=(htmlspecialchars(strip_tags($article->getAuteur())));
		
		// bind values
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":titre", $titre);
		$stmt->bindParam(":texte", $texte);
		$stmt->bindParam(":photo", $photo);
		$stmt->bindParam(":video", $video);
		$stmt->bindParam(":lien", $lien);
		$stmt->bindParam(":statut", $statut);
		$stmt->bindParam(":dateParution", $dateParution);
		$stmt->bindParam(":auteur", $auteur);
		
		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function count()
	{
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->bdd_table . "";
 
		$stmt = $this->connexion->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function exists($id)
	{
	  // Si le param�tre est un string, c'est qu'on a fourni un login.
	  if ($id > 0) // On veut voir si tel utilisateur ayant pour login $login existe.
	  {
		// On ex�cute alors une requ�te COUNT() avec une clause WHERE, et on retourne un boolean.
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM " . $this->bdd_table . " WHERE id = ".$id."")->fetchColumn();
	  }
	  return false;
	}
}
?>