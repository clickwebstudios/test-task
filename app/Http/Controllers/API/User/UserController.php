<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Auth;
use App\Libs\Interfaces\OrmCommandInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserBalanceRepository;
use App\Repositories\UserLogRepository;

class UserController extends Controller
{
    public function logs(UserLogRepository $userLogRepository)
    {
        $user = Auth::user();
      
        return response()->json([
            'logs' => $userLogRepository->logsByUser($user)
        ]);
    }
  
    public function generateAccessToken(UserRepository $userRepository)
    {
        $user = Auth::user();
      
        $userRepository->generateUserAccessToken($user);
    }
  
    public function checkoutCoins(OrmCommandInterface $commander, UserRepository $userRepository, UserBalanceRepository $balanceRepository)
    {
        $user = Auth::user();
        $userPayment = $user->userPaymentDefault;
        
        if (!$userPayment) {
            abort(422, 'Please attache payment card');
        }
        
        $coins = request()->coins?? 0;
      
        $commander->setCommand($userRepository, 'createIntentPay', [
            $user,
            $coins
        ]);
        
        $commander->setCommand($balanceRepository, 'addCoins', [
            $user,
            $coins
        ]);
        
        if (!$commander->execute()) {
            return response()->json([
                'errors' => $commander->getErrors(),
                'coreMessage' => 'An error occurred on the site',
            ], 422);
        }
        
        return response()->json([
            'paymentResult' => $userRepository->getLastResult(),
        ]);
    }
}
