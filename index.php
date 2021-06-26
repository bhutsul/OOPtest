<?php

//i am going to use an abstract factory, because we could make another kind of robot types

abstract class AbstractFactory
{
    protected $types = [];


    /**
     * i used class name for key, it helps us  to ignore duplicates
     * @param AbstractRobot $robot
     */
    public function addType(AbstractRobot $robot): void
    {
        $this->types[get_class($robot)] = $robot;
    }

    protected function validateType(string $className): bool
    {
        return array_key_exists($className, $this->types);
    }

    /**
     * @param int $count
     * @param $className
     * @return array|string
     */
    protected function createRobots(int $count, $className)
    {
        if (!$this->validateType($className)) {
            return "you need to add a robot $className type";
//            throw new Exception("$className does not exist");
        }

        $robots = [];

        for ($i = 0; $i < $count; $i++) {
            $robots[] = new $className();
        }

        return $robots;
    }

    //if php version 8 i could use :array|string
    abstract public function createRobot1(int $count);
    abstract public function createRobot2(int $count);
    abstract public function createMergeRobot(int $count);
}

//i did a validation type, but i do not know if type is required

class FactoryRobot extends AbstractFactory
{

    /**
     * @param int $count
     * @return array|string
     */
    public function createRobot1(int $count)
    {
        $className = 'Robot1';

        return $this->createRobots($count, $className);
    }

    /**
     * @param int $count
     * @return array|string
     */
    public function createRobot2(int $count)
    {
        $className = 'Robot2';

        return $this->createRobots($count, $className);
    }

    /**
     * @param int $count
     * @return array|string
     */
    public function createMergeRobot(int $count)
    {
        $className = 'MergeRobot';

        return $this->createRobots($count, $className);
    }
}

abstract class AbstractRobot
{
    abstract function getWeight();
    abstract function getHeight();
    abstract function getSpeed();
}

class Robot1 extends AbstractRobot
{
    public function getHeight()
    {
        // TODO: Implement getHeight() method.
    }
    public function getWeight()
    {
        // TODO: Implement getWeight() method.
    }
    public function getSpeed()
    {
        // TODO: Implement getSpeed() method.
    }
}

class Robot2 extends AbstractRobot
{
    public function getHeight()
    {
        // TODO: Implement getHeight() method.
    }
    public function getWeight()
    {
        // TODO: Implement getWeight() method.
    }
    public function getSpeed()
    {
        // TODO: Implement getSpeed() method.
    }
}

interface MergeRobotInterface
{
    public function addRobot($robot): void;
    public function getRobots(): array;
}

class MergeRobot extends AbstractRobot implements MergeRobotInterface
{
    private $robots = [];

    public function getHeight()
    {
        // TODO: Implement getHeight() method.
    }
    public function getWeight()
    {
        // TODO: Implement getWeight() method.
    }
    public function getSpeed()
    {
        // TODO: Implement getSpeed() method.
    }

    //mixed types if php 8 AbstractRobot|array
    public function addRobot($robots): void
    {
        if ($robots instanceof AbstractRobot) {
            $this->robots[] = $robots;
        }

        if (is_array($robots)) {
            foreach ($robots as $robot) {
                $this->robots[] = $robot;
            }
        }
    }

    /**
     * @return array
     */
    public function getRobots(): array
    {
        return $this->robots;
    }
}

function reset(array $mergeRobots)
{

}

$factory = new FactoryRobot();

$factory->addType(new Robot1());
$factory->addType(new Robot2());

var_dump($factory->createRobot1(5));
var_dump($factory->createRobot2(2));

$mergeRobot = new MergeRobot();
$mergeRobot->addRobot(new Robot2());
$mergeRobot->addRobot($factory->createRobot2(2));