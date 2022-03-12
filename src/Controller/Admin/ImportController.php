<?php

namespace App\Controller\Admin;

use App\Form\Type\Admin\UploadFileType;
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
     * Show file upload form link and previous import list.
     *
     * @Route("/{type}", name="custom_admin_import_index", methods={"GET"})
     */
    public function indexAction(Request $request, string $type): Response
    {
        return $this->render('admin/import/index.html.twig', ['type' => $type]);
    }

    /**
     * File upload form.
     *
     * @Route("/upload/{type}", name="custom_admin_import_upload", methods={"GET", "POST"})
     */
    public function uploadAction(Request $request, string $type): Response
    {
        $form = $this->createForm(UploadFileType::class);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'File upload successful. Importing items.');

                $this->redirectToRoute('custom_admin_import_index', ['type' => $type]);
            }
        }

        return $this->render(
            'admin/import/upload.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type
            ]
        );
    }
}
