<?php

namespace App\Controller;

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
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(Bank $bank, EntityManagerInterface $em): Response
    {
        $bankAffiliates = $em->getRepository(BankAffiliate::class)
            ->findBy(['bank' => $bank]);
        $bankAffiliateDatas = array_map(
            static function (BankAffiliate $bankAffiliate) {
                return (new BankAffiliateData())->fill($bankAffiliate);
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
     * @return Response
     */
    public function find(BankAffiliate $bankAffiliate): Response
    {
        return new JsonResponse(
            (new BankAffiliateData())->fill($bankAffiliate),
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
     * @return Response
     */
    public function create(Bank $bank, BankAffiliateData $bankAffiliateData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $bankAffiliate = (new BankAffiliate())
            ->fill($bankAffiliateData)
            ->setBank($bank)
        ;
        $em->persist($bankAffiliate);
        $em->flush();

        return new JsonResponse(
            $bankAffiliateData->fill($bankAffiliate),
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
     * @return Response
     */
    public function update(BankAffiliate $bankAffiliate, BankAffiliateData $bankAffiliateData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $bankAffiliate->fill($bankAffiliateData);
        $em->flush();

        return new JsonResponse(
            (new BankAffiliateData())->fill($bankAffiliate),
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
