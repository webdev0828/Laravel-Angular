<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('PlansTableSeeder');
		// $this->call('UserTableSeeder');
		// $this->call('OrderTableSeeder');
		// $this->call('TrackTableSeeder');
		// $this->call('NewsCategoryTableSeeder');
		// $this->call('NewsTrackTableSeeder');
		// $this->call('FeaturesTableSeeder');
		$this->call('SettingTableSeeder');
		// $this->call('TrackGenresTableSeeder');
		// $this->call('CompetitionTableSeeder');
		// $this->call('CampaignTableSeeder');

	}

}
