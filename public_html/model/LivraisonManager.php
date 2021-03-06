<?php
class LivraisonManager{
    //attributes
    private $_db;
    
    //constructor
    public function __construct($db){
        $this->_db = $db;
    }
    
    //CRUD operations
    public function add(Livraison $livraison){
        $query = $this->_db->prepare('INSERT INTO t_livraison (libelle, designation, quantite, prixUnitaire,
         dateLivraison, idFournisseur, idProjet, code)
        VALUES (:libelle, :designation, :quantite, :prixUnitaire, :dateLivraison, 
         :idFournisseur, :idProjet, :code)') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':libelle', $livraison->libelle());
        $query->bindValue(':designation', $livraison->designation());
        $query->bindValue(':quantite', $livraison->quantite());
        $query->bindValue(':prixUnitaire', $livraison->prixUnitaire());
        $query->bindValue(':dateLivraison', $livraison->dateLivraison());
        $query->bindValue(':idFournisseur', $livraison->idFournisseur());
        $query->bindValue(':idProjet', $livraison->idProjet());
		$query->bindValue(':code', $livraison->code());
        $query->execute();
        $query->closeCursor();
    }

    public function update(Livraison $livraison){
        $query = $this->_db->prepare('UPDATE t_livraison SET libelle=:libelle, designation=:designation,
        quantite=:quantite, prixUnitaire=:prixUnitaire, dateLivraison=:dateLivraison
        WHERE id=:id') or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':libelle', $livraison->libelle());
        $query->bindValue(':designation', $livraison->designation());
        $query->bindValue(':quantite', $livraison->quantite());
        $query->bindValue(':prixUnitaire', $livraison->prixUnitaire());
        $query->bindValue(':dateLivraison', $livraison->dateLivraison());
        $query->bindValue(':id', $livraison->id());
        $query->execute();
        $query->closeCursor();
    }
	
	public function delete($idLivraison){
		$query = $this->_db->prepare('DELETE FROM t_livraison WHERE id=:idLivraison')
		or die(print_r($this->_db->errorInfo()));;
		$query->bindValue(':idLivraison', $idLivraison);
		$query->execute();
		$query->closeCursor();
	}
    
    public function getLivraisonsByIdFournisseur($idFournisseur){
        $livraisons = array();
        $query = $this->_db->prepare('SELECT * FROM t_livraison WHERE idFournisseur=:idFournisseur');
        $query->bindValue(':idFournisseur', $idFournisseur);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
    
    public function getLivraisonToday(){
        $livraisons = array();
        $query = $this->_db->query('SELECT * FROM t_livraison WHERE dateLivraison=CURDATE()');
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
    
    public function getLivraisonYesterday(){
        $livraisons = array();
        $query = $this->_db->query('SELECT * FROM t_livraison WHERE dateLivraison=SUBDATE(CURDATE(),1)');
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
    
    public function getLivraisonsWeek(){
        $livraisons = array();
        $query = $this->_db->query('SELECT * FROM t_livraison WHERE dateLivraison BETWEEN SUBDATE(CURDATE(),7) AND CURDATE()');
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
	
	public function getLivraisonsNumberWeek(){
        $query = $this->_db->query('SELECT COUNT(*) AS numberLivraison FROM t_livraison WHERE dateLivraison BETWEEN SUBDATE(CURDATE(),7) AND CURDATE()');
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['numberLivraison'];
    }
    
    public function getLivraisonsByIdProjet($idProjet){
        $livraisons = array();
        $query = $this->_db->prepare('SELECT * FROM t_livraison WHERE idProjet=:idProjet');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
	
	public function getLivraisonsByIdProjetByLimit($idProjet, $begin, $end){
        $livraisons = array();
        $query = $this->_db->prepare('SELECT * FROM t_livraison WHERE idProjet=:idProjet LIMIT '.$begin.', '.$end);
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = new Livraison($data);
        }
        $query->closeCursor();
        return $livraisons;
    }
	
	public function getLivraisonsNumberByIdProjet($idProjet){
		$query = $this->_db->query('SELECT COUNT(*) AS livraisonNumbers FROM t_livraison WHERE idProjet='.$idProjet);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['livraisonNumbers'];
	}
    
    public function getLivraisonNumber(){
        $query = $this->_db->query('SELECT COUNT(*) AS livraisonNumber FROM t_livraison');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['livraisonNumber'];
    }
    
	public function getFournisseursByIdProjet($idProjet){
		$fournisseurs = array();
		$query = $this->_db->prepare(" SELECT DISTINCT f.id AS id, nom FROM t_fournisseur f INNER JOIN t_livraison l 
		ON l.idFournisseur = f.id WHERE l.idProjet=:idProjet ")
		or die(print_r($this->_db->errorInfo()));
		$query->bindValue(':idProjet', $idProjet);
		$query->execute();
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $fournisseurs[] = new Fournisseur($data);
        }
        $query->closeCursor();
		return $fournisseurs;
	}
	
    public function getIdFournisseurByIdProject($idProjet){
        $idsFournisseur = array();
        $query = $this->_db->prepare('SELECT DISTINCT idFournisseur FROM t_livraison WHERE idProjet=:idProjet');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $livraisons[] = $data['idFournisseur'];
        }
        $query->closeCursor();
        if(!empty($livraisons)){
            return $livraisons;    
        }
        else{
            return 1;
        }
    }
    
    public function getLivraisonById($id){
        $query = $this->_db->prepare('SELECT * FROM t_livraison WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new Livraison($data);
    }
    
    
    public function resteTotal(){
        $query = $this->_db->query('SELECT SUM(reste) AS credit FROM t_livraison');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['credit'];
    }
    
    public function getLastId(){
        $query = $this->_db->query('SELECT id AS last_id FROM t_livraison ORDER BY id DESC LIMIT 0, 1');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $id = $data['last_id'];
        return $id;
    }

	public function getLivraisonByCode($code){
        $query = $this->_db->prepare('SELECT * FROM t_livraison WHERE code=:code');
        $query->bindValue(':code', $code);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new Livraison($data);
    }
	
	public function getCodeLivraison($code){
        $query = $this->_db->prepare('SELECT code FROM t_livraison WHERE code=:code');
		$query->bindValue(':code', $code);
		$query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['code'];
    }

	public function getSommeLivraisonsByIdProjetAndIdFournisseur($idProjet, $idFournisseur){
		$query = $this->_db->prepare(' SELECT SUM(prixUnitaire*quantite) AS total FROM t_livraison 
		WHERE idProjet=:idProjet AND idFournisseur=:idFournisseur ');
		$query->bindValue(':idProjet', $idProjet);
		$query->bindValue(':idFournisseur', $idFournisseur);
		$query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['total'];
	}
}