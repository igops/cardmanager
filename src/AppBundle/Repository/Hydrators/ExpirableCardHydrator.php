<?php

namespace AppBundle\Repository\Hydrators;

use AppBundle\Entity\Card;
use AppBundle\Entity\Decorators\ExpirableCard;
use AppBundle\Utils\DateTimeUtils;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;

class ExpirableCardHydrator extends ObjectHydrator
{
    /**
     * @inheritDoc
     */
    protected function hydrateRowData(array $row, array &$result)
    {
        $hydrated = [];
        parent::hydrateRowData($row, $hydrated);

        $rowKeys = array_keys($row);
        $observedAtKey = end($rowKeys);

        /** @var Card $card */
        $card = $hydrated[0][0];

        $expirable = (new ExpirableCard())
            ->setCard($card)
            ->setObservedAt(DateTimeUtils::fromSqlTimestamp($row[$observedAtKey]));
        ;

        $result[] = $expirable;
    }


}