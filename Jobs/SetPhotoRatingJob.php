<?php

	namespace Crm\Modules\PhotoRatingModule\Jobs;

	use Crm\Jobs\Job;
	use Crm\OrderProduct;
	use Illuminate\Queue\SerializesModels;

	class SetPhotoRatingJob extends Job {
		use SerializesModels;

		/**
		 * @var OrderProduct $product
		 */
		protected $product;

		/**
		 * @var int $rate
		 */
		protected $rate;

		/**
		 * @var bool $result
		 */
		protected $result;

		public function __construct($data) {
			$this->product = OrderProduct::findOrFail($data['id']);;
			$this->rate = $data['rate'];
		}

		public function handle() {
			$this->product->photo_rating = $this->rate;
			$this->result = $this->product->save();
		}

		/**
		 * @return string
		 */
		public function getResult() {
			return $this->result ? 'ok' : 'error';
		}
	}
