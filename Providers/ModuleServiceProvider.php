<?php
	/**
	 * Created by PhpStorm.
	 * User: DZB
	 * Date: 18.01.2018
	 * Time: 11:57
	 */

	namespace Modules\PhotoRatingModule\Providers;

	use Crm\ClientPhoto;
	use Illuminate\Support\Facades\Route;
	use Modules\CoreModule\Providers\CoreModuleServiceProvider;

	class ModuleServiceProvider extends CoreModuleServiceProvider {
		public function boot() {
			parent::boot();

			$this->loadViewsFrom(__DIR__ . '/../Views', 'photoRating');
		}

		public function register() {
			parent::register();
		}

		protected function getDir() {
			return __DIR__;
		}
	}
