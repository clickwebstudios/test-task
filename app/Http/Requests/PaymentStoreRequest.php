<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    protected $paymentData;
    protected $user;
    
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $data = request()->all();
        
        $paymentData = [
            'card_type' => 'card',
            'card_number' => $data['paymentData']['cardNumber']?? null,
            'cvs' => $data['paymentData']['cardCvc']?? null,
            'expiry_at' => $data['paymentData']['cardExpiry']?? null,
        ];
        
        $this->user = auth()->user();
        $this->paymentData = $paymentData;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getPaymentdata()
    {
        return $this->paymentData;
    }
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $validationData = [];
        
        $validationData['paymentData.cardNumber'] = 'required|max:180';
        $validationData['paymentData.cardExpiry'] = 'required|max:180';
        $validationData['paymentData.cardCvc'] = 'required|max:180';

        return $validationData;
    }
    
    public function attributes(): array
    {
        $attributes = [];
        
        $attributes['paymentData.cardNumber'] = 'Credit Card Number';
        $attributes['paymentData.cardExpiry'] = 'Expiry Date';
        $attributes['paymentData.cardCvc'] = 'CVC';
        
        return $attributes;
    }
}
