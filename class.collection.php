<?php
class KeyInUseException extends Exception {}

class KeyInvalidException extends Exception {}

class Collection {

  private $_members = array();    //collection members

  protected function addItem($obj, $key = null) {
        
    if($key) {
      if(isset($this->_members[$key])) {
        throw new KeyInUseException("Key \"$key\" already in use!");
      } else {
        $this->_members[$key] = $obj;
      }
    } else {
      $this->_members[] = $obj;
    }
  }

  public function removeItem($key) {
    
    if(isset($this->_members[$key])) {
      unset($this->_members[$key]);
    } else {
      throw new KeyInvalidException("Invalid key \"$key\"!");
    }  
  }
  
  public function getItem($key) {
    
    if(isset($this->_members[$key])) {
      return $this->_members[$key];
    } else {
      throw new KeyInvalidException("Invalid key \"$key\"!");
    }
  }

  public function keys() {
    return array_keys($this->_members);
  }

  public function length() {
    return sizeof($this->_members);
  }

  public function exists($key) {
    return (isset($this->_members[$key]));
  }

  public function __toString(){
	$result = "Mostrant tots els elements de la col·lecció:\n";
        for ($i = 0; $i < count($this->_members); $i++) {
        	$result.= $this->_members[$i] . "\n";  //__toString must be defined
        }
	return $result;
  }

}
?>
