<?php

namespace App\Repositories\Eloquent;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getAllPayments($attributes = null)
    {
        return Payment::all();
    }

    public function getPaymentById($id)
    {
        return Payment::find($id);
    }

    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function updatePayment($id, array $data)
    {
        $payment = $this->getPaymentById($id);
        $payment ->update($data);
        return $payment;
    }

    public function deletePayment($id)
    {
        $payment = $this->getPaymentById($id);
        $payment->delete();
        return $payment;
    }
}
