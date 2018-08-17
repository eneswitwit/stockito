<?php
namespace App\Transformers;

use App\Models\Brand;
use LukeVear\LaravelTransformer\AbstractTransformer;
use LukeVear\LaravelTransformer\TransformerEngine;

class BrandCreativesTransformer extends AbstractTransformer {
	/**
	 * @param \App\Models\Brand $model
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function run($model) : array
	{
		return [
			'id' => $model->id,
			'name' => $model->getNameAttribute(null),
			'email' => $model->user->email,
			'company' => $model->company,
			'position' => $model->pivot->role,
			'role' => Brand::$permission_names[$model->pivot->position],
		];
	}
}