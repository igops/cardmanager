<?php

namespace AppBundle\DataFixtures;

use AppBundle\Services\CardGenerator;
use AppBundle\Utils\DateTimeUtils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CardFixtures extends Fixture
{

    /** @var CardGenerator */
    private $cardGenerator;

    /**
     * CardFixtures constructor.
     * @param CardGenerator $cardGenerator
     */
    public function __construct(CardGenerator $cardGenerator)
    {
        $this->cardGenerator = $cardGenerator;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->cardGenerator->generateCards('AA1234', DateTimeUtils::fromSqlTimestamp('2018-12-31'), 120);
    }
}