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
     * i would like to use for first element of array object from type, but it is not logical
     * @param int $count
     * @param $className
     * @return array
     * @throws Exception
     */
    protected function createRobots(int $count, $className): array
    {
        if (!$this->validateType($className)) {
//            return "you need to add a robot $className type";
            throw new Exception("$className does not exist");
        }

        $robots = [];

        for ($i = 0; $i < $count; $i++) {
            $robots[] = clone $this->types[$className];
        }

        return $robots;
    }

    abstract public function createRobot1(int $count): array;
    abstract public function createRobot2(int $count): array;
    abstract public function createMergeRobot(int $count): array;
}

//i did a validation type, but i do not know if type is required, if it is not required i would just create instance
//i would like to send exception but i do not know about client, i think exception is better

class FactoryRobot extends AbstractFactory
{

    /**
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function createRobot1(int $count): array
    {
        $className = 'Robot1';

        return $this->createRobots($count, $className);
    }

    /**
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function createRobot2(int $count): array
    {
        $className = 'Robot2';

        return $this->createRobots($count, $className);
    }

    /**
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function createMergeRobot(int $count): array
    {
        $className = 'MergeRobot';

        return $this->createRobots($count, $className);
    }
}

abstract class AbstractRobot
{
    protected $weight;
    protected $height;
    protected $speed;

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height): void
    {
        $this->height = $height;
    }

    /**
     * @param mixed $speed
     */
    public function setSpeed($speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }
}

class Robot1 extends AbstractRobot
{
    public function __construct()
    {
        $this->setSpeed(1);
        $this->setWeight(1);
        $this->setHeight(1);
    }
}

class Robot2 extends AbstractRobot
{
    public function __construct()
    {
        $this->setSpeed(2);
        $this->setWeight(1);
        $this->setHeight(1);
    }
}

interface MergeRobotInterface
{
    public function addRobot($robot);
    public function getRobots(): array;
    public function speedCalculate(): int;
}

class MergeRobot extends AbstractRobot implements MergeRobotInterface
{
    private $robots = [];


    /**
     * mixed types if php 8 AbstractRobot|array
     * @param $robots
     * @return $this
     * @throws Exception
     */
    public function addRobot($robots)
    {
        if ($robots instanceof AbstractRobot) {
            $this->robots[] = $robots;

            $height = $this->height += $robots->getHeight();
            $weight = $this->weight += $robots->getWeight();

            $this->setHeight($height);
            $this->setWeight($weight);
        } else if (is_array($robots)) {
            foreach ($robots as $robot) {
                if (!$robot instanceof AbstractRobot) {
                    throw new Exception('Robot must be instance of AbstractRobot');
                }

                $this->robots[] = $robot;
            }

            $this->setHeight($this->heightCalculate());
            $this->setWeight($this->weightCalculate());
        } else {
            throw new Exception('Robots must be instance of AbstractRobot or array');
        }

        $this->setSpeed($this->speedCalculate());

        return $this;
    }

    /**
     * @param array $robots
     */
    public function setRobots(array $robots): void
    {
        $this->robots = $robots;
    }

    /**
     * @return array
     */
    public function getRobots(): array
    {
        return $this->robots;
    }

    public function speedCalculate(): int
    {
        $robots_speed = [];

        foreach ($this->robots as $robot) {
            $robots_speed[] = $robot->getSpeed();
        }

        return !empty($robots_speed) ? min($robots_speed) : 0;
    }

    public function heightCalculate(): int
    {
        $height = 0;

        foreach ($this->robots as $robot) {
            $height += $robot->getHeight();
        }

        return $height;
    }

    public function weightCalculate(): int
    {
        $weight = 0;

        foreach ($this->robots as $robot) {
            $weight += $robot->getWeight();
        }

        return $weight;
    }
}



function resett(array $mergeRobots): AbstractRobot
{
    $mergeRobot = new MergeRobot();

    foreach ($mergeRobots as $robot) {
        if (!$robot instanceof AbstractRobot) {
            throw new Exception('Robot must be instance of AbstractRobot');
        }

        $mergeRobot->addRobot($robot);
    }

    return $mergeRobot;
}



$factory = new FactoryRobot();

$factory->addType(new Robot1());
$factory->addType(new Robot2());

//var_dump($factory->createRobot1(5));
//var_dump($factory->createRobot2(2));

$mergeRobot = new MergeRobot();
$mergeRobot->addRobot(new Robot2());
$mergeRobot->addRobot($factory->createRobot2(2));
$factory->addType($mergeRobot);

$res = resett($factory->createMergeRobot(2));

echo $res->getSpeed();

echo $res->getWeight();
