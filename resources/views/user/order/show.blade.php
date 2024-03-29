@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Order
{{--    <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>--}}
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>S.N.</th>
            <th>Order No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Quantity</th>
{{--            <th>Charge</th>--}}
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>ETB{{number_format($order->total_amount,2)}}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">ORDER INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Order Number</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Order Status</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td> : ETB{{number_format($order->total_amount,2)}}</td>
                    </tr>
                    <tr>
                        <td>Products</td>
                        <td> :
                            <table>
                                <tr>
                                    <th>Product Title</th>
                                    <th>Quantity</th>
                                    <th>Sub total</th>
                                </tr>
                                @foreach($order->cart as $orderCarts)
                                    <tr>
                                        <td>{{$orderCarts->product->title}}</td>
                                        <td>{{$orderCarts->quantity}}</td>
                                        <td>ETB{{number_format($orderCarts->price,2)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
{{--                    <tr>--}}
{{--                      <td>Payment Method</td>--}}
{{--                      <td> : @if($order->payment_method=='cod') Cash on Delivery @else Paypal @endif</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Payment Status</td>--}}
{{--                        <td> : {{$order->payment_status}}</td>--}}
{{--                    </tr>--}}
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

