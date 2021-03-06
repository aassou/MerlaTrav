<?php
class ContratManager{
    //attributes
    private $_db;
    
    //constructor
    public function __construct($db){
        $this->_db = $db;
    }
    
    //CRUD operations
    public function add(Contrat $contrat){
        $query = $this->_db->prepare('
        INSERT INTO t_contrat (dateCreation, prixVente, avance, modePaiement, dureePaiement, echeance, 
        note, idClient, idProjet, idBien, typeBien, code, status)
        VALUES (:dateCreation, :prixVente, :avance, :modePaiement, :dureePaiement, :echeance, :note, :idClient, 
		 :idProjet, :idBien, :typeBien, :code, :status)') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':dateCreation', $contrat->dateCreation());
        $query->bindValue(':prixVente', $contrat->prixVente());
        $query->bindValue(':avance', $contrat->avance());
		$query->bindValue(':modePaiement', $contrat->modePaiement());
		$query->bindValue(':dureePaiement', $contrat->dureePaiement());
        $query->bindValue(':echeance', $contrat->echeance());
		$query->bindValue(':note', $contrat->note());
        $query->bindValue(':idClient', $contrat->idClient());
        $query->bindValue(':idProjet', $contrat->idProjet());
        $query->bindValue(':idBien', $contrat->idBien());
		$query->bindValue(':typeBien', $contrat->typeBien());
		$query->bindValue(':code', $contrat->code());
		$query->bindValue(':status', 'actif');
        $query->execute();
        $query->closeCursor();
    }
    
    public function update(Contrat $contrat){
        $query = $this->_db->prepare('UPDATE t_contrat SET dateCreation=:dateCreation, prixVente=:prixVente,
        avance=:avance, modePaiement=:modePaiement, dureePaiement=:dureePaiement, echeance=:echeance,  
        note=:note WHERE id=:id') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':dateCreation', $contrat->dateCreation());
        $query->bindValue(':prixVente', $contrat->prixVente());
        $query->bindValue(':avance', $contrat->avance());
		$query->bindValue(':modePaiement', $contrat->modePaiement());
		$query->bindValue(':dureePaiement', $contrat->dureePaiement());
        $query->bindValue(':echeance', $contrat->echeance());
		$query->bindValue(':note', $contrat->note());
        $query->bindValue(':id', $contrat->id());
        $query->execute();
        $query->closeCursor();
    }
	
	public function desisterContrat($idContrat){
		$query = $this->_db->prepare('UPDATE t_contrat SET status=:status WHERE id=:id') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $idContrat);
		$query->bindValue(':status', 'annulle');
        $query->execute();
        $query->closeCursor();
	}

	public function activerContrat($idContrat){
		$query = $this->_db->prepare('UPDATE t_contrat SET status=:status WHERE id=:id') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $idContrat);
		$query->bindValue(':status', 'actif');
        $query->execute();
        $query->closeCursor();
	}
	
	public function changerBien($idContrat, $idBien, $typeBien){
		$query = $this->_db->prepare('UPDATE t_contrat SET typeBien=:typeBien, idBien=:idBien WHERE id=:id') 
        or die(print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $idContrat);
        $query->bindValue(':idBien', $idBien);
        $query->bindValue(':typeBien', $typeBien);
        $query->execute();
        $query->closeCursor();
	}
	
	public function delete($idContrat){
		$query = $this->_db->prepare('DELETE FROM t_contrat WHERE id=:idContrat')
		or die(print_r($this->_db->errorInfo()));;
		$query->bindValue(':idContrat', $idContrat);
		$query->execute();
		$query->closeCursor();
	}
    
    public function getClientNameByIdContract($idContrat){
        $query = $this->_db->prepare('SELECT nom FROM t_client, t_contrat WHERE t_client.id=t_contrat.idClient AND t_contrat.id=:idContrat');
        $query->bindValue(':idContrat', $idContrat);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['nom'];
    }
    
    public function getContratsNumber(){
        $query = $this->_db->query('SELECT COUNT(*) AS contratNumbers FROM t_contrat');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['contratNumbers'];
    }
	
	public function getContratsNumberByIdProjet($idProjet){
        $query = $this->_db->query('SELECT COUNT(*) AS contratNumbers FROM t_contrat WHERE idProjet='.$idProjet);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['contratNumbers'];
    }
    
