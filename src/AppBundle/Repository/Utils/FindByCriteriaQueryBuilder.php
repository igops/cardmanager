<?php

namespace AppBundle\Repository\Utils;

use AppBundle\DTO\CardSearchCriteria;
use AppBundle\Entity\Decorators\ExpirableCard;
use Doctrine\ORM\QueryBuilder;

class FindByCriteriaQueryBuilder
{
    /** @var QueryBuilder */
    private $queryBuilder;

    /** @var CardSearchCriteria */
    private $searchCriteria;

    /**
     * CardRepositorySearchBuilder constructor.
     * @param QueryBuilder $queryBuilder
     * @param CardSearchCriteria $searchCriteria
     */
    public function __construct(QueryBuilder $queryBuilder, CardSearchCriteria $searchCriteria)
    {
        $this->queryBuilder = $queryBuilder;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @return FindByCriteriaQueryBuilder
     */
    public function addWhereClauses(): FindByCriteriaQueryBuilder
    {
        $qb = $this->queryBuilder;
        $criteria = $this->searchCriteria;

        $qb->where('1=1');

        if ($criteria->getSeriesMask() !== null) {
            $qb->andWhere('MATCH_AGAINST(c.series) AGAINST(:seriesMask BOOLEAN) > 0');
        }
        if ($criteria->getNumberMask() !== null) {
            $qb->andWhere('MATCH_AGAINST(c.number) AGAINST(:numberMask BOOLEAN) > 0');
        }
        if ($criteria->getReleasedAtFrom() !== null) {
            $qb->andWhere('c.releasedAt >= :releasedAtFrom');
        }
        if ($criteria->getReleasedAtTo() !== null) {
            $qb->andWhere('c.releasedAt <= :releasedAtTo');
        }
        if ($criteria->getExpiresAtFrom() !== null) {
            $qb->andWhere('c.expiresAt >= :expiresAtFrom');
        }
        if ($criteria->getExpiresAtTo() !== null) {
            $qb->andWhere('c.expiresAt <= :expiresAtTo');
        }
        return $this;
    }

    /**
     * @return FindByCriteriaQueryBuilder
     */
    public function addStatusWhereClause(): FindByCriteriaQueryBuilder
    {
        $qb = $this->queryBuilder;
        $status = $this->searchCriteria->getStatus();

        if ($status !== null) {
            if ($status === ExpirableCard::STATUS_ACTIVE) {
                $qb
                    ->andWhere('c.activated = 1')
                    ->andWhere('c.expiresAt > :observedAt');
            } else {
                if ($status === ExpirableCard::STATUS_INACTIVE) {
                    $qb
                        ->andWhere('c.activated = 0')
                        ->andWhere('c.expiresAt > :observedAt');
                } else {
                    if ($status === ExpirableCard::STATUS_EXPIRED) {
                        $qb->andWhere('c.expiresAt <= :observedAt');
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @return FindByCriteriaQueryBuilder
     */
    public function addParams(): FindByCriteriaQueryBuilder
    {
        $qb = $this->queryBuilder;
        $criteria = $this->searchCriteria;

        if ($criteria->getSeriesMask() !== null) {
            $qb->setParameter('seriesMask', $criteria->getSeriesMask());
        }
        if ($criteria->getNumberMask() !== null) {
            $qb->setParameter('numberMask', $criteria->getNumberMask());
        }
        if ($criteria->getReleasedAtFrom() !== null) {
            $qb->setParameter('releasedAtFrom', $criteria->getReleasedAtFrom());
        }
        if ($criteria->getReleasedAtTo() !== null) {
            $qb->setParameter('releasedAtTo', $criteria->getReleasedAtTo());
        }
        if ($criteria->getExpiresAtFrom() !== null) {
            $qb->setParameter('expiresAtFrom', $criteria->getExpiresAtFrom());
        }
        if ($criteria->getExpiresAtTo() !== null) {
            $qb->setParameter('expiresAtTo', $criteria->getExpiresAtTo());
        }
        if ($criteria->getStatus() !== null) {
            $qb->setParameter('observedAt', $criteria->getObservedAt());
        }
        return $this;
    }
}