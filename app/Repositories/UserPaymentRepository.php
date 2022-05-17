<?php

namespace App\Repositories;

use App\Models\User;
use App\Libs\Interfaces\StripeHelperContract;
use App\Libs\ExceptionCommand;
use DB;
use App\Models\UserPayment;

class UserPaymentRepository
{
    public function delete(int $id, bool $deleteDefault = false)
    {
        $stripe = app(StripeHelperContract::class);
        $model = UserPayment::find($id);
        
        if (!$model) {
            return;
        }
        
        if ($model->is_default and !$deleteDefault) {
            return;
        }
        
        if ($model->stripe_payment_id) {
            $response = $stripe->detachPaymentMethods($model->stripe_payment_id);
        }
        
        $model->delete();
    }
  
    public function setDefaultPayment(int $id): void
    {
        $bi = UserPayment::find($id);
        if (!$bi) {
            return;
        }
        
        DB::update("update user_payments set is_default = 0 where user_id = ".$bi->user_id." ");
        $bi = UserPayment::find($id);
        
        $bi->is_default = 1;
        $bi->save();
    }

    public function unsetDefaultPayment(int $id): void
    {
        $bi = UserPayment::find($id);
        if (!$bi) {
            return;
        }

        $bi->is_default = 0;
        $bi->save();
    }
    
    public function store(User $user, array $data)
    {
        $stripe = app(StripeHelperContract::class);

        $expAr = explode('/', $data['expiry_at']);
        $expMonth = $expAr[0] ?? 0;
        $expYear = $expAr[1] ?? 0;

        $response = $stripe->createPaymentmethod(
            $data['card_number']?? '',
            $expMonth?? '',
            $expYear?? '',
            $data['cvs']?? ''
        );

        if (!$response) {
            Throw new ExceptionCommand('Stripe Error', $stripe->getErrors());
        }

        $data['stripe_payment_id'] = $response->id;
        
        if (!$user->stripe_id) {
            $responseUser = $stripe->createCustomerFromUser($user);

            if (!$responseUser) {
                Throw new ExceptionCommand('Stripe Error', $stripe->getErrors());
            }

            $user->stripe_id = $responseUser->id;
            $user->save();
        }

        $resAttache = $stripe->attachPaymentMethodToCustomer($response->id, $user->stripe_id);

        if (!$resAttache) {
            Throw new ExceptionCommand('Stripe Error', $stripe->getErrors());
        }

        $data['stripe_payment_attache_id'] = $resAttache->id;
        
        $UserPayment = app(UserPayment::class);
        $UserPayment->fill($data);
        
        $user->userPayments()->save($UserPayment);
        
        foreach($user->userPayments as $payment){
            $payment->is_default = 0;
            $payment->save();
        }
        
        $UserPayment->is_default = 1;
        $UserPayment->save();
    }
}
