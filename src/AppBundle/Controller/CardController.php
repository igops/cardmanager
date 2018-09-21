<?php

namespace AppBundle\Controller;

use AppBundle\DTO\CardSearchCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, defaults={"page": "1", "_format"="html"}, name="cardlist")
     * @Route("/page/{page}", methods={"GET"}, defaults={"_format"="html"}, name="cardlist_paginated", requirements={"page"="\d+"})
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function cardListAction(Request $request, int $page = 1)
    {
        $criteriaSeries = $request->query->get('_series') ?? '';
        $criteriaNumber = $request->query->get('_number') ?? '';
        $criteriaReleasedFrom = $request->query->get('_releasedFrom') ?? '';
        $criteriaReleasedTo = $request->query->get('_releasedTo') ?? '';
        $criteriaExpiresFrom = $request->query->get('_expiresFrom') ?? '';
        $criteriaExpiredTo = $request->query->get('_expiresTo') ?? '';
        $criteriaStatus = $request->query->get('_status') ?? '';


        $criteria = new CardSearchCriteria();
        if (!empty($criteriaSeries)) {
            $criteria->setSeriesMask($criteriaSeries);
        }
        if (!empty($criteriaNumber)) {
            $criteria->setNumberMask($criteriaNumber);
        }
        if (!empty($criteriaReleasedFrom)) {
            $criteria->setReleasedAtFrom($criteriaReleasedFrom);
        }
        if (!empty($criteriaReleasedTo)) {
            $criteria->setReleasedAtTo($criteriaReleasedTo);
        }
        if (!empty($criteriaExpiresFrom)) {
            $criteria->setExpiresAtFrom($criteriaExpiresFrom);
        }
        if (!empty($criteriaExpiredTo)) {
            $criteria->setExpiresAtTo($criteriaExpiredTo);
        }
        if (!empty($criteriaStatus)) {
            $criteria->setStatus($criteriaStatus);
        }

        $cards = $this->getDoctrine()->getRepository('AppBundle:Card')->findByCriteria(
            $criteria, $page
        );
        return $this->render('card/list.html.twig', [
            'cards' => $cards,
        ]);
    }
}
