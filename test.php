<?php

require_once('class.collection.php');

class Foo {
    private $_name;
    private $_number;

    public function __construct($name, $number) {
        $this->_name = $name;
        $this->_number = $number;
    }

    public function __toString() {
        return $this->_name . ' is number ' . $this->_number;
    }
}

class MyCollection extends Collection {
    public function addItem($item, $key = null) {
        parent::addItem($item, $key);
    }
}

class MyClass {
    public function run() {
        $colFoo = new MyCollection(); // Use the extended class
        $colFoo->addItem(new Foo("Steve", 14), "steve");
        $colFoo->addItem(new Foo("Ed", 37), "ed");
        $colFoo->addItem(new Foo("Bob", 49));

        $objSteve = $colFoo->getItem("steve");

        print $objSteve; //prints "Steve is number 14"
        print "<br>";
        $colFoo->removeItem("steve"); //deletes the "steve" object
        print "Removed: Steve<br>";

        try {
            $objSteve = $colFoo->getItem("steve"); //throws KeyInvalidException
        } catch (KeyInvalidException $kie) {
            print "The collection doesn't contain anything called 'steve'";
        }
    }
}

$myClassInstance = new MyClass();
$myClassInstance->run();
?>

