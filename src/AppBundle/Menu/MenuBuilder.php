<?php
/**
 * Created by PhpStorm.
 * User: springer
 * Date: 30.1.15
 * Time: 21:46
 */
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('Pridat auto', array('route' => 'vehicleCreate'));
        $menu->addChild('Prihlasit se', array('route' => 'vehicleCreate'));
        // ... add more children

        return $menu;
    }
}