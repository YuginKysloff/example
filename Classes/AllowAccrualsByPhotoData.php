<?php
	/**
	 * Created by PhpStorm.
	 * User: TPL
	 * Date: 17.05.2018
	 * Time: 17:39
	 */

	namespace Crm\Modules\PhotoRatingModule\Classes;

	use Carbon\Carbon;
	use Crm\Accrual;
	use Crm\Order;
	use Crm\OrderProduct;
	use Crm\User;
	use Illuminate\Support\Collection;
	use Modules\PhotoRatingModule\Repositories\OrderRepository;

	class AllowAccrualsByPhotoData {

		/**
		 * Добавление триггера разрешения/запрещения оплаты за день где есть не оцененные фото букетов
		 *
		 * @param User $user
		 * @param Collection $accruals
		 * @return Collection
		 */
		public function addUnratedPhotoStatisticsForUserAccruals(User $user, Collection $accruals) {
			return $accruals
				->map(function ($model) use ($user) {
					$orderedList = $model['list']->sortBy('date');

					$dateFrom = $orderedList->first()->date->startOfDay();
					$dateTo = $orderedList->last()->date->endOfDay();

					$orders = app(OrderRepository::class)->getListForFloristRating($dateFrom, $dateTo, $user->id);

					$model['list'] = $model['list']
						->map(function (Accrual $model, $date) use ($orders) {
							$date = Carbon::parse($date);

							$dateFrom = $date->copy()->startOfDay();
							$dateTo = $date->copy()->endOfDay();

							$model->allowAccrual = $orders
								->where('created_at', '>', $dateFrom)
								->where('created_at', '<=', $dateTo)
								->map(function (Order $model) {
									return $model->products;
								})
								->collapse()
								->filter(function (OrderProduct $model) {
									return $model->isBouquet() && is_null($model->photo_rating);
								})
							->count() === 0;

							return $model;
						});

					return $model;
				});
		}
	}
