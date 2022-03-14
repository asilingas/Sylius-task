<?php

namespace App\Controller\Admin;

use App\Entity\Custom\Export;
use App\Entity\Custom\Import;
use App\Form\Type\Admin\UploadFileType;
use App\Message\ImportRequest;
use App\Repository\ImportRepository;
use App\Service\FileUploader;
use App\Service\ImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/import")
 */
class ImportController extends AbstractController
{
    private ImportService $importService;

    private ImportRepository $importRepository;

    private FileUploader $fileUploader;

    private MessageBusInterface $messageBus;

    public function __construct(
        ImportService $importService,
        ImportRepository $importRepository,
        FileUploader $fileUploader,
        MessageBusInterface $messageBus
    ) {
        $this->importService = $importService;
        $this->importRepository = $importRepository;
        $this->fileUploader = $fileUploader;
        $this->messageBus = $messageBus;
    }

    /**
     * Show file upload form link and previous import list.
     *
     * @Route("/{type}", name="custom_admin_import_index", methods={"GET"})
     */
    public function indexAction(Request $request, string $type): Response
    {
        $imports = $this->importRepository->getForUser($this->getUser());

        return $this->render(
            'admin/import/index.html.twig',
            [
                'type' => $type,
                'imports' => $imports
            ]
        );
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
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->getData()['file'];
                $filename = $this->fileUploader->upload($uploadedFile, 'import');
                $import = $this->importService->create($this->getUser(), $filename, $type);
                $this->messageBus->dispatch(new ImportRequest($import->getId()));
//                $this->importService->process($import);

                $this->addFlash('success', sprintf('File %s uploaded. Importing items.', $filename));

                return $this->redirectToRoute('custom_admin_import_index', ['type' => $type]);
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

    /**
     * Serves import file.
     *
     * @Route("/download/{guid}", name="custom_admin_import_download", methods={"GET"})
     * @ParamConverter("export", class="App\Entity\Custom\Export")
     */
    public function downloadAction(Import $import): BinaryFileResponse
    {
        $response = new BinaryFileResponse('import/' . $import->getFilename());
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $import->getFilename()
        );

        return $response;
    }

    /**
     * Data for progress bar.
     * TODO move to FOS REST or API Platform
     *
     * @Route("/progress/{id}", name="custom_admin_import_progress", methods={"GET"})
     * @ParamConverter("import", class="App\Entity\Custom\Import")
     */
    public function progressAction(Import $import): JsonResponse
    {
        return new JsonResponse(
            [
                'processed' => $import->getProcessedItemCount(),
                'total' => $import->getTotalItemCount(),
            ]
        );
    }
}
