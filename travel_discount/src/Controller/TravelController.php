<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DiscountCalculator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TravelController extends AbstractController
{
    private $calculator;

    public function __construct(DiscountCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/api/calculate", name="calculate_discount", methods={"POST"})
     */
    public function calculate(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $constraint = new Assert\Collection([
            'basePrice' => [new Assert\NotBlank(), new Assert\Type('numeric')],
            'birthDate' => [new Assert\NotBlank(), new Assert\Date()],
            'startDate' => [new Assert\Date()],
            'paymentDate' => [new Assert\Date()],
        ]);

        $errors = $validator->validate($data, $constraint);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $basePrice = $data['basePrice'];
        $birthDate = $data['birthDate'];
        $startDate = $data['startDate'] ?? null;
        $paymentDate = $data['paymentDate'] ?? null;

        $finalPrice = $this->calculator->calculateDiscount($basePrice, $birthDate, $startDate, $paymentDate);

        return new JsonResponse(['finalPrice' => $finalPrice]);
    }
}
