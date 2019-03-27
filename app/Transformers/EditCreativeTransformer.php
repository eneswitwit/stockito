<?php

namespace App\Transformers;
use LukeVear\LaravelTransformer\AbstractTransformer;

/**
 * Class EditCreativeTransformer
 * @package App\Transformers
 */
class EditCreativeTransformer extends AbstractTransformer {

	/**
	 * @param \App\Models\Creative $model
	 * @return array
	 */
	public function run($model) : array
	{
		return [
			'id' => $model->id,
            'brandCreativeId' => $model->brandCreativeId,
			'position' => $model->pivot->position,
			'role' => $model->pivot->role
		];
	}
}