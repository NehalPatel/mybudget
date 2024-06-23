<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
	protected $guarded = ['id'];

	public static function get($settingId) {
		return Setting::where('id', $settingId)->first();
	}
	public static function get_settings()
	{
		$settings = [];
		$settings_row = Setting::all();

		foreach ($settings_row as $key => $setting) {
			$settings[$setting['name']] = $setting['value'];
		}

		return $settings;
	}
}
