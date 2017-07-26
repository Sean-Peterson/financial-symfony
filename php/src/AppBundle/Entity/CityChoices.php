<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CityChoices
 *
 * @ORM\Table(name="city_choices")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityChoicesRepository")
 */
class CityChoices
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
     * @var array
     *
     * @ORM\Column(name="cities", type="array", nullable=true)
     */
    private $cities;


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
     * Set cities
     *
     * @param array $cities
     *
     * @return CityChoices
     */
    public function setCities($cities)
    {
        $this->cities = $cities;

        return $this;
    }

    /**
     * Get cities
     *
     * @return array
     */
    public function getCities()
    {
        return $this->cities;
    }
}

