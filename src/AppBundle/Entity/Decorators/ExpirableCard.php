<?php

namespace AppBundle\Entity\Decorators;

use AppBundle\Entity\Card;

class ExpirableCard
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;
    public const STATUS_EXPIRED = 2;

    /**
     * @var Card
     */
    private $card;

    /**
     * @var \DateTime
     */
    private $observedAt;

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     * @return ExpirableCard
     */
    public function setCard(Card $card): ExpirableCard
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getObservedAt(): \DateTime
    {
        return $this->observedAt;
    }

    /**
     * @param \DateTime $observedAt
     * @return ExpirableCard
     */
    public function setObservedAt(\DateTime $observedAt): ExpirableCard
    {
        $this->observedAt = $observedAt;
        return $this;
    }

    /**
     * Returns status depending on a datetime we observe a card.
     * Note that release date does not affect status.
     * Please be consistent with CardRepository::findByCriteria.
     *
     * @return int
     */
    public function getStatus()
    {
        if ($this->observedAt >= $this->card->getExpiresAt()) {
            return self::STATUS_EXPIRED;
        }
        return $this->card->isActivated() ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
    }

    // Adapting all getters of a card
    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->card->getSeries();
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->card->getNumber();
    }

    /**
     * @return \DateTime
     */
    public function getReleasedAt(): \DateTime
    {
        return $this->card->getReleasedAt();
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->card->getExpiresAt();
    }

}