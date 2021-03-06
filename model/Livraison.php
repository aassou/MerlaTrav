<?php
class Livraison{
    //attributes
    private $_id;
    private $_libelle;
    private $_designation;
    private $_quantite;
    private $_prixUnitaire;
    private $_paye;
    private $_reste;
    private $_dateLivraison;
    private $_modePaiement;
    private $_idFournisseur;
    private $_idProjet;
	private $_code;
    
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
    
    public function setLibelle($libelle){
        $this->_libelle = $libelle;
    }
    
    public function setDesignation($designation){
        $this->_designation = $designation;
    }
    
    public function setQuantite($quantite){
        $this->_quantite = (int) $quantite;
    }
    
    public function setPrixUnitaire($prixUnitaire){
        $this->_prixUnitaire = (float) $prixUnitaire;
    }
    
    public function setPaye($paye){
        $this->_paye = (float) $paye;
    }
    
    public function setReste($reste){
        $this->_reste = (float) $reste;
    }
    
    public function setDateLivraison($dateLivraison){
        $this->_dateLivraison = $dateLivraison;
    }
    
    public function setModePaiement($modePaiement){
        $this->_modePaiement = $modePaiement;
    }
    
    public function setIdProjet($idProjet){
        $this->_idProjet = $idProjet;
    }
    
    public function setIdFournisseur($idFournisseur){
        $this->_idFournisseur = $idFournisseur;
    }
    
	public function setCode($code){
        $this->_code = $code;
    }
	
    //getters
    public function id(){
        return $this->_id;
    }
    
    public function libelle(){
        return $this->_libelle;
    }
    
    public function designation(){
        return $this->_designation;
    }
    
    public function quantite(){
        return $this->_quantite;
    }
    
    public function prixUnitaire(){
        return $this->_prixUnitaire;
    }
    
    public function paye(){
        return $this->_paye;
    }
    
    public function reste(){
        return $this->_reste;
    }
    
    public function dateLivraison(){
        return $this->_dateLivraison;
    }
    
    public function modePaiement(){
        return $this->_modePaiement;
    }
    
    public function idFournisseur(){
        return $this->_idFournisseur;
    }
    
    public function idProjet(){
        return $this->_idProjet;
    }
    
	public function code(){
        return $this->_code;
    }
	
}
