<div class="coverBox clearfix">
  <div class="cover">
    <div class="orgi_det">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h3>{{Lang::get('cart.Tix Cart')}}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div class="contbody">
  <div class="container">
    <div class="row">
      <div class="contwrap clearfix"><!--{{ @Form::open(array('url' => 'processpayment','method' => 'post'))}}-->
          <?php $total_amount=$total_qty=0; ?>
          @if(count($cart) > 0)
          
          <div class="col-sm-8">
              <a href="{{URL::to('cart/clear')}}" class="btn btn-grey btn-sm btn-block mbten"><i class="fa fa-trash-o"></i> {{Lang::get('cart.clear cart')}}</a>
          <?php $total_amount=$total_qty=0;
        $event_ticket_id_log = ''; 
        foreach($cart as $row) : //echo '<pre>';print_r($row);echo '</pre>';
			$event_id = $row->options->event_id;
			$row_id = $row->rowid;
			if($valid_eveent = EventsHelper::isValidEvent($event_id,$row_id))
			{
			 $total_amount = $total_amount+$row->subtotal; ?>
          <div class="well well-sm mbten">
            <div class="row">
              <div class="col-sm-2">
                <div class="eve_thumb animated zoomIn"> <?php echo EventsHelper::getEventImage(($row->options->has('event_id') ? $row->options->event_id : 0),'small');?></div>
              </div>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="">
                      <div class="mbten">
                        <div class="headlink"><?php echo EventsHelper::getEventUrl(($row->options->has('event_id') ? $row->options->event_id : 0),$row->name);?></div>
                      </div>
                      <div><i class="fa fa-calendar fa-fw"></i> <?php echo EventDateHelper::getEventDate(($row->options->has('event_date_id') ? $row->options->event_date_id : 0));?></div>
                      <div class="mbten"><i class="fa fa-clock-o fa-fw"></i> <?php echo EventDateHelper::getEventTime(($row->options->has('event_date_id') ? $row->options->event_date_id : 0));?></div>
                      @if(!$row->options->seat_id)
                      <div class="input-group col-sm-3">
                        <input type="hidden" name="rowid" id="rowid_{{$row->options->event_date_id.$row->options->event_ticket_id}}" value="{{$row->rowid}}" >
                        <input type="text" value="{{$row->qty}}" class="form-control input-sm" name="qty" id="qty_{{$row->options->event_date_id.$row->options->event_ticket_id}}" />
                        <span class="input-group-btn"> <a href="javascript:void(0);" data-target="{{$row->options->event_date_id.$row->options->event_ticket_id}}" class="btn btn-sm btn-red openbutton cartupdate"><i class="fa fa-refresh"></i></a> </span> </div>
                      @endif
                      <div class="cart-update_{{$row->options->event_date_id.$row->options->event_ticket_id}}"></div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="text-right mbten">{{ HTML::i_link('removecart/'.$row->rowid, 'fa fa-trash-o', 'remove', array('class' => 'btn btn-red btn-xs'),'') }}</div>
                    <div class="text-right">
                      <div class=""> </div>
                      <?php $currency = CurrencyHelper::getEventCurrency($event_id);?>
                      <span class="badge"><span class="itemQty_{{$row->options->event_date_id.$row->options->event_ticket_id}}">{{$row->qty}}</span> x</span> {{$currency[0]->is_prefix?$currency[0]->symbol:''}} <span class="price_{{$row->options->event_date_id}}"><?php echo $row->price;?></span> {{$currency[0]->is_prefix?'':$currency[0]->symbol}} </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } endforeach;	?>
        </div>
        <div class="col-sm-4">
          
          <div class="well well-sm mbten" style="height:85px;"><div class="red text-center">
                <label class="control-label">{{Lang::get('cart.Time Remaining')}}</label>
              <div class="finalAmt "><div class="finalAmt"><span id="timer"></span></div></div>
                </div>
          </div>
          <div class="well">
            <div class="promoBox text-center">
              <div>{{Lang::get('cart.Enter Promocode (if available)')}}</div>
              <br />
              <div class="input-group col-sm-8 col-sm-offset-2">
                <input type="text" class="form-control" id="promocode" placeholder="ex:TIX25">
                <span class="input-group-btn">
                <button class="btn btn-silver promoApply" type="button"><i class="fa fa-check"></i> {{Lang::get('cart.Apply')}}</button>
                </span> </div>
              <div><br />
                <div class="text-center promo-applied"> @if(Session::has('promo'))
                  <button href="javascript:void(0);" id="removepromo" class="btn btn-xs btn-red promo-remove" ><i class="fa fa-times"> {{Lang::get('cart.clear promo')}}</i></button>
                @endif </div>
              </div>
            </div>
            <div class="ttlbox">
              <div class="text-center">{{Lang::get('cart.Total')}}</div>
              <?php $site_currency = Session::get('currency');?>
              <div class="finalAmt text-center"> {{$site_currency->is_prefix?$site_currency->symbol:''}} <span class="checkout_total">{{PaymentHelper::getTransactionAmount()}}</span> {{$site_currency->is_prefix?'':$site_currency->symbol}} </div>
            </div>
          </div>
          <div> 
              
                  {{ Form::open(array('url' => 'payment/info')) }}
                      {{ Form::button('<i class="fa fa-chevron-circle-right"></i> '.Lang::get('cart.Proceed to pay'),array('type'=>'submit', 'id'=>'proceedtopay', 'class'=>'btn btn-red btn-lg btn-block ddload','data-loading-text'=>'<i class="fa fa-refresh fa-spin"></i> '.Lang::get('cart.Proceeding...'))) }}
                  {{ @Form::close()}} 
              
          </div>
        </div>
        @else
        <div class="text-center">
            <h1 class="red vbig"><i class="fa fa-shopping-cart fa-fw"></i></h1>
            <h1>{{Lang::get('cart.OOPS..!! your cart is empty')}}</h1>
          <div class="subhero">{{Lang::get('cart.Lets go and buy something')}} <a href="{{ URL::asset('')}}">{{Lang::get('cart.here')}}</a></div>
        </div>
        @endif 
        <!--{{ @Form::close()}} --></div>
    </div>
  </div>
</div>