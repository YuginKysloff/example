<?php
	/**
	 * Created by PhpStorm.
	 * User: TPL
	 * Date: 03.05.2018
	 * Time: 11:18
	 */

	namespace Crm\Modules\PhotoRatingModule\Controllers;

	use Carbon\Carbon;
	use Crm\Modules\PhotoRatingModule\Jobs\SetPhotoRatingJob;
	use Crm\Order;
	use Crm\OrderProduct;
	use Modules\CoreModule\Controllers\CoreController;
	use Modules\CoreModule\Traits\CrmControllerTrait;
	use Modules\PhotoRatingModule\Repositories\OrderRepository;
	use Modules\PhotoRatingModule\Requests\PhotoRatingRequest;

	class PhotoRatingController extends CoreController {
		use CrmControllerTrait;

		public function getRatingPage() {
			$dateFrom = Carbon::createFromDate(2018, 5, 1)->startOfDay();
			//$dateFrom = Carbon::createFromDate(2018, 5, 21)->startOfDay();
			$dateTo = Carbon::now()->endOfDay();

			$orders = app(OrderRepository::class)->getListForFloristRating($dateFrom, $dateTo);

			$data['products'] = $orders
				->map(function (Order $order) {
					return $order->products;
				})
				->collapse()
				->filter(function (OrderProduct $model) {
					return $model->isBouquet() && is_null($model->photo_rating);
				})
				->sortByDesc('id')
				->take(15);

			return view('photoRating::rating', $data);
		}

		public function postRateProduct(PhotoRatingRequest $request) {
			$job = new SetPhotoRatingJob($request->only('id', 'rate'));
			$job->handle();
			$result['status'] = $job->getResult();

			return $result;
		}
	}
