<?php

	namespace Modules\PhotoRatingModule\Repositories;

	use Carbon\Carbon;
	use Crm\Order;
	use Crm\OrderStatus;
	use Crm\Repositories\BaseOrdersRepository;
	use Illuminate\Support\Collection;
	use Crm\Order as Model;

	class OrderRepository extends BaseOrdersRepository {

		protected function getModelClass() {
			return Model::class;
		}

		public function getById(int $id) {
			return $this
				->startConditions()
				->with([
					'products.media',
					'products.product.media',
					'products.product.parameters',
					'products.product.policy',
				])
				->findOrFail($id);
		}

		/**
		 * Получение заказов флориста за период
		 *
		 * @param Carbon $dateFrom
		 * @param Carbon $dateTo
		 * @param int $floristId
		 * @return Collection
		 */
		public function getListForFloristRating(Carbon $dateFrom, Carbon $dateTo, int $floristId = 0): Collection {
			return $this
				->startConditions()
				->whereCreatedBetween($dateFrom, $dateTo)
				->when($floristId, function($query) use($floristId) {
					return $query->where('process_user_id', $floristId);
				})
				->whereHasStatus(OrderStatus::STATUS_READY)
				->with([
					'products.product.policy',
					'products.product.parameters',
				])
				->get();
		}
	}
