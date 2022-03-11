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
            ->addChild('product-import', ['route' => 'custom_admin_product_import_index'])
            ->setLabelAttribute('icon', 'upload')
            ->setLabel('Product import');

        $exportSubmenu = $menu
            ->addChild('export')
            ->setLabel('Export');
        $exportSubmenu
            ->addChild('product-export')
            ->setLabelAttribute('icon', 'download')
            ->setLabel('Product export');
    }
}
