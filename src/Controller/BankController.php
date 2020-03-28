<?php

namespace App\Controller;

use Swift_Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/bank")
 */
class BankController {
    /**
     * @Route(path="/", methods={"GET"})
     * @param Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function get(Swift_Mailer $mailer): JsonResponse
    {
//        $message = (new \Swift_Message('Hello Email'))
//            ->setFrom('<put real email spurce here>')
//            ->setTo('<put real email target here>')
//            ->setBody('test')
//        ;

        $mailer->send($message);

        return new JsonResponse(
            ['status' => 'Successful response!'],
            Response::HTTP_OK
        );
    }
}
