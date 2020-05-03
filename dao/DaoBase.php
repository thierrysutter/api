<?php
include_once '../config/core.php';
include_once '../commun/utilities.php';

class DaoBase
{
	// connexion à la base de données
    protected $connexion;
    protected $tableName;
    
    // constructor with $db as database connection
	public function __construct($db, $tableName)
	{
        $this->setDb($db);
        $this->setTableName($tableName);
	}

	public function setDb(PDO $db)
	{
		$this->connexion = $db;
    }
    
    public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

	public function Compte()
	{
		$query = "SELECT COUNT(*) as total_rows FROM " . $tableName . "";
 
		$stmt = $this->connexion->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	public function exists($id)
	{
	  if ($id > 0)
	  {
		// On exécute alors une requète COUNT() avec une clause WHERE, et on retourne un boolean.
		return (bool) $this->connexion->query("SELECT COUNT(*) FROM " . $tableName . " WHERE id = ".$id."")->fetchColumn();
	  }
	  return false;
	}
}

?>