<?php

	namespace Modules\PhotoRatingModule\Requests;

	use Crm\Http\Requests\Request;

	class PhotoRatingRequest extends Request {

		public function rules() {
			return [
				'id'  => 'required|exists:order_products,id',
				'rate' => 'required|integer',
			];
		}
	}
