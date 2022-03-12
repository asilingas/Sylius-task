<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/export")
 */
class ExportController extends AbstractController
{
    /**
     * @Route("/{type}", name="custom_admin_export_index", methods={"GET"})
     */
    public function indexAction(Request $request, string $type): Response
    {
        return $this->render('admin/export/index.html.twig');
    }
}
