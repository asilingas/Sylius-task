<?php

namespace App\Controller\Admin;

use App\Entity\Export\Export;
use App\Repository\ExportRepository;
use App\Service\ExportService;
use App\Message\ExportRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/export")
 */
class ExportController extends AbstractController
{
    private ExportRepository $exportRepository;

    private ExportService $exportService;

    private MessageBusInterface $messageBus;

    public function __construct(
        ExportRepository $exportRepository,
        ExportService $exportService,
        MessageBusInterface $messageBus
    ) {
        $this->exportRepository = $exportRepository;
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
        $exports = $this->exportRepository->getForUser($this->getUser());

        return $this->render(
            'admin/export/index.html.twig',
            [
                'type' => $type,
                'exports' => $exports,
            ]
        );
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

    /**
     * Serves export file.
     *
     * @Route("/download/{guid}", name="custom_admin_export_download", methods={"GET"})
     * @ParamConverter("export", class="App\Entity\Export\Export")
     */
    public function downloadAction(Export $export): BinaryFileResponse
    {
        $response = new BinaryFileResponse('export/' . $export->getFilename());
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $export->getFilename()
        );

        return $response;
    }

    /**
     * Data for progress bar.
     * TODO move to FOS REST or API Platform
     *
     * @Route("/progress/{id}", name="custom_admin_export_progress", methods={"GET"})
     * @ParamConverter("export", class="App\Entity\Export\Export")
     */
    public function progressAction(Export $export): JsonResponse
    {
        return new JsonResponse(
            [
                'processed' => $export->getProcessedItemCount(),
                'total' => $export->getTotalItemCount(),
            ]
        );
    }
}
