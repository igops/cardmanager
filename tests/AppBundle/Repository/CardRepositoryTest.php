<?php

namespace AppBundle\Repository;

use AppBundle\DTO\CardSearchCriteria;
use AppBundle\Entity\Card;
use AppBundle\Entity\Decorators\ExpirableCard;
use AppBundle\Services\CardGenerator;
use AppBundle\Services\CardNumberGenerator;
use AppBundle\Utils\DateTimeUtils;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CardRepositoryTest extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /** @var CardRepository */
    private $cardRepository;

    /** @var CardGenerator */
    private $cardGenerator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->cardRepository = $this->em->getRepository('AppBundle:Card');

        /** @var CardNumberGenerator|\PHPUnit_Framework_MockObject_MockObject $dummyNumberGenerator */
        $dummyNumberGenerator = $this->createMock(CardNumberGenerator::class);
        $dummyNumberGenerator->expects($this->any())
            ->method('generate')
            ->willReturn('1234567890123456');

        $this->cardGenerator = new CardGenerator($dummyNumberGenerator, $this->em);

        $this->deleteAllCards();
    }


    public function testCardGenerator()
    {
        $this->cardGenerator->generateCards('AA1234', self::getDateNY2019(), 25);

        $cardsAdded = (int)$this->cardRepository->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        self::assertEquals(25, $cardsAdded);

        $this->deleteAllCards();
    }


    public function testFindBySeries()
    {
        $this->cardGenerator->generateCards('AA1234', self::getDateNY2019(), 1);
        $this->cardGenerator->generateCards('AAAA34', self::getDateNY2019(), 1);
        $this->cardGenerator->generateCards('12AA34', self::getDateNY2019(), 1);
        $this->cardGenerator->generateCards('BB1234', self::getDateNY2019(), 1);

        $criteria = (new CardSearchCriteria())->setSeriesMask('A*');
        $matched = $this->getResultSet($criteria);

        self::assertCount(2, $matched);

        $matchedSeries = $this->extractSeries($matched);

        self::assertContains('AA1234', $matchedSeries);
        self::assertContains('AAAA34', $matchedSeries);
        self::assertNotContains('12AA34', $matchedSeries);
        self::assertNotContains('BB1234', $matchedSeries);

        $this->deleteAllCards();
    }


    public function testFindByNumber()
    {
        $this->cardGenerator->generateCards('AA1234', self::getDateNY2019(), 5);

        $criteria = (new CardSearchCriteria())->setNumberMask('123456*');
        self::assertCount(5, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())->setNumberMask('124356*');
        self::assertCount(0, $this->getResultSet($criteria));

        $this->deleteAllCards();
    }


    public function testFindBySeriesAndNumber()
    {
        $this->cardGenerator->generateCards('AA1234', self::getDateNY2019(), 2);
        $this->cardGenerator->generateCards('BB5678', self::getDateNY2019(), 2);

        $criteria = (new CardSearchCriteria())
            ->setSeriesMask('AA*')
            ->setNumberMask('123456*');
        self::assertCount(2, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setSeriesMask('BB*')
            ->setNumberMask('123456*');
        self::assertCount(2, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setSeriesMask('AA*')
            ->setNumberMask('234567*');
        self::assertCount(0, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setSeriesMask('BB*')
            ->setNumberMask('234567*');
        self::assertCount(0, $this->getResultSet($criteria));

        $this->deleteAllCards();
    }


    public function testFindByReleasedAt()
    {
        $this->cardGenerator->generateCards('AAAAAA', self::getDateNY2019(), 1, self::getDateNY2019());
        $this->cardGenerator->generateCards('BBBBBB', self::getDateNY2019(), 1, self::getDateEaster2019());
        $this->cardGenerator->generateCards('CCCCCC', self::getDateNY2019(), 1, self::getDateSolstice2019());

        $criteria = (new CardSearchCriteria())
            ->setReleasedAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-25'));
        self::assertCount(3, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setReleasedAtTo(DateTimeUtils::fromSqlTimestamp('2020-05-25'));
        self::assertCount(3, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setReleasedAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setReleasedAtTo(DateTimeUtils::fromSqlTimestamp('2019-05-25'));
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(2, $matched);
        self::assertContains('AAAAAA', $series);
        self::assertContains('BBBBBB', $series);
        self::assertNotContains('CCCCCC', $series);

        $criteria = (new CardSearchCriteria())
            ->setReleasedAtFrom(DateTimeUtils::fromSqlTimestamp('2019-05-25'))
            ->setReleasedAtTo(DateTimeUtils::fromSqlTimestamp('2020-05-25'));
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertNotContains('AAAAAA', $series);
        self::assertNotContains('BBBBBB', $series);
        self::assertContains('CCCCCC', $series);

        $this->deleteAllCards();
    }


    public function testFindByExpiresAt()
    {
        $this->cardGenerator->generateCards('AAAAAA', self::getDateNY2019(), 1);
        $this->cardGenerator->generateCards('BBBBBB', self::getDateEaster2019(), 1);
        $this->cardGenerator->generateCards('CCCCCC', self::getDateSolstice2019(), 1);

        $criteria = (new CardSearchCriteria())
            ->setExpiresAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-25'));
        self::assertCount(3, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setExpiresAtTo(DateTimeUtils::fromSqlTimestamp('2020-05-25'));
        self::assertCount(3, $this->getResultSet($criteria));

        $criteria = (new CardSearchCriteria())
            ->setExpiresAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAtTo(DateTimeUtils::fromSqlTimestamp('2019-05-25'));
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(2, $matched);
        self::assertContains('AAAAAA', $series);
        self::assertContains('BBBBBB', $series);
        self::assertNotContains('CCCCCC', $series);

        $criteria = (new CardSearchCriteria())
            ->setExpiresAtFrom(DateTimeUtils::fromSqlTimestamp('2019-05-25'))
            ->setExpiresAtTo(DateTimeUtils::fromSqlTimestamp('2020-05-25'));
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertNotContains('AAAAAA', $series);
        self::assertNotContains('BBBBBB', $series);
        self::assertContains('CCCCCC', $series);

        $this->deleteAllCards();
    }


    public function testFindByCardStatus()
    {
        $c1 = (new Card())
            ->setSeries('AAAAAA')
            ->setNumber('1234567890123456')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateNY2019())
            ->setActivated(true)
        ;
        $this->em->persist($c1);

        $c1 = (new Card())
            ->setSeries('BBBBBB')
            ->setNumber('1234567890123456')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateEaster2019())
            ->setActivated(true)
        ;
        $this->em->persist($c1);

        $c1 = (new Card())
            ->setSeries('CCCCCC')
            ->setNumber('1234567890123456')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateSolstice2019())
            ->setActivated(false)
        ;
        $this->em->persist($c1);

        $this->em->flush();

        // note that release date does not affect status
        $criteria = (new CardSearchCriteria())
            ->setObservedAt(DateTimeUtils::fromSqlTimestamp('2018-05-01'))
            ->setStatus(ExpirableCard::STATUS_ACTIVE);
        /** @var ExpirableCard[] $matched */
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(2, $matched);
        self::assertContains('AAAAAA', $series);    // active
        self::assertContains('BBBBBB', $series);    // active
        self::assertNotContains('CCCCCC', $series); // inactive
        // card statuses must be consistent with the result
        self::assertEquals(ExpirableCard::STATUS_ACTIVE, $matched[0]->getStatus());
        self::assertEquals(ExpirableCard::STATUS_ACTIVE, $matched[1]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(DateTimeUtils::fromSqlTimestamp('2018-12-31'))
            ->setStatus(ExpirableCard::STATUS_ACTIVE);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(2, $matched);
        self::assertContains('AAAAAA', $series);    // active
        self::assertContains('BBBBBB', $series);    // active
        self::assertNotContains('CCCCCC', $series); // inactive
        self::assertEquals(ExpirableCard::STATUS_ACTIVE, $matched[0]->getStatus());
        self::assertEquals(ExpirableCard::STATUS_ACTIVE, $matched[1]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateNY2019())
            ->setStatus(ExpirableCard::STATUS_ACTIVE);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertNotContains('AAAAAA', $series); // expired
        self::assertContains('BBBBBB', $series);    // active
        self::assertNotContains('CCCCCC', $series); // inactive
        self::assertEquals(ExpirableCard::STATUS_ACTIVE, $matched[0]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateEaster2019())
            ->setStatus(ExpirableCard::STATUS_ACTIVE);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(0, $matched);
        self::assertNotContains('AAAAAA', $series); // expired
        self::assertNotContains('BBBBBB', $series); // expired
        self::assertNotContains('CCCCCC', $series); // inactive

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateEaster2019())
            ->setStatus(ExpirableCard::STATUS_INACTIVE);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertNotContains('AAAAAA', $series); // expired
        self::assertNotContains('BBBBBB', $series); // expired
        self::assertContains('CCCCCC', $series);    // inactive
        self::assertEquals(ExpirableCard::STATUS_INACTIVE, $matched[0]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateSolstice2019())
            ->setStatus(ExpirableCard::STATUS_INACTIVE);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(0, $matched);
        self::assertNotContains('AAAAAA', $series); // expired
        self::assertNotContains('BBBBBB', $series); // expired
        self::assertNotContains('CCCCCC', $series); // expired

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateNY2019())
            ->setStatus(ExpirableCard::STATUS_EXPIRED);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertContains('AAAAAA', $series);    // expired
        self::assertNotContains('BBBBBB', $series); // active
        self::assertNotContains('CCCCCC', $series); // inactive
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[0]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateEaster2019())
            ->setStatus(ExpirableCard::STATUS_EXPIRED);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(2, $matched);
        self::assertContains('AAAAAA', $series);    // expired
        self::assertContains('BBBBBB', $series);    // expired
        self::assertNotContains('CCCCCC', $series); // inactive
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[0]->getStatus());
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[1]->getStatus());

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateSolstice2019())
            ->setStatus(ExpirableCard::STATUS_EXPIRED);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(3, $matched);
        self::assertContains('AAAAAA', $series); // expired
        self::assertContains('BBBBBB', $series); // expired
        self::assertContains('CCCCCC', $series); // expired
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[0]->getStatus());
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[1]->getStatus());
        self::assertEquals(ExpirableCard::STATUS_EXPIRED, $matched[2]->getStatus());

        $this->deleteAllCards();
    }


    public function testFindByMultipleCriteria()
    {
        $c1 = (new Card())
            ->setSeries('AAAAAA')
            ->setNumber('1234567890123456')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateNY2019())
            ->setActivated(true);
        $this->em->persist($c1);

        $c1 = (new Card())
            ->setSeries('BBBBBB')
            ->setNumber('3456789012345678')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateEaster2019())
            ->setActivated(true);
        $this->em->persist($c1);

        $c1 = (new Card())
            ->setSeries('CCCCCC')
            ->setNumber('5678901234567890')
            ->setBalance(0)
            ->setReleasedAt(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAt(self::getDateSolstice2019())
            ->setActivated(false);
        $this->em->persist($c1);

        $this->em->flush();

        $criteria = (new CardSearchCriteria())
            ->setObservedAt(self::getDateEaster2019())
            ->setSeriesMask('BB*')
            ->setNumberMask('345678*')
            ->setReleasedAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-01'))
            ->setReleasedAtTo(DateTimeUtils::fromSqlTimestamp('2019-05-25'))
            ->setExpiresAtFrom(DateTimeUtils::fromSqlTimestamp('2018-05-25'))
            ->setExpiresAtTo(DateTimeUtils::fromSqlTimestamp('2020-05-25'))
            ->setStatus(ExpirableCard::STATUS_EXPIRED);
        $matched = $this->getResultSet($criteria);
        $series = $this->extractSeries($matched);
        self::assertCount(1, $matched);
        self::assertNotContains('AAAAAA', $series);
        self::assertContains('BBBBBB', $series);
        self::assertNotContains('CCCCCC', $series);
    }

    /**
     * {@inheritDoc}
     */
    protected
    function tearDown()
    {
        parent::tearDown();

        $this->deleteAllCards();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    private function deleteAllCards(): void
    {
        $this->cardRepository->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * @param $criteria
     * @return array|\Traversable
     */
    private function getResultSet($criteria)
    {
        return $this->cardRepository->findByCriteria($criteria)->getCurrentPageResults();
    }

    /**
     * @param $matched
     * @return array
     */
    private function extractSeries($matched): array
    {
        return array_map(function (ExpirableCard $card) {
            return $card->getCard()->getSeries();
        }, iterator_to_array($matched));
    }

    /**
     * @return \DateTime
     */
    private static function getDateNY2019()
    {
        return DateTimeUtils::fromSqlTimestamp('2019-01-01');
    }

    /**
     * @return \DateTime
     */
    private static function getDateEaster2019()
    {
        return DateTimeUtils::fromSqlTimestamp('2019-04-21 12:00:00');
    }

    /**
     * @return \DateTime
     */
    private static function getDateSolstice2019()
    {
        return DateTimeUtils::fromSqlTimestamp('2019-12-22 04:19:00');
    }
}
