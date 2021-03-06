<?php
class PiecesAppartement{
	//attributes
	private $_id;
	private $_nom;
	private $_url;
	private $_idAppartement;
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
	
	public function setUrl($url){
		$this->_url = $url;
	}
	
	public function setIdAppartement($idAppartement){
		$this->_idAppartement = $idAppartement;
	}
	//getters
	public function id(){
		return $this->_id;
	}
	
	public function nom(){
		return $this->_nom;
	}
	
	public function url(){
		return $this->_url;
	}
	
	public function idAppartement(){
		return $this->_idAppartement;
	}
}