    public function getContratNumberToday(){
        $query = $this->_db->query('SELECT COUNT(*) AS contratNumbersToday FROM t_contrat WHERE dateCreation=CURDATE()');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['contratNumbersToday'];
    }

     public function getContratNumberWeek(){
        $query = $this->_db->query('SELECT COUNT(*) AS contratNumbersWeek FROM t_contrat WHERE dateCreation BETWEEN SUBDATE(CURDATE(),7) AND CURDATE()');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['contratNumbersWeek'];
    }
    
    public function getContratNumberMonth(){
        $query = $this->_db->query('SELECT COUNT(*) AS contratNumbersMonth FROM t_contrat WHERE MONTHNAME(dateCreation) = MONTHNAME(CURDATE())');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['contratNumbersMonth'];
    }
    
    public function getContrats(){
        $contrats = array();
        $query = $this->_db->query('SELECT * FROM t_contrat GROUP BY idClient');
        //get result
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }

    public function getIdClientByIdProject($idProject){
        $idsClient = array();
        $query = $this->_db->prepare('SELECT idClient FROM t_contrat WHERE idProjet=:idProjet');
        $query->bindValue(':idProjet', $idProject);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $idsClient[] = $data['idClient'];
        }
        $query->closeCursor();
        return $idsClient;
    }

    public function getContratByIdBien($idBien){
        $query = $this->_db->prepare('SELECT * FROM t_contrat WHERE idBien=:idBien');
        $query->bindValue(':idBien', $idBien);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new Contrat($data);
    }

    public function getContratsByIdProjet($idProjet, $begin, $end){
        $contrats = array();    
        $query = $this->_db->prepare("SELECT * FROM t_contrat WHERE idProjet=:idProjet ORDER BY status, dateCreation LIMIT ".$begin.", ".$end);
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }
	
	public function getContratsDesistesByIdProjet($idProjet, $begin, $end){
        $contrats = array();    
        $query = $this->_db->prepare("SELECT * FROM t_contrat WHERE idProjet=:idProjet AND status='annulle' LIMIT ".$begin.", ".$end);
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }

    public function getContratsToday(){
        $contrats = array();
        $query = $this->_db->query('SELECT c.prixVente, cl.nom FROM t_contrat c, t_client cl WHERE cl.id=c.idClient AND c.dateCreation = CURDATE() ORDER BY c.dateCreation');
        //get result
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }
    
    public function getContratYesterday(){
        $contrats = array();
        $query = $this->_db->query('SELECT c.prixVente, cl.nom FROM t_contrat c, t_client cl WHERE cl.id=c.idClient AND dateCreation = SUBDATE(CURDATE(),1)');
        //get result
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }
    
    public function getContratWeek(){
        $contrats = array();
        $query = $this->_db->query('SELECT c.prixVente, cl.nom FROM t_contrat c, t_client cl WHERE cl.id=c.idClient AND dateCreation BETWEEN SUBDATE(CURDATE(),7) AND CURDATE()');
        //get result
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }
    
    public function getContratsByIdClient($idClient){
        $contrats = array();    
        $query = $this->_db->prepare('SELECT * FROM t_contrat WHERE idClient =:id');
        $query->bindValue(':id', $idClient);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $contrats[] = new Contrat($data);
        }
        $query->closeCursor();
        return $contrats;
    }
    
    public function getContratById($idContrat){
        $query = $this->_db->prepare('SELECT * FROM t_contrat WHERE id=:id');
        $query->bindValue(':id', $idContrat);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new Contrat($data);
    }

	public function getContratByCode($code){
        $query = $this->_db->prepare('SELECT * FROM t_contrat WHERE code=:code');
        $query->bindValue(':code', $code);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new Contrat($data);
    }
    
    public function getLastId(){
        $query = $this->_db->query('SELECT id AS last_id FROM t_contrat ORDER BY id DESC LIMIT 0, 1');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $id = $data['last_id'];
        return $id;
    }
    
	public function getCodeContrat($code){
        $query = $this->_db->prepare('SELECT code FROM t_contrat WHERE code=:code');
		$query->bindValue(':code', $code);
		$query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['code'];
    }
	
	public function sommeAvanceByIdProjet($idProjet){
        $query = $this->_db->prepare('SELECT SUM(avance) AS total FROM t_contrat 
        WHERE idProjet=:idProjet');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
        return $data['total'];
    }
}
