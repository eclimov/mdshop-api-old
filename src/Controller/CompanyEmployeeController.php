<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyEmployee;
use App\Model\CompanyEmployeeData;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route(path="company/{company_id}/employee", requirements={"company_id" = "\d+"})
 * @ParamConverter("company", options={"id" = "company_id"})
 * @Entity("companyEmployee", expr="repository.findOneByCompanyIdAndId(company_id,id)")
 */
class CompanyEmployeeController {
    /**
     * @Route(methods={"GET"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function get(Company $company, EntityManagerInterface $em): Response
    {
        $companyEmployee = $em->getRepository(CompanyEmployee::class)
            ->findBy(['company' => $company]);
        $companyEmployeeDatas = array_map(
            static function (CompanyEmployee $companyEmployee) {
                return (new CompanyEmployeeData())->fill($companyEmployee);
            },
            $companyEmployee
        );
        return new JsonResponse(
            $companyEmployeeDatas,
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param CompanyEmployee $companyEmployee
     * @return Response
     */
    public function find(CompanyEmployee $companyEmployee): Response
    {
        return new JsonResponse(
            (new CompanyEmployeeData())->fill($companyEmployee),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(methods={"POST"})
     * @ParamConverter("companyEmployeeData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param Company $company
     * @param CompanyEmployeeData $companyEmployeeData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Company $company, CompanyEmployeeData $companyEmployeeData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $companyEmployee = (new CompanyEmployee())
            ->fill($companyEmployeeData)
            ->setCompany($company)
        ;
        $em->persist($companyEmployee);
        $em->flush();

        return new JsonResponse(
            $companyEmployeeData->fill($companyEmployee),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"PUT"})
     * @ParamConverter("companyEmployeeData", converter="fos_rest.request_body", options={"validator"={"groups"={"create"}}})
     * @param CompanyEmployee $companyEmployee
     * @param CompanyEmployeeData $companyEmployeeData
     * @param ConstraintViolationListInterface $validationErrors
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function update(CompanyEmployee $companyEmployee, CompanyEmployeeData $companyEmployeeData, ConstraintViolationListInterface $validationErrors, EntityManagerInterface $em): Response
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

        $companyEmployee->fill($companyEmployeeData);
        $em->flush();

        return new JsonResponse(
            (new CompanyEmployeeData())->fill($companyEmployee),
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"DELETE"})
     * @param CompanyEmployee $companyEmployee
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(CompanyEmployee $companyEmployee, EntityManagerInterface $em): Response
    {
        $em->remove($companyEmployee);
        $em->flush();
        return new Response('',Response::HTTP_OK);
    }
}
