<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 * @ORM\Table(
 *     name="card",
 *     indexes={
 *         @Index(columns={"series"}, flags={"fulltext"}),
 *         @Index(columns={"number"}, flags={"fulltext"}),
 *         @Index(columns={"released_at"}),
 *         @Index(columns={"expires_at"})
 *     })
 * )
 */
class Card
{
    public const SERIES_LENGTH = 6;
    public const NUMBER_LENGTH = 16;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=Card::SERIES_LENGTH)
     */
    private $series;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=Card::NUMBER_LENGTH)
     */
    private $number;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $releasedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * Adding a purchase does not affect this value
     *
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastPurchaseAt;

    /**
     * Available balance in cents
     * Adding a purchase does not affect this value
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $balance;

    /**
     * @var Purchase[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="Purchase",
     *      mappedBy="card",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * @ORM\OrderBy({"createdAt": "DESC"})
     */
    private $purchases;

    public function __construct()
    {
        $this->releasedAt = new \DateTime();
        $this->expiresAt = new \DateTime();
        $this->purchases = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * @param string $series
     * @return Card
     */
    public function setSeries(string $series): Card
    {
        $this->series = $series;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Card
     */
    public function setNumber(string $number): Card
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReleasedAt(): \DateTime
    {
        return $this->releasedAt;
    }

    /**
     * @param \DateTime $releasedAt
     * @return Card
     */
    public function setReleasedAt(\DateTime $releasedAt): Card
    {
        $this->releasedAt = $releasedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     * @return Card
     */
    public function setExpiresAt(\DateTime $expiresAt): Card
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     * @return Card
     */
    public function setActivated(bool $activated): Card
    {
        $this->activated = $activated;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastPurchaseAt()
    {
        return $this->lastPurchaseAt;
    }

    /**
     * @param \DateTime|null $lastPurchaseAt
     * @return Card
     */
    public function setLastPurchaseAt($lastPurchaseAt)
    {
        $this->lastPurchaseAt = $lastPurchaseAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     * @return Card
     */
    public function setBalance(int $balance): Card
    {
        $this->balance = $balance;
        return $this;
    }
    /**
     * @return Purchase[]|Collection
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param Purchase $purchase
     */
    public function addPurchase(Purchase $purchase): void
    {
        $purchase->setCard($this);
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
        }
    }

    /**
     * @param Purchase $purchase
     */
    public function removePurchase(Purchase $purchase): void
    {
        $purchase->setCard(null);
        $this->purchases->removeElement($purchase);
    }
}
