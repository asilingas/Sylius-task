<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/import")
 */
class ImportController extends AbstractController
{
    /**
     * @Route("/product", name="custom_admin_product_import_index", methods={"GET"})
     */
    public function productAction(Request $request): Response
    {
        return $this->render('admin/import/index.html.twig');
    }
}
