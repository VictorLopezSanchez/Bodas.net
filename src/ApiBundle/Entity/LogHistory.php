<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LogHistory.
 *
 * @ORM\Table(name="log_history")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\LogHistoryRepository")
 */
class LogHistory
{

    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name_simulator;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $cardinal_point;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $num_call;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $path;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNameSimulator()
    {
        return $this->name_simulator;
    }

    /**
     * @param string $name_simulator
     */
    public function setNameSimulator($name_simulator)
    {
        $this->name_simulator = $name_simulator;
    }

    /**
     * @return string
     */
    public function getCardinalPoint()
    {
        return $this->cardinal_point;
    }

    /**
     * @param string $cardinal_point
     */
    public function setCardinalPoint($cardinal_point)
    {
        $this->cardinal_point = $cardinal_point;
    }

    /**
     * @return int
     */
    public function getNumCall()
    {
        return $this->num_call;
    }

    /**
     * @param int $num_call
     */
    public function setNumCall($num_call)
    {
        $this->num_call = $num_call;
    }

    /**
     * @return int
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param int $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}
