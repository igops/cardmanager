<?php

namespace AppBundle\Services;

use AppBundle\Entity\Card;
use AppBundle\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;

class CardGenerator
{
    private const DEFAULT_BALANCE = 1000;
    private const DEFAULT_ACTIVATED = false;

    /** @var CardNumberGenerator */
    private $cardNumberGenerator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * CardGenerator constructor.
     * @param CardNumberGenerator $cardNumberGenerator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CardNumberGenerator $cardNumberGenerator,
                                EntityManagerInterface $entityManager)
    {
        $this->cardNumberGenerator = $cardNumberGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $series
     * @param \DateTime $expiresAt
     * @param int $amount
     * @param \DateTime|null $releasedAt
     */
    public function generateCards($series, $expiresAt, $amount, $releasedAt = null)
    {
        $cards = [];

        $releasedAt = $releasedAt ?? new \DateTime();
        $releasedAtTime = $releasedAt->getTimestamp();

        $expiresAtTime = $expiresAt->getTimestamp();

        for ($i = 0; $i < $amount; $i++) {
            $cards[] = (new Card())
                ->setSeries($series)
                ->setNumber($this->cardNumberGenerator->generate(Card::NUMBER_LENGTH))
                ->setBalance(self::DEFAULT_BALANCE)
                ->setActivated(self::DEFAULT_ACTIVATED)
                ->setReleasedAt((new \DateTime())->setTimestamp($releasedAtTime))
                ->setExpiresAt((new \DateTime())->setTimestamp($expiresAtTime))
            ;
        }

        $this->entityManager->getRepository('AppBundle:Card')->batchInsert($cards);
    }


}