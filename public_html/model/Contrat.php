<?php
class Contrat{
        
    //attributes
    private $_id;
    private $_dateCreation;
    private $_prixVente;
    private $_avance;
	private $_modePaiement;
	private $_dureePaiement;
    private $_echeance;
    private $_note;
    private $_idClient;
    private $_idProjet;
    private $_idBien;
	private $_typeBien;
	private $_code;
	private $_status;
    
    //le constructeur
    public function __construct($data){
        $this->hydrate($data);
    }
    
    //la focntion hydrate sert à attribuer les valeurs en utilisant les setters d'une façon dynamique!
    public function hydrate($data){
        foreach ($data as $key => $value){
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }
    
    //setters
    public function setId($id){
        $this->_id = $id;
    }
    
    public function setDateCreation($dateCreation){
        $this->_dateCreation = $dateCreation;
    }
    
    public function setPrixVente($prixVente){
        $this->_prixVente = $prixVente;
    }
    
    public function setAvance($avance){
        $this->_avance = $avance;
    }
    
	public function setModePaiement($modePaiement){
        $this->_modePaiement = $modePaiement;
    }
	
	public function setDureePaiement($dureePaiement){
        $this->_dureePaiement = $dureePaiement;
    }
	
	public function setEcheance($echeance){
        $this->_echeance = $echeance;
    }
    
    public function setNote($note){
        $this->_note = $note;
    }
    
    public function setIdClient($idClient){
        $this->_idClient = $idClient;
    }
    
    public function setIdProjet($idProjet){
        $this->_idProjet = $idProjet;
    }
    
    public function setIdBien($idBien){
        $this->_idBien = $idBien;
    }
	
	public function setTypeBien($typeBien){
        $this->_typeBien = $typeBien;
    }
	
	public function setCode($code){
        $this->_code = $code;
    }
	
	public function setStatus($status){
        $this->_status = $status;
    }
    
    //getters
    
    public function id(){
        return $this->_id;
    }
    
    public function dateCreation(){
        return $this->_dateCreation;
    }
    
    public function prixVente(){
        return $this->_prixVente;
    }
    
    public function avance(){
        return $this->_avance;
    }
    
	public function modePaiement(){
        return $this->_modePaiement;
    }
	
	public function dureePaiement(){
        return $this->_dureePaiement;
    }
	
    public function echeance(){
        return $this->_echeance;
    }
    
    public function note(){
        return $this->_note;
    }
    
    public function idClient(){
        return $this->_idClient;
    }
    
    public function idProjet(){
        return $this->_idProjet;
    }
    
    public function idBien(){
        return $this->_idBien;
    }
	
	public function typeBien(){
        return $this->_typeBien;
    }
	
	public function code(){
        return $this->_code;
    }
	
	public function status(){
        return $this->_status;
    }
}
