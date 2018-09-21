<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="purchase")
 */
class Purchase
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Card
     *
     * @ORM\ManyToOne(targetEntity="Card", inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * Purchase cost in cents
     * Adding a purchase to a card does not affect a balance
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     * @return Purchase
     */
    public function setCard(Card $card): Purchase
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Purchase
     */
    public function setDescription(string $description): Purchase
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     * @return Purchase
     */
    public function setCost(int $cost): Purchase
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Purchase
     */
    public function setCreatedAt(\DateTime $createdAt): Purchase
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
