<?php

	namespace Crm\Modules\PhotoRatingModule\Jobs;

	use Carbon\Carbon;
	use Crm\Jobs\Job;
	use Crm\Order;
	use Crm\OrderProduct;
	use Modules\PhotoRatingModule\Repositories\OrderRepository;

	class UserRatingStatisticJob extends Job {
		/**
		 * @var int $floristId
		 */
		protected $floristId;

		/**
		 * Кол-во дней для сбора статистики
		 *
		 * @var int $period
		 */
		protected $period;

		/**
		 * @var array $result
		 */
		protected $result;

		public function __construct(int $floristId, int $period) {
			$this->floristId = $floristId;
			$this->period = $period;
		}

		public function handle() {
			$bouquets = $this->getBouquetsFromOrders();

			$this->result['total'] = $bouquets->count();

			$this->result['rated'] = $bouquets->filter(function (OrderProduct $bouquet) {
				return isset($bouquet->photo_rating);
			})->count();

			$this->result['passed'] = $bouquets->where('photo_rating', '=', 1)->count();

			$this->result['unPassed'] = $this->result['rated'] - $this->result['passed'];

			$this->result['percent'] = ($this->result['passed'] > 0) ?
				($this->result['passed'] / $this->result['rated'] * 100) :
				0;
		}

		protected function getBouquetsFromOrders() {
			$orders = $this->getOrders();

			return $orders
				->map(function (Order $model) {
					return $model->products;
				})
				->collapse()
				->filter(function (OrderProduct $model) {
					return $model->isBouquet();
				});
		}

		protected function getOrders() {
			$dateFrom = Carbon::now()->subDays($this->period)->startOfDay();
			$dateTo = Carbon::now()->endOfDay();

			return app(OrderRepository::class)->getListForFloristRating($dateFrom, $dateTo, $this->floristId);
		}

		/**
		 * @return array
		 */
		public function getResult() {
			return $this->result;
		}
	}
