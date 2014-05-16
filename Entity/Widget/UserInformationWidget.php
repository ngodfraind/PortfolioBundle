<?php

namespace Icap\PortfolioBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;

/**
 * This entity is only an AbstractResource sub-type, with no additional attributes.
 *
 * @ORM\Entity
 * @ORM\Table(name="icap__portfolio_widget_user_information")
 */
class UserInformationWidget extends AbstractWidget
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;
}