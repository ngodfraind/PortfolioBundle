<?php

namespace Icap\PortfolioBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Icap\PortfolioBundle\Entity\Portfolio;
use Icap\PortfolioBundle\Entity\Widget\AbstractWidget;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactory;

/**
 * @DI\Service("icap_portfolio.manager.widgets")
 */
class WidgetsManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templatingEngine;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /** @var array */
    protected $widgetsConfig = null;

    /**
     * Constructor.
     *
     * @DI\InjectParams({
     *     "entityManager"     = @DI\Inject("doctrine.orm.entity_manager"),
     *     "templatingEngine"  = @DI\Inject("templating"),
     *     "formFactory"       = @DI\Inject("form.factory")
     * })
     */
    public function __construct(EntityManager $entityManager, EngineInterface $templatingEngine, FormFactory $formFactory)
    {
        $this->entityManager    = $entityManager;
        $this->templatingEngine = $templatingEngine;
        $this->formFactory      = $formFactory;
    }

    /**
     * @return array
     */
    public function getWidgetsConfig()
    {
        $widgetsConfig = $this->widgetsConfig;

        if (null === $this->widgetsConfig) {
            $widgetTypes = $this->entityManager->getRepository('IcapPortfolioBundle:Widget\WidgetType')->findAllInArray();

            $sortedWidgetTypes = array();

            foreach ($widgetTypes as $widgetType) {
                $sortedWidgetTypes[$widgetType['name']] = $widgetType;
            }

            $this->widgetsConfig = $sortedWidgetTypes;
            $widgetsConfig = $this->widgetsConfig;
        }

        return $widgetsConfig;
    }

    /**
     * @param string $widgetType
     *
     * @return bool
     */
    public function isWidgetTypeUnique($widgetType)
    {
        $widgetTypeIsUnique = false;

        $widgetsConfig = $this->getWidgetsConfig();

        if (isset($widgetsConfig[$widgetType]) && $widgetsConfig[$widgetType]['isUnique']) {
            $widgetTypeIsUnique = true;
        }

        return $widgetTypeIsUnique;
    }

    /**
     * @param AbstractWidget $widget
     * @param string         $type
     *
     * @return string
     */
    public function getView(AbstractWidget $widget, $type)
    {
        return $this->templatingEngine->render('IcapPortfolioBundle:templates:' . $type . '.html.twig', array('widget' => $widget));
    }

    /**
     * @param string $type
     * @param string $action
     *
     * @return string
     */
    public function getFormView($type, $action)
    {
        return $this->templatingEngine->render('IcapPortfolioBundle:templates/' . $action . ':' . $type . '.html.twig');
    }

    /**
     * @param string $type
     * @param object $data
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm($type, $data)
    {
        return $this->formFactory->create('icap_portfolio_widget_form_' . $type, $data);
    }

    /**
     * @param AbstractWidget $widget
     * @param string         $type
     * @param array          $parameters
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public function handle(AbstractWidget $widget, $type, array $parameters)
    {
        $originalChildren = new ArrayCollection();

        foreach ($widget->getChildren() as $child) {
            $originalChildren->add($child);
        }

        $data = array();

        $form = $this->getForm($type, $widget);
        $form->submit($parameters);

        if ($form->isValid()) {
            $newChildren = $widget->getChildren();
            foreach ($originalChildren as $child) {
                if (!$newChildren->contains($child)) {
                     $this->entityManager->remove($child);
                }
            }

            $this->entityManager->persist($widget);
            $this->entityManager->flush();

            $widgetDatas = array(
                'type'   => $widget->getWidgetType(),
                'column' => $widget->getColumn(),
                'row'    => $widget->getRow(),
                'views'  => array(
                    'view' => $this->getView($widget, $type)
                )
            );
            $data = $widgetDatas + $widget->getData();

            return $data;
        }
        echo "<pre>";
        var_dump($form->getErrors());
        echo "</pre>" . PHP_EOL;
        echo "<pre>";
        var_dump($form->getErrorsAsString());
        echo "</pre>" . PHP_EOL;
        die("FFFFFUUUUUCCCCCKKKKK" . PHP_EOL);

        throw new \InvalidArgumentException();
    }

    /**
     * @param Portfolio $portfolio
     * @param string    $type
     *
     * @throws \InvalidArgumentException
     * @return AbstractWidget
     */
    public function getNewWidget(Portfolio $portfolio, $type)
    {
        $widgetsConfig = $this->getWidgetsConfig();

        if (isset($widgetsConfig[$type])) {
            $widgetNamespace = sprintf('Icap\PortfolioBundle\Entity\Widget\%sWidget', ucfirst($type));
            /** @var \Icap\PortfolioBundle\Entity\Widget\AbstractWidget $widget */
            $widget = new $widgetNamespace();
            $widget->setPortfolio($portfolio);

            return $widget;
        }

        throw new \InvalidArgumentException("Unknown type of widget.");
    }

    /**
     * @param AbstractWidget $widget
     */
    public function deleteWidget(AbstractWidget $widget)
    {
        $this->entityManager->remove($widget);
        $this->entityManager->flush();
    }
}
 