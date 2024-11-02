<?php

namespace App\Repositories\Contracts;

interface PaymentRepositoryInterface
{
    public function getAllPayments();
    public function getPaymentById($id);
    public function createPayment(array $data);
    public function updatePayment($id, array $data);
    public function deletePayment($id);
}
