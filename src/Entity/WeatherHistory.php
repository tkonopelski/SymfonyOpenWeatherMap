<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeatherHistoryRepository")
 */
class WeatherHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="datetime")
     */
    private $added;

    /**
     * @ORM\Column(type="float")
     */
    private $temp;

    /**
     * @ORM\Column(type="integer")
     */
    private $pressure;

    /**
     * @ORM\Column(type="integer")
     */
    private $humidity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visibility;

    /**
     * @ORM\Column(type="float")
     */
    private $wind_speed;

    /**
     * @ORM\Column(type="integer")
     */
    private $clouds;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ua;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->added;
    }

    public function setAdded(\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getTemp(): ?float
    {
        return $this->temp;
    }

    public function setTemp(float $temp): self
    {
        $this->temp = $temp;

        return $this;
    }

    public function getPressure(): ?int
    {
        return $this->pressure;
    }

    public function setPressure(int $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(int $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->wind_speed;
    }

    public function setWindSpeed(float $wind_speed): self
    {
        $this->wind_speed = $wind_speed;

        return $this;
    }

    public function getClouds(): ?int
    {
        return $this->clouds;
    }

    public function setClouds(int $clouds): self
    {
        $this->clouds = $clouds;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUa(): ?string
    {
        return $this->ua;
    }

    public function setUa(?string $ua): self
    {
        $this->ua = $ua;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
