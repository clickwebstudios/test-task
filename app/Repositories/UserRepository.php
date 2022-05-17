<?php

namespace App\Repositories;

use App\Models\User;
use App\Libs\Interfaces\StripeHelperContract;
use App\Libs\ExceptionCommand;

class UserRepository
{
    protected $lastResult = null;

    public function getLastResult()
    {
        return $this->lastResult;
    }
  
    public function generateUserAccessToken(User $user)
    {
        $token = null;
        while (true) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            
            $model = User::where('access_token', $token)
                ->where('id', '!=', $user->id)
                ->first();
            
            if (!$model) {
                break;
            }
        }
        
        $user->access_token = $token;
        $user->save();
        
        return $token;
    }
    
    public function createIntentPay(User $user, int $coins)
    {
        $stripeHelper = app(StripeHelperContract::class);
        
        $response = $stripeHelper->paymentIntentByUser($user, $coins);

        if (!$response) {
            Throw new ExceptionCommand('Stripe Error', ['Payment error']);
        }
        
        $responseConfirm = $stripeHelper->paymentIntentConfirm($response->id);

        if (!$responseConfirm) {
            Throw new ExceptionCommand('Stripe Error', $stripeHelper->getErrors());
        }
        
        if ($responseConfirm->status === 'succeeded') {
          
          return $this->lastResult = [
              'status' => $responseConfirm->status,
              'client_secret' => $responseConfirm->client_secret?? null,
              'payment_method' => $responseConfirm->payment_method?? null,
          ];
          
        } else {
            Throw new ExceptionCommand('Stripe Error', [
                'requires_action' => [
                    'status' => $responseConfirm->status,
                    'client_secret' => $responseConfirm->client_secret?? null,
                    'payment_method' => $responseConfirm->payment_method?? null,
                ],
            ]);
        }
    }
}
