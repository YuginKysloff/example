<?php
	/**
	 * Created by PhpStorm.
	 * User: DZB
	 * Date: 21.02.2018
	 * Time: 12:47
	 */

	Route::namespace('Crm\Modules\PhotoRatingModule\Controllers')
		->group(function () {
			Route::get('photo-rating', 'PhotoRatingController@getRatingPage')
				->name('web.photo-rating.rating-page');

			Route::post('photo-rating/rate', 'PhotoRatingController@postRateProduct')
				->name('web.photo-rating.rate-product');
		});
