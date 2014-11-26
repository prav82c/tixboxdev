<?php

// @author ponnuchamy



class cartController extends \BaseController {



    /**

     * Display a listing of the resource.

     *

     * @return Response

     */ 

    public function index() {



        $cart = Cart::content();

        return View::make('cart.site.index', array('cart' => $cart));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return Response

     */

    public function create() {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @return Response

     */

    public function store() {

        //

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return Response

     */

    public function show($id) {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return Response

     */

    public function edit($id) {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  int  $id

     * @return Response

     */

    public function update($id) {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return Response

     */

    public function destroy($id) {

        //

    }



    public function add() {

		

		$seatmap_tickets = json_decode(Input::get('ticket'));

		

		$formdata = array();

		parse_str(Input::get('formdata'),$formdata);

		$event_title = Input::get('event_title');

		if(count($formdata)>0)

		{

			$event_date = $formdata['event_date_id'];//Input::get('event_date_id');

			$event_id = $formdata['event_id'];//Input::get('event_id');

			$event_title = $formdata['event_title'];//Input::get('event_title');
			
			// updating currency
			$currency = CurrencyHelper::getEventCurrency($event_id);
			Session::put('currency',$currency[0]);
			

			if(isset($formdata['data']))

			{

				$tickets = $formdata['data'];//Input::get('data');

	

				foreach ($tickets['tickets'] as $ticket) {

					
					/*$temptickets = UserTicketTemp::where('event_date_id','=',$event_date)->where('event_id','=',$event_id)->where('event_ticket_id','=',$ticket['event_ticket_id'])->get();
					$total_count = 0;
					$available_qty = EventsHelper::getAvailableTickets($ticket['event_ticket_id']);
					
					foreach($temptickets as $temp_ticket)
					{
						$total_count = $total_count + $temp_ticket->qty;
					}
					$total_quantity = $available_qty - $total_count;
					*/
					
					$available_qty = EventsHelper::getTotalAvailableTickets($ticket['event_ticket_id']);
                    
                    $temp_total  = EventsHelper::getTempTotal($event_id,$event_date,$ticket['event_ticket_id']);
					
					$total_quantity = $available_qty - $temp_total;
					
                  //  Log::info('available qyt '.$available_qty);
                    
                   // Log::info('Ticket id'.$ticket['event_ticket_id'].' <> Temp qty '.$temp_total);
					
					
					if($total_quantity <= 0)
					{ 
					    Log::info('cart add totatl quantity less'.$total_quantity);
						//$cart = Cart::content(); 
	
						$response = array('status' => 'updated','message' => 'Added in the cart.','totalItem' => 1,'cartcontent' => '');
				
						return json_encode($response);
					
					}
					
		

					if ($ticket['quantity'] != 0) { 
					
							 $basket_id = '';
                             $event = EventsHelper::getEventDetails($event_id);Log::info(' Ticket Name :'.$ticket['ticket_name']);
                             if($event->is_dtcm)
                             {

                                 $dtcm_token = AppHelper::dtcmToken();
                                 $dtcm_sellercode = AppHelper::dtcmSellerCode();
//echo '/usr/bin/curl -X POST -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$dtcm_token.'\' -d \'{"Channel":"W","Seller":"'.$dtcm_sellercode.'","Performancecode":"'.$event->dtcm_id.'","Area":"'.$ticket['ticket_name'].'","autoReduce": false,"Demand":[{"PriceTypeCode":"A","Quantity":1,"Admits":1,"offerCode":"","qualifierCode":"","entitlement":"","Customer":{}}],"Fees":[{"Type":"5","Code":"W"}]}\' -v https://api.etixdubai.com/baskets';exit;
                                 // basket generate
                                 exec('/usr/bin/curl -X POST -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$dtcm_token.'\' -d \'{"Channel":"W","Seller":"'.$dtcm_sellercode.'","Performancecode":"'.$event->dtcm_id.'","Area":"'.$ticket['ticket_name'].'","autoReduce": false,"Demand":[{"PriceTypeCode":"A","Quantity":1,"Admits":1,"offerCode":"","qualifierCode":"","entitlement":"","Customer":{}}],"Fees":[{"Type":"5","Code":"W"}]}\' -v https://api.etixdubai.com/baskets', $output, $return);

                                 // getting basket id
                                 if(isset($output))
                                 {
                                    $op = json_decode($output[0]);
                                    $basket_id = $op->Id;
                                 }
                             }
							 
							 //. update the cart items in usertickettemp table

							 $session_id = Session::getId();

							 $id = $event_id;

							 $options = array(

										'event_date_id' => $event_date,

										'event_ticket_id' => $ticket['event_ticket_id'],

										'available_qty' => $available_qty,

										'event_id' => $event_id, 'basket_id' => $basket_id);

							 ksort($options);

							 $rowid = md5($id . serialize($options));

							$data = Cart::associate('Ticket')->add(

							   // $event_date . $ticket['event_ticket_id'], 

								$id,

								$event_title.'-'.$ticket['ticket_name'],

								$ticket['quantity'], 

								$ticket['price'], 

								$options);
								
								
								 $usertickettemp = new UserTicketTemp();

								 $usertickettemp->session_id = $session_id;
						
								 $usertickettemp->row_id = $rowid;
						
								 $usertickettemp->event_id = $event_id;
						
								 $usertickettemp->status = 1;
						
								 //$usertickettemp->price = $ticket->price;
						
								 $usertickettemp->user_id = (Session::has('user')?Session::get('user')->id:0);
						
								 $usertickettemp->event_ticket_id = $ticket['event_ticket_id'];
						
								 $usertickettemp->event_date_id = $event_date;
						
								 $usertickettemp->qty = $ticket['quantity'];
						
								 $usertickettemp->save();

					}

				}

			}

		}

		$cart = Cart::content(); 

		$response = array('status' => 'updated','message' => 'Added in the cart.','totalItem' => Cart::associate('Ticket')->count(),'cartcontent' => ''.View::make('cart.site.indexajax', array('cart' => $cart)));

		return json_encode($response);

    }

	

	public function updateTicketsTempTable()

	{

		 $ticket = json_decode(Input::get('seat'));

		 $action = Input::get('action');

		

		 if($action == 'insert')

		 {
             
             //$dtcm_token = AppHelper::dtcmToken();
                          
            // $dtcm_sellercode = AppHelper::dtcmSellerCode();
             
            // exec('/usr/bin/curl -X GET -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$dtcm_token.'\' -v \'https://api.etixdubai.com/performances/etes1tb/sections/'.$ticket->title.'/?channel=W&sellerCode='.$dtcm_sellercode.'\'', $purchase, $return);
             
             $session_id = Session::getId();

			 $temptickets = UserTicketTemp::where('event_date_id','=',$ticket->event_date_id)->where('seat_id','=',$ticket->seat_id)->count();

                         $ticketcount = UserTicket::where('event_date_id','=',$ticket->event_date_id)
							->where('event_id','=',$ticket->event_id)
							->where('seat_id','=',$ticket->seat_id)
							->where('is_canceled','=',0)
							->count();

			 if($temptickets==0 && $ticketcount == 0)

			 {
                 $basket_id = '';
                 $event = EventsHelper::getEventDetails($ticket->event_id);
                 if($event->is_dtcm)
                 {
                 
                     $dtcm_token = AppHelper::dtcmToken();
                     $dtcm_sellercode = AppHelper::dtcmSellerCode();
                     
                     // basket generate
                     exec('/usr/bin/curl -X POST -H \'Content-Type: application/json\' -H \'Authorization: Bearer '.$dtcm_token.'\' -d \'{"Channel":"W","Seller":"'.$dtcm_sellercode.'","Performancecode":"'.$event->dtcm_id.'","Area":"'.$ticket->title.'","autoReduce": false,"Demand":[{"PriceTypeCode":"A","Quantity":1,"Admits":1,"offerCode":"","qualifierCode":"","entitlement":"","Customer":{}}],"Fees":[{"Type":"5","Code":"W"}]}\' -v https://api.etixdubai.com/baskets', $output, $return);
                    
                     // getting basket id
                     if(isset($output))
                     {
                        $op = json_decode($output[0]);
                        $basket_id = $op->Id;
                     }
                 }
				 $options = array(

							'event_date_id' => $ticket->event_date_id,

							'event_ticket_id' => $ticket->event_ticket_id,

							'seat_id' => $ticket->seat_id,

							'available_qty' => 1,

							'seat_details' => $ticket->title.'/'.$ticket->row.'/'.$ticket->name,
							
							//'session_id' => $session_id,

							'event_id' => $ticket->event_id, 
                     
                            'basket_id' => $basket_id );

					

				 $id = $ticket->event_id;			

				 ksort($options);

				 $rowid = md5($id . serialize($options));

				 

				// $usertickettemp = UserTicketTemp::find($usertickettemp->ticket_id);

				 //$usertickettemp->session_id = $session_id;

				// $usertickettemp->row_id = $rowid;

				// $usertickettemp->save();

				 

				 $price = Session::get('nbdpromo'.$ticket->event_id)?$ticket->price -(Session::get('nbdpromo'.$ticket->event_id)*$ticket->price):$ticket->price;

				 //cart insert

				 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
				 
                 
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 
				 // updating currency
				 $currency = CurrencyHelper::getEventCurrency($ticket->event_id);
				 Session::put('currency',$currency[0]);

				 $usertickettemp = new UserTicketTemp();

				 $usertickettemp->session_id = $session_id;

				 $usertickettemp->row_id = $rowid;

				 $usertickettemp->event_id = $ticket->event_id;

				 $usertickettemp->status = 1;

				 $usertickettemp->price = $ticket->price;

				 $usertickettemp->user_id = (Session::has('user')?Session::get('user')->id:0);

				 $usertickettemp->event_ticket_id = $ticket->event_ticket_id;

				 $usertickettemp->event_date_id = $ticket->event_date_id;

				 $usertickettemp->qty = 1;

				 $usertickettemp->seat_id = $ticket->seat_id;
				 
				 $usertickettemp->seat_details = $ticket->title.'/'.$ticket->row.'/'.$ticket->name;

				 $usertickettemp->save();
                 
                 
                 
                 /*Log::info('CART ITEM'.$rowid);
                 
                 if(Cart::associate('Ticket')->get("'".$rowid."'"))
				 {
				 	Log::info('CART ITEM  ADDED');
				 }
				 else
				 	Log::info('CART ITEM NOT ADDED');*/
                 
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);
                 $data = Cart::associate('Ticket')->add($id,$ticket->title.' - '.$ticket->row.'/'.$ticket->name,1, $price, $options);

				 $response = array('message' => 'Added');

				 return json_encode($response);

			 }

		 	 $response = array('message' => 'Someone booked');

			 return json_encode($response);

		 }

		 if($action == 'delete')

		 {

		 	$session_id = Session::getId();

			$user_id = (Session::has('user')?Session::get('user')->id:0);

			

			$removeticket = UserTicketTemp::where('event_date_id','=',$ticket->event_date_id)->where('seat_id','=',$ticket->seat_id)->where('event_id','=',$ticket->event_id)->where('status','=',1)->where('session_id','=',$session_id)->where('user_id','=',$user_id)->first();

			if($removeticket)

			{

				$row_id = $removeticket->row_id;

			

				$temptickets = UserTicketTemp::where('event_date_id','=',$ticket->event_date_id)->where('seat_id','=',$ticket->seat_id)->where('event_id','=',$ticket->event_id)->where('status','=',1)->where('session_id','=',$session_id)->where('user_id','=',$user_id)->delete();

			

				

			

				if($temptickets)

				{
                                        $cart = Cart::associate('Ticket')->remove($row_id);
                                        
					$response = array('message' => 'Deleted');

					return json_encode($response);

				}

				else

				{

					$response = array('message' => 'you cannot unselect');

					return json_encode($response);

				}

			}

			

			

		 }		 //. update the cart items in usertickettemp table

		

		

		return;

							

		

	}

	

	public function cartUpdate()

	{

		$id = Input::get('rowid');

		$qty = Input::get('qty');

		$uid = Input::get('uid');

		$available_qty = 0;

		$current_cart_qty = Cart::associate('Ticket')->get($id)->qty;

		$available_qty = $this->getTicketAvailablity($id);

		if($qty<=0)

			return $response = array('status' => 'failed','uid' => $uid,'message' => 'Minimum should be 1.');

		if($available_qty >= $qty || $current_cart_qty > $qty)

		{

			$usertempticket = UserTicketTemp::where('row_id','like',$id)->update(array('qty' => $qty));

			$cart = Cart::associate('Ticket')->update($id,array('qty' => $qty));
			
			$current_event_id = Cart::associate('Ticket')->get($id)->id;
			$amount = Cart::associate('Ticket')->get($id)->subtotal;
			
			$itemTotal =  Cart::associate('Ticket')->get($id)->subtotal;
			$cartTotal =  Cart::associate('Ticket')->total();
				
			if(Session::has('promo.promo_value_'.$current_event_id))
			{
				$content_qty = Cart::associate('Ticket')->searchEventQty($current_event_id);
				$promo_code_used_count = PromoCodeUser::where('promo_code','like',Session::get('promo.promo_code_'.$current_event_id))->count();
				$promocodes = PromoCode::where('promo_code','like',Session::get('promo.promo_code_'.$current_event_id))->first();
				
				$avail_qty = $promocodes->promo_max_limit - $promo_code_used_count;
				if( $avail_qty < $content_qty)
				{
					$response = array('status' => 'exceeded','uid' => $uid,'message' => 'You can buy upto '.$avail_qty.' using promo code');
					return $response;
				}
				
				$promo_amount =  Session::get('promo.ispercentage_'.$current_event_id) ?  Session::get('promo.promo_value_'.$current_event_id) * $amount : $content_qty * Session::get('promo.promo_value_'.$current_event_id);
				$itemTotal = $itemTotal - $promo_amount;
				$cartTotal = $cartTotal - $promo_amount;
			}
			$response = array('status' => 'updated', 'itemTotal' => $itemTotal, 'cartTotal' => $cartTotal,'uid' => $uid,'qty' => $qty,'totalItem' => Cart::associate('Ticket')->count());

		}

		else

			$response = array('status' => 'exceeded','uid' => $uid,'message' => 'You can buy upto '.$available_qty);

			

 		return $response;

	}

	public function getTicketAvailablity($id)

	{

			$rowdetails = Cart::associate('Ticket')->get($id);

			//print_r($rowdetails);

			$ticket_id = $rowdetails->options->event_ticket_id;

			$event_date_id = $rowdetails->options->event_date_id;

			return EventsHelper::getAvailableTickets($ticket_id);

			

	}

	

	public function addSeats() {

        //$data =  Input::all();

        $event_date = Input::get('event_date_id');

        $event_id = Input::get('event_id');

		$event_title = Input::get('event_title');

		$ticket_type_id = Input::get('ticket_type_id');

		$seat_id = Input::get('seat_id');

        

        return Redirect::to('checkout')->with("Ticket added");

    }

	

	 public function removecart()

	 {

		$id = Request::segment(2);

		$cart = Cart::associate('Ticket')->remove($id);
		Session::forget('promo');
		
		$usertempticket = UserTicketTemp::where('row_id','like',$id)->where('status','=',1)->delete();

		return Redirect::to('checkout');

	 }
	
	public function clearCart()
	{
		$intial_cart = Cart::content();
		foreach($intial_cart as $row)
		{
			$usertempticket = UserTicketTemp::where('row_id','like',$row->rowid)->where('status','=',1)->delete();
			$cart = Cart::associate('Ticket')->remove($row->rowid);
		}
		return Redirect::to('/');
	 }
	 

    public function checkout() {

	    $session_id = Session::getId();
        
		$tickets = UserTicketTemp::where('session_id','like',$session_id)->where('status','=',1)->get();
		
		
		$cart_status = '';
		
		$cart_content = Cart::content();
		$session_id = Session::getId();
        $ticketcount = 0;
		foreach($cart_content as $row)
		{
			//print_r($row);
			$rowid = $row->rowid;
			if($row->options->has('seat_id'))
			{
				//$temptickets = UserTicketTemp::where('row_id','=',$rowid)->where('session_id','=',$session_id)->count();
				$temptickets = UserTicketTemp::where('row_id','=',$rowid)->count();
				if($temptickets > 0)
					$temptimeupdate = UserTicketTemp::where('row_id','=',$rowid)->update(array('created_at' => date('Y-m-d H:i:s')));
				else
				{
					$removecart = Cart::associate('Ticket')->remove($rowid);
					$cart_status = 'Woops...some one booked your seat(s) in meantime.. Try with other seat(s)';
				}
				
				$ticketcount = UserTicket::where('event_date_id','=',$row->options->event_date_id)
							->where('event_id','=',$row->options->event_id)
							->where('seat_id','=',$row->options->seat_id)
							->where('is_canceled','=',0)
							->count();
				if($ticketcount > 0)
				{
					$removecart = Cart::associate('Ticket')->remove($rowid);
					$cart_status = 'Woops...some one booked your seat(s) in meantime.. Try with other seat(s)';
				}
							
			}
		}
		
		
		
        
        foreach($tickets as $ticket)
        {
            if(!Cart::associate('Ticket')->get($ticket->row_id))
            {
			 	  $seats = explode('/',$ticket->seat_details);
                  $options = array(

							'event_date_id' => $ticket->event_date_id,

							'event_ticket_id' => $ticket->event_ticket_id,

							'seat_id' => $ticket->seat_id,

							'available_qty' => 1,

							'seat_details' => $ticket->seat_details,
							
							//'session_id' => $session_id,

							'event_id' => $ticket->event_id);


				 $id = $ticket->event_id;			

				 ksort($options);

				 $rowid = md5($id . serialize($options));

			     $price = Session::get('nbdpromo'.$ticket->event_id)?$ticket->price -(Session::get('nbdpromo'.$ticket->event_id)*$ticket->price):$ticket->price;
                if(isset($seats[1]) && isset($seats[0]) && isset($seats[2]) )
			     {
				    $data = Cart::associate('Ticket')->add($id,$seats[0].' - '.$seats[1].'/'.$seats[2],1, $price, $options);
                 }	 
				 $usertempticket = UserTicketTemp::where('row_id','like',$ticket->row_id)->update(array('row_id' => $rowid));

             }
        }
  
        $intial_cart = Cart::content();
		foreach($intial_cart as $row)
            EventsHelper::isValidEvent($row->options->event_id,$row->rowid);

		//regenerate cart content
		$cart = Cart::content();

        return View::make('cart.site.checkout', array('cart' => $cart,'cart_status' => $cart_status));

    }
	
	public function appCart() 
	{
	  $userdata = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'status' => 1,
            'is_activated' => 1,
            'is_facebook_login' => 0
        );
        
		$user = $user_detail = User::where('email', '=', $userdata['email'])->get(array('id', 'first_name', 'last_name', 'email', 'password', 'status'))->first();
        if ($user != NULL && $user->email == $userdata['email'])
		{

            $check = Hash::check(Input::get('password'), $user->password);
            if ($check)
			{
						$session_id = Session::getId();
						
						$tickets = UserTicketTemp::where('session_id','like',$session_id)->where('status','=',1)->get();
						
						$cart_status = '';
						
						$cart_content = Cart::content();
						$session_id = Session::getId();
						$ticketcount = 0;
						foreach($cart_content as $row)
						{
							//print_r($row);
							$rowid = $row->rowid;
							if($row->options->has('seat_id'))
							{
								//$temptickets = UserTicketTemp::where('row_id','=',$rowid)->where('session_id','=',$session_id)->count();
								$temptickets = UserTicketTemp::where('row_id','=',$rowid)->count();
								if($temptickets > 0)
									$temptimeupdate = UserTicketTemp::where('row_id','=',$rowid)->update(array('created_at' => date('Y-m-d H:i:s')));
								else
								{
									$removecart = Cart::associate('Ticket')->remove($rowid);
									$cart_status = 'Woops...some one booked your seat(s) in meantime.. Try with other seat(s)';
								}
								
								$ticketcount = UserTicket::where('event_date_id','=',$row->options->event_date_id)
											->where('event_id','=',$row->options->event_id)
											->where('seat_id','=',$row->options->seat_id)
											->where('is_canceled','=',0)
											->count();
								if($ticketcount > 0)
								{
									$removecart = Cart::associate('Ticket')->remove($rowid);
									$cart_status = 'Woops...some one booked your seat(s) in meantime.. Try with other seat(s)';
								}
											
							}
						}
						
						foreach($tickets as $ticket)
						{
							if(!Cart::associate('Ticket')->get($ticket->row_id))
							{
								  $seats = explode('/',$ticket->seat_details);
								  $options = array(
				
											'event_date_id' => $ticket->event_date_id,
				
											'event_ticket_id' => $ticket->event_ticket_id,
				
											'seat_id' => $ticket->seat_id,
				
											'available_qty' => 1,
				
											'seat_details' => $ticket->seat_details,
											
											//'session_id' => $session_id,
				
											'event_id' => $ticket->event_id);
				
				
								 $id = $ticket->event_id;			
				
								 ksort($options);
				
								 $rowid = md5($id . serialize($options));
				
								 $price = Session::get('nbdpromo'.$ticket->event_id)?$ticket->price -(Session::get('nbdpromo'.$ticket->event_id)*$ticket->price):$ticket->price;
								if(isset($seats[1]) && isset($seats[0]) && isset($seats[2]) )
								 {
									$data = Cart::associate('Ticket')->add($id,$seats[0].' - '.$seats[1].'/'.$seats[2],1, $price, $options);
								 }	 
								 $usertempticket = UserTicketTemp::where('row_id','like',$ticket->row_id)->update(array('row_id' => $rowid));
				
							 }
						}
				  
						$intial_cart = Cart::content();
						foreach($intial_cart as $row)
							EventsHelper::isValidEvent($row->options->event_id,$row->rowid);
				
						//regenerate cart content
						$cart = Cart::content();
				
						return View::make('cart.app.cart', array('cart' => $cart,'cart_status' => $cart_status));
				
					
			}
		}
    }
	
	public function billingsummary() {

		$cart = Cart::content();

		$response = array('message' => 'Added in the cart.','cartcontent' => ''.View::make('cart.site.billingsummary', array('cart' => $cart)));

		return json_encode($response);

    }



    public function temp() {

        

        Mail::send('organizer.mails.pdfgeneration', array(

            'first_name' => 'Ponnu',

            'last_name' => 'chamy',

            'email' => 'ponnuchamyk@gmail,com'

                ), function($message) {



                    $message->to('prav82c@gmail.com')->subject('Organizer account de-activated');

                    //$pdf  = 'G:\projects\xampp\htdocs\ponnuchamy\test.pdf';

                    $dompdf = new DOMPDF();

                    $dompdf->load_html('<p>welcome indid</p>');

                    $dompdf->render();

                    //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

                    $output = $dompdf->output();

                    $pdf = $_SERVER['DOCUMENT_ROOT'] . '\tixbox' . rand() . '.pdf';

                    file_put_contents($pdf, $output);

                    $message->attach($pdf);

                });

    }



    public function barcode() {

        echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T");

        exit();

    }

    

    

     public function ticket(){

          $pattern = base64_encode(file_get_contents(URL::asset('img/site/pat02.png')));

          $logo = base64_encode(file_get_contents(URL::asset('img/site/logo.png')));

          

         

          $code = base_path().'/'.rand().rand();

          $QRCODE =  DNS2D::getBarcodePNG($code, "QRCODE");

          

          

          $voucher = View::make('cart.pdf.ticket',array(

              'userticket'=>'TI20123',

              'QRCODE'=>$QRCODE,

              'logo'=>$logo,

			  'pattern'=>$pattern));

          

          $dompdf = new DOMPDF();

          $dompdf->load_html($voucher);

          $dompdf->render();

          $dompdf->stream("ticket.pdf", array("Attachment" => false)); 

        

    }



}