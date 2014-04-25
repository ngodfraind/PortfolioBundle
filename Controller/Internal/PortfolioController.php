<?php

namespace Icap\PortfolioBundle\Controller\Internal;

use Claroline\CoreBundle\Entity\User;
use Icap\PortfolioBundle\Controller\Controller as BaseController;
use Icap\PortfolioBundle\Entity\Portfolio;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/internal")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/portfolio/{id}", name="icap_portfolio_internal_portfolio", options={"expose"=true})
     * @Method({"GET"})
     *
     * @ParamConverter("loggedUser", options={"authenticatedUser" = true})
     */
    public function getAction(User $loggedUser, Portfolio $portfolio)
    {
        $response = new JsonResponse();

        /** @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $twigEngine */
        $twigEngine = $this->get('templating');

        $data = array(
            'id'    => $portfolio->getId(),
            'slug'  => $portfolio->getSlug(),
            'title' => array(
                'view'  => $twigEngine->render('IcapPortfolioBundle:templates:title.html.twig', array('portfolioTitle' => $portfolio->getTitle())),
                'value' => $portfolio->getTitle()
            ),
            'userInformation' => array(
                'view'   => 'pouet',
                'avatar' => $loggedUser->getPicture()
            )
        );

        $response->setData($data);

        return $response;
    }
}
 