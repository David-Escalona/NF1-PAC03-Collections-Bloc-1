<?php
require_once('class.collection.php');

class Course{
  private $name;

  public function __construct($name){
    $this->name = $name;
  }

  public function __toString(){
    return "Nom del curs: " . $this->name;
  }

}

class CourseCollection extends Collection{
  public function addCourse(Course $obj = null, $key = null) {
    parent::addItem($obj, $key);
  }
}

$cc = new CourseCollection();
$cc->addCourse(new Course("Informàtica"));
$cc->addCourse(new Course("Robòtica"));
$cc->addCourse(new Course("Python"));

print $cc; 

