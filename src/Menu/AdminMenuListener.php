<?php

namespace App\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    // Custom menu items
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $importSubmenu = $menu
            ->addChild('import')
            ->setLabel('Import');
        $importSubmenu
            ->addChild(
                'product-import',
                [
                    'route' => 'custom_admin_import_index',
                    'routeParameters' => ['type' => 'product'],
                ]
            )
            ->setLabelAttribute('icon', 'upload')
            ->setLabel('Product import');

        $exportSubmenu = $menu
            ->addChild('export')
            ->setLabel('Export');
        $exportSubmenu
            ->addChild(
                'product-export',
                [
                    'route' => 'custom_admin_export_index',
                    'routeParameters' => ['type' => 'product'],
                ]
            )
            ->setLabelAttribute('icon', 'download')
            ->setLabel('Product export');
    }
}
