<?php
//ici tous notre site part d'index.php il est inutile de partir du chemin d'index mais directement a models/livreManager etc...
require_once "models/LivreManager.class.php";


//ici il s'agit d'une class qui permet a l'utilisateur d'acceder aux information sur la 
//commande de routage pour une demande spécifique via l'url
class LivresController{

    private $livreManager;


    //ici j'instancie le livremanager que j'ai déclarrer plus haut en private
    public function __construct(){
        $this->livreManager = new LivreManager;
        $this->livreManager->chargementLivres();
    }



    public function afficherLivres(){
    // ici je crée une boucle qui me permet d'afficher
    // ma liste de livre a travers ma variable livres.
    //ma variable livres sera donc utiliser dans mon controleur par 
    //la fonction afficher ce qui permet d'afficher l'ensemble des livres.
        $livres = $this->livreManager->getLivres();

        require "views/livres.view.php";

    }



    public function afficherLivre($id){
    //ici je crée une nouvelle route pour pouvoir récupperer mon nouveau template view
        $livre = $this->livreManager->getLivreById($id);

        require "views/afficherLivre.view.php";

    }



    public function ajoutLivre(){
        require "views/ajoutLivre.view.php";
    }


    public function ajoutLivreValidation(){
        $file = $_FILES['image'];
        $repertoire = "public/images/";
        $nomImageAjoute = $this->ajoutImage($file,$repertoire);
        $this->livreManager->ajoutLivreBd($_POST['titre'],$_POST['nbPages'],$nomImageAjoute);
        header('Location: '. URL . "livres");
    }



    public function suppressionLivre($id){
        $nomImage = $this->livreManager->getLivreById($id)->getImage();
        unlink("public/images/".$nomImage);
        $this->livreManager->suppressionLivreBD($id);
        header('Location: '. URL . "livres");
    }



    public function modificationLivre($id){
        $livre = $this->livreManager->getLivreById($id);
        require "views/modifierLivre.view.php";
    }



    public function modificationLivreValidation(){
        $imageActuelle = $this->livreManager->getLivreById($_POST['identifiant'])->getImage();
        $file = $_FILES['image'];



        if($file['size'] > 0){
            unlink("public/images/".$imageActuelle);
            $repertoire = "public/images/";
            $nomImageToAdd = $this->ajoutImage($file,$repertoire);
        } else {
            $nomImageToAdd = $imageActuelle;
        }
        $this->livreManager->modificationLivreBD($_POST['identifiant'],$_POST['titre'],$_POST['nbPages'],$nomImageToAdd);
        header('Location: '. URL . "livres");
    }



    private function ajoutImage($file, $dir){
        if(!isset($file['name']) || empty($file['name']))
            throw new Exception("Vous devez indiquer une image");
        if(!file_exists($dir)) mkdir($dir,0777);
        $extension = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        $random = rand(0,99999);
        $target_file = $dir.$random."_".$file['name'];
        

        if(!getimagesize($file["tmp_name"]))
            throw new Exception("Le fichier n'est pas une image");

        if($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png" && $extension !== "gif")
            throw new Exception("L'extension du fichier n'est pas reconnu");

        if(file_exists($target_file))
            throw new Exception("Le fichier existe déjà");

        if($file['size'] > 500000)
            throw new Exception("Le fichier est trop gros");

        if(!move_uploaded_file($file['tmp_name'], $target_file))
            throw new Exception("l'ajout de l'image n'a pas fonctionné");
            
        else return ($random."_".$file['name']);
    }
}