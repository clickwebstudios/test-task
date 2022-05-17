<?php

namespace App\Libs;

use Stripe\Exception\CardException;
use App\Models\User;
use Stripe\PaymentMethod;

class StripeHelper
{
    protected $stripe = null;

    protected $errors = [];

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    }

    public function getStripe()
    {
        return $this->stripe;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function detachPaymentMethods($id)
    {
        try{
            $response = $this->stripe->paymentMethods->detach(
              $id,
              []
            );
            
        } catch (CardException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }

        return $response;
    }
    
    public function getPaymentInformation(string $stripePaymentId): array
    {
        try{

            $response = $this->stripe->paymentMethods->retrieve(
                $stripePaymentId,
                []
            );

            $data = [
                'brand' => $response->card->brand,
                'card' => '**** **** **** '.$response->card->last4,
                'exp_month' => $response->card->exp_month,
                'exp_year' => $response->card->exp_year,
            ];

        } catch (\Exception $e) {
            $this->errors = [$e->getMessage()];
            return [];
        }

        return $data;
    }
    
    public function createPaymentmethod(string $number, string $exp_month, string $exp_year, string $cvc): ?PaymentMethod
    {
        try{

            $response = $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $number,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'cvc' => $cvc
                ],
            ]);

        } catch (\Exception $e) {
            $this->errors = [$e->getMessage()];
            return null;
        }

        return $response;
    }
    
    public function createCustomerFromUser(User $user)
    {
        try{

            $response = $this->stripe->customers->create([
              'email' => $user->email,
              'name' => $user->name,
              'description' => 'User id: '.$user->id,
            ]);

        } catch (\Exception $e) {
            $this->errors = [$e->getMessage()];
            return null;
        }

        return $response;
    }
    
    public function attachPaymentMethodToCustomer(string $paymentMethodId, string $customerId)
    {
        try{

            $response = $this->stripe->paymentMethods->attach(
                $paymentMethodId,
                ['customer' => $customerId]
            );

        } catch (\Exception $e) {
            $this->errors = [$e->getMessage()];
            return null;
        }

        return $response;
    }
        
    public function paymentIntentByUser(User $user, int $coins)
    {
        $price = $coins*config('saas.coint_price');
        
        $userPayment = $user->userPaymentDefault;
      
        try{
            $response = $this->stripe->paymentIntents->create([
              'amount' => $price*100,
              'description' => 'User Id: '.$user->id.', conins: '.$coins,
              'currency' => 'USD',
              'customer' => $user->stripe_id,
              'payment_method' => $userPayment->stripe_payment_id,
            ]);
            
        } catch (CardException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }

        return $response;
    }
    
    public function paymentIntentConfirm($idPi)
    {
        try{
          $responsePi = $this->stripe->paymentIntents->confirm(
              $idPi
          );
          
        } catch (CardException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
        
        return $responsePi;
    }
}
