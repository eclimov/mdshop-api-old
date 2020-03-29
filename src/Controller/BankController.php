<?php

namespace App\Controller;

use App\Converter\BankDataConverter;
use App\Entity\Bank;
use App\Model\BankData;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="/bank")
 */
class BankController { // https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrineconverter-options
    /**
     * @Route(methods={"GET"})
     * @param BankDataConverter $bankDataConverter
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function get(BankDataConverter $bankDataConverter, ManagerRegistry $doctrine): JsonResponse
    {
        $banks = $doctrine->getRepository(Bank::class)->findAll();
        $bankDatas = array_map(
            static function (Bank $bank) use ($bankDataConverter) {
                return $bankDataConverter->convert($bank);
            },
            $banks
        );
        return new JsonResponse(
            $bankDatas,
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Bank $bank
     * @param BankDataConverter $bankDataConverter
     * @return Response
     */
    public function find(Bank $bank, BankDataConverter $bankDataConverter): Response
    {
        return new JsonResponse(
            $bankDataConverter->convert($bank),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("bankData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}}))
     * @param BankData $bankData
     * @return Response
     */
    public function create(BankData $bankData, ConstraintViolationListInterface $validationErrors): Response
    {
        if (count($validationErrors) > 0) {
            dump($validationErrors);
        }

        dump($bankData);
        die;

        return new JsonResponse(
            $this->bankService->create($bankData),
            Response::HTTP_CREATED
        );
    }
}
