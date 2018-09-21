<?php

namespace AppBundle\Repository\Utils;

use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaginatorUtil
{
    const ITEMS_PER_PAGE = 10;

    public static function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = (new Pagerfanta(new DoctrineORMAdapter($query)))
            ->setMaxPerPage(self::ITEMS_PER_PAGE)
            ->setCurrentPage($page);

        return $paginator;
    }
}