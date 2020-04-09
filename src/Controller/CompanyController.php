<?php

namespace App\Controller;

use App\Converter\CompanyConverter;
use App\Converter\CompanyDataConverter;
use App\Entity\Company;
use App\Model\CompanyData;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="/company")
 */
class CompanyController
{
    /**
     * @Route(methods={"GET"})
     * @param CompanyDataConverter $companyDataConverter
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(CompanyDataConverter $companyDataConverter, EntityManagerInterface $em): Response
    {
        // TODO: use 'findVisibleToUserOrderByName' repository method here, when security is implemented
        $companies = $em->getRepository(Company::class)->findAll();
        $companyDatas = array_map(
            static function (Company $company) use ($companyDataConverter) {
                return $companyDataConverter->convert($company);
            },
            $companies
        );
        return new JsonResponse(
            $companyDatas,
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Company $company
     * @param CompanyDataConverter $companyDataConverter
     * @return Response
     */
    public function find(Company $company, CompanyDataConverter $companyDataConverter): Response
    {
        // TODO: use 'findVisibleToUserById' repository method here, when security is implemented
        return new JsonResponse(
            $companyDataConverter->convert($company),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("companyData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param CompanyData $companyData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param CompanyConverter $companyConverter
     * @return Response
     */
    public function create(CompanyData $companyData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, CompanyConverter $companyConverter): Response
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

        $company = $companyConverter->convert($companyData);
        $em->persist($company);
        $em->flush();
        $companyData->setId($company->getId());

        return new JsonResponse(
            $companyData,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"PUT"})
     * @ParamConverter("companyData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param Company $company
     * @param CompanyData $companyData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @param CompanyDataConverter $companyDataConverter
     * @return Response
     */
    public function update(Company $company, CompanyData $companyData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em, CompanyDataConverter $companyDataConverter): Response
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

        // TODO: update converter so it could update existing entities (if provided)
        $company
            ->setName($companyData->name)
            ->setShortName($companyData->shortName)
            ->setFiscalCode($companyData->fiscalCode)
            ->setIban($companyData->iban)
            ->setVat($companyData->vat)
            ;
        $em->flush();

        return new JsonResponse(
            $companyDataConverter->convert($company),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"DELETE"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Company $company, EntityManagerInterface $em): Response
    {
        $em->remove($company);
        $em->flush();
        return new Response('',Response::HTTP_OK);
    }
}
