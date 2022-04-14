<?php

require_once "Model.class.php";
require_once "Livre.class.php";



class LivreManager extends Model{
    private $livres;


    public function ajoutLivre($livre){
        $this->livres[] = $livre;
    }


    public function getLivres(){
        return $this->livres;
    }


    public function chargementLivres(){
        $req = $this->getBdd()->prepare("SELECT * FROM livres");
        $req->execute();
        $mesLivres = $req->fetchAll(PDO::FETCH_ASSOC);
        $req->closeCursor();
        foreach($mesLivres as $livre){
            $l = new Livre($livre['id'],$livre['titre'],$livre['nbPages'],$livre['image']);
            $this->ajoutLivre($l);
        }
    }

            public function getLivreById($id){
            //on utilise la boucle for et on part un zero car c'est un tableau
            for($i=0; $i < count($this->livres);$i++){
            if($this->livres[$i]->getId() === $id){
            return $this->livres[$i];
                }
            }
    }

                    
        
        
        
            public function ajoutLivreBd($titre,$nbPages,$image){
                $req = "INSERT INTO livres (titre, nbPages, image) values (:titre, :nbPages, :image)";
                $stmt = $this->getBdd()->prepare($req);
                $stmt->bindValue(":titre",$titre,PDO::PARAM_STR);
                $stmt->bindValue(":nbPages",$nbPages,PDO::PARAM_INT);
                $stmt->bindValue(":image",$image,PDO::PARAM_STR);
                $resultat = $stmt->execute();
                $stmt->closeCursor();
                if($resultat > 0){
                    $livre = new Livre($this->getBdd()->lastInsertId(),$titre,$nbPages,$image);
                    $this->ajoutLivre($livre);
                }        
            }
        
        
        
            public function suppressionLivreBD($id){
                $req = "DELETE FROM livres WHERE id = :idLivre";  
                $stmt = $this->getBdd()->prepare($req);  
                $stmt->bindValue(":idLivre",$id,PDO::PARAM_INT);  
                $resultat = $stmt->execute();   
                $stmt->closeCursor();  
                if($resultat > 0){
                    $livre = $this->getLivreById($id);
                    unset($livre);  
                }    
            }
        
        
        
            public function modificationLivreBD($id,$titre,$nbPages,$image){
                $req = "UPDATE livres SET titre = :titre, nbPages = :nbPages, image = :image WHERE id = :id";      
                $stmt = $this->getBdd()->prepare($req);      
                $stmt->bindValue(":id",$id,PDO::PARAM_INT);     
                $stmt->bindValue(":titre",$titre,PDO::PARAM_STR);      
                $stmt->bindValue(":nbPages",$nbPages,PDO::PARAM_INT);      
                $stmt->bindValue(":image",$image,PDO::PARAM_STR);       
                $resultat = $stmt->execute();      
                $stmt->closeCursor();
        
        
        
                if($resultat > 0){    
                    $this->getLivreById($id)->setTitre($titre);    
                    $this->getLivreById($id)->setTitre($nbPages);    
                    $this->getLivreById($id)->setTitre($image);
        
                }   
            }
        }