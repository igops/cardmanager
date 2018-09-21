<?php

namespace AppBundle\Repository;

use AppBundle\DTO\CardSearchCriteria;
use AppBundle\Repository\Utils\FindByCriteriaQueryBuilder;
use AppBundle\Repository\Utils\PaginatorUtil;
use AppBundle\Utils\DateTimeUtils;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;

class CardRepository extends EntityRepository
{
    /**
     * Find cards by matching text fields, date ranges and statuses.
     * Expiration status depends on a date we observe a given card.
     * Activation status is just fetched from DB.
     * Note that release date does not affect status.
     * Please be consistent with ExpirableCard::getStatus.

     * @param CardSearchCriteria $searchCriteria
     * @param int $page
     * @return Pagerfanta
     */
    public function findByCriteria(CardSearchCriteria $searchCriteria, int $page = 1): Pagerfanta
    {
        // join observedAtTime to hydrate it later
        $observedAtTime = DateTimeUtils::toSqlTimestamp($searchCriteria->getObservedAt());
        $qb = $this->createQueryBuilder('c')
            ->addSelect('\''.$observedAtTime.'\'');

        (new FindByCriteriaQueryBuilder($qb, $searchCriteria))
            ->addWhereClauses()
            ->addStatusWhereClause()
            ->addParams();

        $qb->orderBy('c.releasedAt', 'DESC');

        $query = $qb->getQuery();
        $query->setHydrationMode('expirable_card_hydrator');

        return PaginatorUtil::createPaginator($query, $page);
    }

    /**
     * Inserts a batch of cards.
     *
     * @param $cards
     * @param int $batchSize
     */
    public function batchInsert($cards, int $batchSize = 100)
    {
        $em = $this->getEntityManager();

        $i = 0;
        foreach ($cards as $card) {
            $em->persist($card);
            if (++$i % $batchSize === 0) {
                $em->flush();
            }
        }
        $em->flush();
    }
}