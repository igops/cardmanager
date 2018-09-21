<?php

namespace AppBundle\DTO;

class CardSearchCriteria
{
    /**
     * Observation date affects calculation of a status.
     * Default value is a current time.
     *
     * @var \DateTime
     */
    private $observedAt;

    /**
     * @var string|null
     */
    private $seriesMask;

    /**
     * @var string|null
     */
    private $numberMask;

    /**
     * @var \DateTime|null
     */
    private $releasedAtFrom;

    /**
     * @var \DateTime|null
     */
    private $releasedAtTo;

    /**
     * @var \DateTime|null
     */
    private $expiresAtFrom;

    /**
     * @var \DateTime|null
     */
    private $expiresAtTo;

    /**
     * @var int|null
     */
    private $status;

    /**
     * ExpirableCardSearchCriteria constructor.
     */
    public function __construct()
    {
        $this->observedAt = new \DateTime();
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
     * @return CardSearchCriteria
     */
    public function setObservedAt(\DateTime $observedAt): CardSearchCriteria
    {
        $this->observedAt = $observedAt;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSeriesMask()
    {
        return $this->seriesMask;
    }

    /**
     * @param null|string $seriesMask
     * @return CardSearchCriteria
     */
    public function setSeriesMask($seriesMask)
    {
        $this->seriesMask = $seriesMask;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNumberMask()
    {
        return $this->numberMask;
    }

    /**
     * @param null|string $numberMask
     * @return CardSearchCriteria
     */
    public function setNumberMask($numberMask)
    {
        $this->numberMask = $numberMask;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleasedAtFrom()
    {
        return $this->releasedAtFrom;
    }

    /**
     * @param \DateTime|null $releasedAtFrom
     * @return CardSearchCriteria
     */
    public function setReleasedAtFrom($releasedAtFrom)
    {
        $this->releasedAtFrom = $releasedAtFrom;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleasedAtTo()
    {
        return $this->releasedAtTo;
    }

    /**
     * @param \DateTime|null $releasedAtTo
     * @return CardSearchCriteria
     */
    public function setReleasedAtTo($releasedAtTo)
    {
        $this->releasedAtTo = $releasedAtTo;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAtFrom()
    {
        return $this->expiresAtFrom;
    }

    /**
     * @param \DateTime|null $expiresAtFrom
     * @return CardSearchCriteria
     */
    public function setExpiresAtFrom($expiresAtFrom)
    {
        $this->expiresAtFrom = $expiresAtFrom;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAtTo()
    {
        return $this->expiresAtTo;
    }

    /**
     * @param \DateTime|null $expiresAtTo
     * @return CardSearchCriteria
     */
    public function setExpiresAtTo($expiresAtTo)
    {
        $this->expiresAtTo = $expiresAtTo;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     * @return CardSearchCriteria
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}