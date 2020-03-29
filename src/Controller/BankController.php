<?php

namespace App\Controller;

use App\Converter\BankConverter;
use App\Converter\BankDataConverter;
use App\Entity\Bank;
use App\Model\BankData;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="/bank")
 */
class BankController
{ // https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrineconverter-options
    /**
     * @Route(methods={"GET"})
     * @param BankDataConverter $bankDataConverter
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(BankDataConverter $bankDataConverter, EntityManagerInterface $em): Response
    {
        $banks = $em->getRepository(Bank::class)->findAll();
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
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
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
     * @ParamConverter("bankData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param BankData $bankData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param BankConverter $bankConverter
     * @return Response
     */
    public function create(BankData $bankData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, BankConverter $bankConverter): Response
    {
        if (count($validationErrors) > 0) {
            return new JsonResponse(
                [
                    'message' => $validationErrors->get(0)->getMessage(),
                    'propertyPath' => $validationErrors->get(0)->getPropertyPath()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $bank = $bankConverter->convert($bankData);
        $em->persist($bank);
        $em->flush();
        $bankData->setId($bank->getId());

        return new JsonResponse(
            $bankData,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"PUT"})
     * @ParamConverter("bankData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param Bank $bank
     * @param BankData $bankData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param BankDataConverter $bankDataConverter
     * @return Response
     */
    public function update(Bank $bank, BankData $bankData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, BankDataConverter $bankDataConverter): Response
    {
        if (count($validationErrors) > 0) {
            return new JsonResponse(
                [
                    'message' => $validationErrors->get(0)->getMessage(),
                    'propertyPath' => $validationErrors->get(0)->getPropertyPath()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $bank->setName($bankData->name);
        $em->flush();

        return new JsonResponse(
            $bankDataConverter->convert($bank),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"DELETE"})
     * @param Bank $bank
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Bank $bank, EntityManagerInterface $em): Response
    {
        $em->remove($bank);
        $em->flush();
        return new Response('',Response::HTTP_OK);
    }
}
