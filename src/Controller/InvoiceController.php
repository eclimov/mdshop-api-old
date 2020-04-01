<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Service\InvoiceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(path="/invoice")
 */
class InvoiceController {
    /**
     * @Route("/{id}/download", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Invoice $invoice
     * @param InvoiceGenerator $invoiceGenerator
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function download(Invoice $invoice, InvoiceGenerator $invoiceGenerator, EntityManagerInterface $em): Response
    {
        // TODO: monitor fix for `maximum number of columns per sheet was exceeded` in LibreOffice
        // https://github.com/PHPOffice/PhpSpreadsheet/pull/1289
        return new JsonResponse(
            [
                'filename' => $invoiceGenerator->generate($invoice, $em)
            ],
            Response::HTTP_OK
        );
    }
}
