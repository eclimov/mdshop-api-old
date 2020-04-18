<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Model\CompanyAddressData;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="company/{company_id}/address", requirements={"company_id" = "\d+"})
 * @ParamConverter("company", options={"id" = "company_id"})
 * @Entity("companyAddress", expr="repository.findOneByCompanyIdAndId(company_id,id)")
 */
class CompanyAddressController {
    /**
     * @Route(methods={"GET"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(Company $company, EntityManagerInterface $em): Response
    {
        $companyAddresses = $em->getRepository(CompanyAddress::class)
            ->findBy(['company' => $company]);
        $companyAddressDatas = array_map(
            static function (CompanyAddress $companyAddress) {
                return (new CompanyAddressData())->fill($companyAddress);
            },
            $companyAddresses
        );
        return new JsonResponse(
            $companyAddressDatas,
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param CompanyAddress $companyAddress
     * @return Response
     */
    public function find(CompanyAddress $companyAddress): Response
    {
        return new JsonResponse(
            (new CompanyAddressData())->fill($companyAddress),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("companyAddressData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param Company $company
     * @param CompanyAddressData $companyAddressData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Company $company, CompanyAddressData $companyAddressData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $companyAddress = (new CompanyAddress())
            ->fill($companyAddressData)
            ->setCompany($company)
        ;
        $em->persist($companyAddress);
        $em->flush();

        return new JsonResponse(
            $companyAddressData->fill($companyAddress),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"PUT"})
     * @ParamConverter("companyAddressData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param CompanyAddress $companyAddress
     * @param CompanyAddressData $companyAddressData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function update(CompanyAddress $companyAddress, CompanyAddressData $companyAddressData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $companyAddress->fill($companyAddressData);
        $em->flush();

        return new JsonResponse(
            (new CompanyAddressData())->fill($companyAddress),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"DELETE"})
     * @param CompanyAddress $companyAddress
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(CompanyAddress $companyAddress, EntityManagerInterface $em): Response
    {
        $em->remove($companyAddress);
        $em->flush();
        return new Response('',Response::HTTP_OK);
    }
}
