<?php
class Client{

    //attributes
    private $_id;
    private $_nom;
    private $_cin;
    private $_email;
    private $_facebook;
    private $_adresse;
    private $_telephone1;
    private $_telephone2;
    private $_created;
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
    
    public function setNom($nom){
        $this->_nom = $nom;
    }
    
    public function setAdresse($adresse){
        $this->_adresse = $adresse;
    }
    
    public function setTelephone1($telephone1){
        $this->_telephone1 = $telephone1;
    }
    
    public function setTelephone2($telephone2){
        $this->_telephone2 = $telephone2;
    }
    
    public function setEmail($email){
        $this->_email = $email;
    }
    
    public function setCin($cin){
        $this->_cin = $cin;
    }
    
    public function setFacebook($facebook){
        $this->_facebook = $facebook;
    }
    public function setCreated($created){
        $this->_created = $created;
    }
	
	public function setCode($code){
		$this->_code = $code;
	}
    //getters
    
    public function id(){
        return $this->_id;
    }
    
    public function nom(){
        return $this->_nom;
    }
    
    public function adresse(){
        return $this->_adresse;
    }
    
    public function telephone1(){
        return $this->_telephone1;
    }
    
    public function telephone2(){
        return $this->_telephone2;
    }
    
    public function email(){
        return $this->_email;
    }
    
    public function cin(){
        return $this->_cin;
    }
    
    public function facebook(){
        return $this->_facebook;
    }
    
    public function created(){
        return $this->_created;
    }
    
	public function code(){
        return $this->_code;
    }
	    
}