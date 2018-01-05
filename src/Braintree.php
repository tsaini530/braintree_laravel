<?php
namespace Dbws\Braintree;

/**
* 
*/

class Braintree 
{	
	public function createToken($braintree_id=''){
		if($braintree_id!=''){
			$clientToken=\Braintree_ClientToken::generate([
            "customerId" =>$customer->braintree_id]
        );
		}
		else{
			$clientToken=\Braintree_ClientToken::generate([]);
		}
		return $clientToken;
	}
	public function getCardList($braintree_id){
		$customer = \Braintree_Customer::find($braintree_id);
		$paymentMethod=$customer->paymentMethods;
		return $paymentMethod;
	}
	
	public function createCustomer($firstName,$lastName,$email){
		$result = \Braintree_Customer::create([
			'firstName'=>$firstName,
			'email'=> $email,
			'lastName'=>$lastName

		]);
		if($result->success){
			$status='success';
			$customer= array(                    
				'braintree_id'=>$result->customer->id
			);    
		} 
		else{
			$message = '';
			foreach($result->errors->deepAll() AS $error) {             
				$message .=$error->code . ": " . $error->message . "\n";                
			}
			$status='error';
			$customer=array("message"=>$message);
		}
		return array("status"=>$status,"response"=>$customer);

	}
	public function createPaymentMethod($braintree_id,$paymentMethodNonce){
		$result = \Braintree_PaymentMethod::create([
			'customerId' => $braintree_id,
			'paymentMethodNonce' => $paymentMethodNonce,
			'options'=>[
				'verifyCard' => true						
			]
		]);
		if($result->success){
			$status='success';
			$payment= array(                    
				'token'=>$result->paymentMethod->token
			);    
		} 
		else{
			$message = '';
			foreach($result->errors->deepAll() AS $error) {             
				$message .=$error->code . ": " . $error->message . "\n";                
			}
			$status='error';
			$payment=array("message"=>$message);
		}        
		return array("status"=>$status,"response"=>$payment); 

	}
	public function setDefault($token){
		$result= \Braintree_PaymentMethod::update($token,
			[
				'options' => [
					'makeDefault' => true
				]
			]
		);
		if($result->success){
			$status='success';
			$payment= array(                    
				'token'=>$result->paymentMethod->token
			);    
		} 
		else{
			$message = '';
			foreach($result->errors->deepAll() AS $error) {             
				$message .=$error->code . ": " . $error->message . "\n";                
			}
			$status='error';
			$payment=array("message"=>$message);
		}        
		return array("status"=>$status,"response"=>$payment); 
	}
	public function deleteCard($token){
		$result = \Braintree_PaymentMethod::delete($token);
		if($result->success){
			$status='success';
			$payment= array(                    
				'delete'=>true
			);    
		} 
		else{
			$message = '';
			foreach($result->errors->deepAll() AS $error) {             
				$message .=$error->code . ": " . $error->message . "\n";                
			}
			$status='error';
			$payment=array("message"=>$message);
		}        
		return array("status"=>$status,"response"=>$payment); 
		
	}
	public function setTransaction($amount,$braintree_id,$paymentMethodNonce){

		$result = \Braintree_Transaction::sale([
				'amount' => $amount,
				'options' => [ 'submitForSettlement' => True ],
				'paymentMethodNonce' => $paymentMethodNonce,
				'customerId'=>$braintree_id
			]);

		if ($result->success) {
            $status='success';
            $transaction= array(                    
                    'txn_id'=>$result->transaction->id,
                    'txn_details'=>json_encode($result->transaction),
                    'txn_date'=>$result->transaction->createdAt->format('Y-m-d H:i:s'),
                    'txn_amount'=>$result->transaction->amount,
                    'token'=>$result->transaction->creditCardDetails->token
                );   
        }
        else {
            $message = '';
            foreach($result->errors->deepAll() AS $error) {             
                $message .=$error->code . ": " . $error->message . "\n";                
            }
            $status='error';
            $transaction=array("message"=>$message);
        }       
        return array("status"=>$status,"response"=>$transaction);
	}
	  /**
     * Create Transaction Refund on braintree    
     * Params Transactionid   
     * @return mixed
     */
	  public function refundTransaction($txn_id){
	  	$result = \Braintree_Transaction::refund($txn_id);

	  	 if ($result->success) {
            $status='success';
            $transaction= array(                    
                    'txn_id'=>$result->transaction->id,
                    'txn_details'=>json_encode($result->transaction),
                    'txn_date'=>$result->transaction->createdAt->format('Y-m-d H:i:s'),
                    'txn_amount'=>$result->transaction->amount,
                    'token'=>$result->transaction->creditCardDetails->token
            ); 
        }     
        else{
            $message = '';
            foreach($result->errors->deepAll() AS $error) {             
                $message .=$error->code . ": " . $error->message . "\n";                
            }
            $status='error';
            $transaction=array("message"=>$message);
        }
        return array("status"=>$status,"response"=>$transaction);
	  }
	}
	?>