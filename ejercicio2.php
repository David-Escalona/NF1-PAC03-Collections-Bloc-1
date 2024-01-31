<?php
class KeyInUseException extends Exception {}
class KeyInvalidException extends Exception {}

class Collection {
    private $_members = array();

    protected function addItem($obj, $key = null) {
        if ($key) {
            if ($this->exists($key)) {
                throw new KeyInUseException("Key \"$key\" already in use!");
            } else {
                $this->_members[$key] = $obj;
            }
        } else {
            $this->_members[] = $obj;
        }
    }

    public function removeItem($key) {
        if ($this->exists($key)) {
            unset($this->_members[$key]);
        } else {
            throw new KeyInvalidException("Invalid key \"$key\"!");
        }
    }

    public function getItem($key) {
        if ($this->exists($key)) {
            return $this->_members[$key];
        } else {
            throw new KeyInvalidException("Invalid key \"$key\"!");
        }
    }

    public function keys() {
        return array_keys($this->_members);
    }

    public function length() {
        return count($this->_members);
    }

    public function exists($key) {
        return isset($this->_members[$key]);
    }

    public function __toString() {
        $result = "Mostrant tots els elements de la col·lecció:\n";
        foreach ($this->_members as $item) {
            $result .= $item . "\n";
        }
        return $result;
    }

    public function add($obj, $key = null) {
        $this->addItem($obj, $key);
    }
}

abstract class Task {
    protected $title;
    protected $date;
    protected $dueDate;
    protected $description;
    protected $assignedTo;

    public function __construct($title, $date, $dueDate, $description, $assignedTo) {
        $this->title = $title;
        $this->date = $date;
        $this->dueDate = $dueDate;
        $this->description = $description;
        $this->assignedTo = $assignedTo;
    }

    abstract public function getDescription();
}

class IndividualTask extends Task {
    public function getDescription() {
        return "Individual Task: " . $this->title . "<br>"
            . "Date: " . $this->date . "<br>"
            . "Due Date: " . $this->dueDate . "<br>"
            . "Description: " . $this->description . "<br>"
            . "Assigned To: " . $this->assignedTo . "<br><br>";
    }
}

class Project extends Task {
    protected $budget;
    protected $workItems;

    public function __construct($title, $date, $dueDate, $description, $assignedTo, $budget) {
        parent::__construct($title, $date, $dueDate, $description, $assignedTo);
        $this->budget = $budget;
        $this->workItems = new Collection();
    }

    public function addWorkItem($workItem) {
        $this->workItems->add($workItem);
    }

    public function getDescription() {
        $output = "Project: " . $this->title . "<br>"
            . "Date: " . $this->date . "<br>"
            . "Due Date: " . $this->dueDate . "<br>"
            . "Description: " . $this->description . "<br>"
            . "Assigned To: " . $this->assignedTo . "<br>"
            . "Budget: " . $this->budget . "<br>"
            . "Work Items: <br>";

        foreach ($this->workItems->keys() as $key) {
            $output .= "&nbsp;&nbsp;&nbsp;" . $this->workItems->getItem($key)->getDescription();
        }

        $output .= "<br>";
        return $output;
    }
}

class TimeBasedTask extends Task {
    protected $estimatedHours;
    protected $hoursSpent;
    protected $childTasks;

    public function __construct($title, $date, $dueDate, $description, $assignedTo, $estimatedHours, $hoursSpent) {
        parent::__construct($title, $date, $dueDate, $description, $assignedTo);
        $this->estimatedHours = $estimatedHours;
        $this->hoursSpent = $hoursSpent;
        $this->childTasks = new Collection();
    }

    public function addChildTask($childTask) {
        $this->childTasks->add($childTask);
    }

    public function getDescription() {
        $output = "Time-Based Task: " . $this->title . "<br>"
            . "Date: " . $this->date . "<br>"
            . "Due Date: " . $this->dueDate . "<br>"
            . "Description: " . $this->description . "<br>"
            . "Assigned To: " . $this->assignedTo . "<br>"
            . "Estimated Hours: " . $this->estimatedHours . "<br>"
            . "Hours Spent: " . $this->hoursSpent . "<br>"
            . "Child Tasks: <br>";

        foreach ($this->childTasks->keys() as $key) {
            $output .= "&nbsp;&nbsp;&nbsp;" . $this->childTasks->getItem($key)->getDescription();
        }

        $output .= "<br>";
        return $output;
    }
}

class FixedBudgetTask extends Task {
    protected $budget;
    protected $childTask;

    public function __construct($title, $date, $dueDate, $description, $assignedTo, $budget) {
        parent::__construct($title, $date, $dueDate, $description, $assignedTo);
        $this->budget = $budget;
        $this->childTask = new Collection();
    }

    public function setChildTask($childTask) {
        $this->childTask->add($childTask);
    }

    public function getDescription() {
        $output = "Fixed Budget Task: " . $this->title . "<br>"
            . "Date: " . $this->date . "<br>"
            . "Due Date: " . $this->dueDate . "<br>"
            . "Description: " . $this->description . "<br>"
            . "Assigned To: " . $this->assignedTo . "<br>"
            . "Budget: " . $this->budget . "<br>";

        if ($this->childTask->length() > 0) {
            $output .= "Child Task: <br>";
            $output .= "&nbsp;&nbsp;&nbsp;" . $this->childTask->getItem(0)->getDescription();
        }

        $output .= "<br>";
        return $output;
    }
}

// Example usage
$project = new Project("Software Project", "2024-01-11", "2024-02-28", "Develop a new software", "John Doe", 10000);
$project->addWorkItem(new IndividualTask("Design UI", "2024-01-15", "2024-01-20", "Create UI wireframes", "Jane Doe"));
$project->addWorkItem(new IndividualTask("Coding", "2024-01-21", "2024-02-10", "Write code", "Bob Smith"));

$timeBasedTask = new TimeBasedTask("Code Review", "2024-02-15", "2024-02-18", "Review code", "Alice Johnson", 10, 5);
$timeBasedTask->addChildTask(new IndividualTask("Fix Bugs", "2024-02-19", "2024-02-22", "Fix reported bugs", "Charlie Brown"));

$fixedBudgetTask = new FixedBudgetTask("Testing", "2024-02-25", "2024-03-05", "Test the application", "Eve Anderson", 5000);
$fixedBudgetTask->setChildTask(new IndividualTask("Write Test Cases", "2024-02-26", "2024-03-01", "Create test cases", "David Miller"));

echo $project->getDescription();
echo $timeBasedTask->getDescription();
echo $fixedBudgetTask->getDescription();
?>


