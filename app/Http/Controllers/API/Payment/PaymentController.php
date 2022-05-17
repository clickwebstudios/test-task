<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use Auth;
use App\Libs\Interfaces\OrmCommandInterface;
use App\Http\Requests\PaymentStoreRequest;
use App\Repositories\UserPaymentRepository;
use App\Models\UserPayment;

class PaymentController extends Controller
{
    public function setDefault(UserPayment $userPayment, UserPaymentRepository $repository)
    {
        $user = auth()->user();
        
        if ($user->id !== $userPayment->user_id) {
            abort('Not fund', 503);
        }
      
        $repository->setDefaultPayment($userPayment->id);
    }
  
    public function store(PaymentStoreRequest $request, OrmCommandInterface $commander, UserPaymentRepository $repository)
    {
        $commander->setCommand($repository, 'store', [
            $request->getUser(),
            $request->getPaymentdata()
        ]);
        
        if (!$commander->execute()) {
            return response()->json([
                'errors' => $commander->getErrors(),
                'coreMessage' => 'An error occurred on the site',
            ], 422);
        }
    }
    
    public function getPayments()
    {
        $user = Auth::user();
        
        return response()->json([
            'payments' => $user->userPayments
        ]);
    }
  
    public function delete(UserPayment $userPayment, UserPaymentRepository $repository)
    {
        $user = auth()->user();
        
        if ($user->id !== $userPayment->user_id) {
            abort('Not fund', 503);
        }
      
        $repository->delete($userPayment->id);
    }
}
