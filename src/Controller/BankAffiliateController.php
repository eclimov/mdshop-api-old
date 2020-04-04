<?php

namespace App\Controller;

use App\Converter\BankAffiliateConverter;
use App\Converter\BankAffiliateDataConverter;
use App\Entity\Bank;
use App\Entity\BankAffiliate;
use App\Model\BankAffiliateData;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="bank/{bank_id}/affiliate", requirements={"bank_id" = "\d+"})
 * @ParamConverter("bank", options={"id" = "bank_id"})
 * @Entity("bankAffiliate", expr="repository.findOneByBankIdAndId(bank_id,id)")
 */
class BankAffiliateController {
    /**
     * @Route(methods={"GET"})
     * @param Bank $bank
     * @param BankAffiliateDataConverter $bankAffiliateDataConverter
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(Bank $bank, BankAffiliateDataConverter $bankAffiliateDataConverter, EntityManagerInterface $em): Response
    {
        $bankAffiliates = $em->getRepository(BankAffiliate::class)
            ->findBy(['bank' => $bank]);
        $bankAffiliateDatas = array_map(
            static function (BankAffiliate $bankAffiliate) use ($bankAffiliateDataConverter) {
                return $bankAffiliateDataConverter->convert($bankAffiliate);
            },
            $bankAffiliates
        );
        return new JsonResponse(
            $bankAffiliateDatas,
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param BankAffiliate $bankAffiliate
     * @param BankAffiliateDataConverter $bankAffiliateDataConverter
     * @return Response
     */
    public function find(BankAffiliate $bankAffiliate, BankAffiliateDataConverter $bankAffiliateDataConverter): Response
    {
        return new JsonResponse(
            $bankAffiliateDataConverter->convert($bankAffiliate),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("bankAffiliateData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param Bank $bank
     * @param BankAffiliateData $bankAffiliateData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param BankAffiliateConverter $bankAffiliateConverter
     * @return Response
     */
    public function create(Bank $bank, BankAffiliateData $bankAffiliateData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, BankAffiliateConverter $bankAffiliateConverter): Response
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

        $bankAffiliate = $bankAffiliateConverter->convert($bankAffiliateData);
        $bankAffiliate->setBank($bank);
        $em->persist($bankAffiliate);
        $em->flush();
        $bankAffiliateData->setId($bankAffiliate->getId());

        return new JsonResponse(
            $bankAffiliateData,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"PUT"})
     * @ParamConverter("bankAffiliateData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param BankAffiliate $bankAffiliate
     * @param BankAffiliateData $bankAffiliateData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param BankAffiliateDataConverter $bankAffiliateDataConverter
     * @return Response
     */
    public function update(BankAffiliate $bankAffiliate, BankAffiliateData $bankAffiliateData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, BankAffiliateDataConverter $bankAffiliateDataConverter): Response
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

        $bankAffiliate->setAffiliateNumber($bankAffiliateData->affiliateNumber);
        $em->flush();

        return new JsonResponse(
            $bankAffiliateDataConverter->convert($bankAffiliate),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"DELETE"})
     * @param BankAffiliate $bankAffiliate
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(BankAffiliate $bankAffiliate, EntityManagerInterface $em): Response
    {
        $em->remove($bankAffiliate);
        $em->flush();
        return new Response('',Response::HTTP_OK);
    }
}
