<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 */
class City
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var flaot
     *
     * @ORM\Column(name="col", type="float")
     */
    private $col;

    /**
     * @var int
     *
     * @ORM\Column(name="rent", type="integer")
     */
    private $rent;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return City
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return City
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set col
     *
     * @param \number $col
     *
     * @return City
     */
    public function setCol(\number $col)
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get col
     *
     * @return \number
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * Set rent
     *
     * @param \number $rent
     *
     * @return City
     */
    public function setRent(\number $rent)
    {
        $this->rent = $rent;

        return $this;
    }

    /**
     * Get rent
     *
     * @return \number
     */
    public function getRent()
    {
        return $this->rent;
    }
}
