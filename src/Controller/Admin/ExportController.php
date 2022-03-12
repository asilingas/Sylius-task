<?php

namespace App\Controller\Admin;

use App\Service\ExportService;
use App\Message\ExportRequest;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/export")
 */
class ExportController extends AbstractController
{
    private ExportService $exportService;

    private MessageBusInterface $messageBus;

    public function __construct(ExportService $exportService, MessageBusInterface $messageBus)
    {
        $this->exportService = $exportService;
        $this->messageBus = $messageBus;
    }

    /**
     * Shows export data request form and former export file list.
     *
     * @Route("/{type}", name="custom_admin_export_index", methods={"GET"})
     */
    public function indexAction(Request $request, string $type): Response
    {
        return $this->render('admin/export/index.html.twig', ['type' => $type]);
    }

    /**
     * Dispatches an export request.
     *
     * @Route("/request/{type}", name="custom_admin_export_request", methods={"GET"})
     */
    public function exportAction(Request $request, string $type): Response
    {
        $export = $this->exportService->create($this->getUser(), $type);
        $this->messageBus->dispatch(new ExportRequest($export->getId()));
        $this->addFlash('success', sprintf('Export file %s is being generated.', $export->getFilename()));

        return $this->redirectToRoute('custom_admin_export_index', ['type' => $type]);
    }
}
