@extends('crm.app')

@section('content')
	<div class="container-fluid">

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-6 text-bold">
						Проверка качества букетов
					</div>

					<div class="col-sm-6 text-right">
						@include('crm.partials.menu.actionMenu')
					</div>
				</div>
			</div>

			<div class="panel-body">

				<table class="table table-condenced">
					<thead>
					<tr>
						<th>Букет</th>
						<th class="text-center">Фото букета с сайта</th>
						<th class="text-center">Фото собранного букета</th>
						<th class="text-center">Оценка</th>
					</tr>
					</thead>
					<tbody data-container-photo-rating data-action="{{ route('web.photo-rating.rate-product') }}">
					@php
						/** @var \Crm\OrderProduct $product */
					@endphp
					@foreach($products as $product)
						<tr data-click>
							<td>
								<a target="_blank"
									 href="{{ \URL::action('Products\ProductController@getEdit', $product->product->id) }}">
									{{ $product->name  }}
								</a>
							</td>
							<td class="text-center">
								@if($product->product->getMedia('catalog')->count())
									<a href="{{ $product->product->getFirstMediaUrl('catalog') }}" target="_blank">
										<img src="{{ $product->product->getFirstMediaUrl('catalog') }}" style="width: 75%">
									</a>
								@else
									<img src="{{ asset('crm/images/foto.jpg') }}">
								@endif
							</td>
							<td class="text-center">
								@if($product->getMedia('order.product.photo')->count())
									<a href="{{ $product->getFirstMediaUrl('order.product.photo') }}" target="_blank">
										<img src="{{ $product->getFirstMediaUrl('order.product.photo') }}" style="width: 75%">
									</a>
								@else
									<img src="{{ asset('crm/images/foto.jpg') }}" style="width: 75%">
								@endif
							</td>
							<td class="text-center" data-product-id="{{ $product->id }}" data-order-id="{{ $product->order_id }}">
								<button class="btn btn-success btn-block" data-rate="1">
									<span class="glyphicon glyphicon-ok"></span>
									<b>Да</b>
								</button>
								<button class="btn btn-danger btn-block" data-rate="0">
									<span class="glyphicon glyphicon-remove"></span>
									<b>Нет</b>
								</button>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

			</div>
		</div>
	</div>
@stop
