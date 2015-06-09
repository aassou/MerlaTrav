<?php
class EmployeProjetSalaireManager{
//attributes
    private $_db;
    
    //constructor
    public function __construct($db){
        $this->_db = $db;
    }
    
    //CRUD operations
    public function add(EmployeProjetSalaire $salaires){
        $query = $this->_db->prepare(
        'INSERT INTO t_salaires_projet (salaire, prime, dateOperation, idEmploye)
        VALUES (:salaire, :prime, :dateOperation, :idEmploye)') 
        or die(print_r($this->_db->errorInfo()));
		$query->bindValue(':salaire', $salaires->salaire());
		$query->bindValue(':prime', $salaires->prime());
		$query->bindValue(':dateOperation', $salaires->dateOperation());
		$query->bindValue(':idEmploye', $salaires->idEmploye());
        $query->execute();
        $query->closeCursor();
    }
	
	public function update(EmployeProjetSalaire $salaire){
		$query = $this->_db->prepare('UPDATE t_salaires_projet SET salaire=:salaire, prime=:prime, 
		dateOperation=:dateOperation WHERE id=:id') or die(print_r($this->_db->errorInfo()));
		$query->bindValue(':salaire', $salaire->salaire());
		$query->bindValue(':prime', $salaire->prime());
		$query->bindValue(':dateOperation', $salaire->dateOperation());
        $query->bindValue(':id', $salaire->id());
        $query->execute();
        $query->closeCursor();
	}
	
	public function delete($idSalaire){
		$query = $this->_db->prepare('DELETE FROM t_salaires_projet WHERE id=:idSalaire');
		$query->bindValue(':idSalaire', $idSalaire);
		$query->execute();
		$query->closeCursor();
	}
    
    public function getSalairesNumber(){
        $query = $this->_db->query('SELECT COUNT(*) AS salairesNumbers FROM t_salaires_projet');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['salairesNumbers'];
    }
	
	public function getSalairesById($idSalaire){
        $query = $this->_db->prepare('SELECT * FROM t_salaires_projet WHERE id=:idSalaire');
		$query->bindValue(':idSalaire', $idSalaire);
		$query->execute();
        //get result
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new EmployeProjetSalaire($data);
    }
    
    public function getSalairesByIdEmploye($idEmploye){
        $employeProjetSalaire = array();
        $query = $this->_db->prepare('SELECT * FROM t_salaires_projet WHERE idEmploye=:idEmploye');
		$query->bindValue(':idEmploye', $idEmploye);
		$query->execute();
        //get result
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $employeProjetSalaire[] = new EmployeProjetSalaire($data);
        }
        $query->closeCursor();
        return $employeProjetSalaire;
    }
	
	public function getTotalByIdEmploye($idEmploye){
        $query = $this->_db->prepare('SELECT SUM(salaire)+SUM(prime) as total FROM t_salaires_projet WHERE idEmploye=:idEmploye');
		$query->bindValue(':idEmploye', $idEmploye);
		$query->execute();
        //get result
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['total'];
    }
	
	public function getSalairesTotalPrimeByIdCharge($idCharge){
        $query = $this->_db->prepare('SELECT SUM(prime) as totalPrime FROM t_salaires_projet WHERE idCharge=:idCharge');
		$query->bindValue(':idCharge', $idCharge);
		$query->execute();
        //get result
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['totalPrime'];
    }
	
	public function getTotalChargesSalaires(){
		$query = $this->_db->query('SELECT (SUM(salaire)+SUM(prime)) AS totalCharges FROM t_salaires_projet');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $totalCharges = $data['totalCharges'];
        return $totalCharges;
	}
	
	public function getChargesSalairesMoisAnnee($moisAnnee){
		$query = $this->_db->prepare("SELECT SUM(salaire)+SUM(prime) AS totalCharges 
		FROM t_salaires_projet WHERE DATE_FORMAT(dateOperation,'%m-%Y')=:moisAnnee");
		$query->bindValue(':moisAnnee', $moisAnnee);
		$query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $totalCharges = $data['totalCharges'];
        return $totalCharges;
	}
	
	public function getLastId(){
        $query = $this->_db->query('SELECT id AS last_id FROM t_salaires_projet ORDER BY id DESC LIMIT 0, 1');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $id = $data['last_id'];
        return $id;
    }
}