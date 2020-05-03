<?php
class Database
{
  /*
  $server = "localhost";
  $username = "root";
  $password = "";
  $db = "stock";
  */

  /* PROD */
  // private $server="tsutter.assaintjulienlesmetz.com"; //Adresse du serveur
  // private $username="tsutter"; //Login sur la bdd
  // private $password="lxyr4kb5"; //Mot de passe de la bdd
  // private $db="tsutter"; //Nom de la bdd � utiliser
  
  /* TEST local */
  private $server="localhost"; // Host name 
  private $username="root"; // Mysql username 
  private $password=""; // Mysql password 
  private $db="tsutter"; // Database name 
  
  
  public $connexion;



  public function getConnection() {
    $this->connexion = null;
    
    try
    {
      $this->connexion = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->db, $this->username, $this->password);
      $this->connexion->exec("set names utf8");
    }
    catch(PDOException $exception)
    {
        echo "Connection error: " . $exception->getMessage();
    }

    return $this->connexion;
  }
}
  

  /*
  Compte POSTMAN
    email = thierry.sutter@assaintjulienlesmetz.com
    login = ThierrySUTTER
    mdp = Ja7i@%D83GLp
   */


?>