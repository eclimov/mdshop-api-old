<?php

namespace App\Controller;

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
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(EntityManagerInterface $em): Response
    {
        // TODO: use 'findVisibleToUserOrderByName' repository method here, when security is implemented
        $companies = $em->getRepository(Company::class)->findAll();
        $companyDatas = array_map(
            static function (Company $company) {
                return (new CompanyData())->fill($company);
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
     * @return Response
     */
    public function find(Company $company): Response
    {
        // TODO: use 'findVisibleToUserById' repository method here, when security is implemented
        return new JsonResponse(
            (new CompanyData())->fill($company),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("companyData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param CompanyData $companyData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(CompanyData $companyData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $company = (new Company())->fill($companyData);
        $em->persist($company);
        $em->flush();

        return new JsonResponse(
            $companyData->fill($company),
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
     * @return Response
     */
    public function update(Company $company, CompanyData $companyData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $company->fill($companyData);
        $em->flush();

        return new JsonResponse(
            $companyData->fill($company),
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
