<?php

namespace Dbws\Braintree\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use braintree;


class BraintreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    function __construct()
    {   
        $user=auth()->user();
        if(empty($user->braintree_id)){
            $braintree=braintree::createCustomer($user->first_name,$user->last_name,$user->email);
            if($braintree['status']=='success'){
                $user->braintree_id=$braintree['response']['braintree_id'];
                $user->save();
            }
        }
    }
    
    public function index()
    {   
        $paymentMethod=braintree::getCardList(auth->user->braintree_id);
        return view('index',['paymentMethod'=>$paymentMethod]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $clientToken= braintree::createToken();
        return view('braintree::create',['clientToken'=>$clientToken]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $input=$request->all();

        $result = braintree::createPaymentMethod($request->payment_method_nonce);
        if($result['status']=='success'){
            $request->session()->flash('alert-success', (' credit card Added successfully.'));
            return redirect(route('braintree.index'));
        }
    }

    public function update($id , Request $request){

        $result= braintree::setDefault($id);
        $request->session()->flash('alert-success', (' credit card Updated successfully.'));
        return redirect(route('braintree.index'));
    }

    public function destroy($id, Request $request)
    {
        $result= braintree::deleteCard($id);
        if($result['status']=='success'){
            $request->session()->flash('alert-success', (' credit card Deleted successfully.'));
       }else{
           $request->session()->flash('alert-danger', (' credit card Deleted successfully.'));
       }
       return redirect(route('braintree.index'));

   }
  

}
