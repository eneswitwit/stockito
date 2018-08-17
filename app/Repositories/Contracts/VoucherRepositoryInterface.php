<?php

namespace App\Repositories\Contacts;

use Illuminate\Http\Request;

/**
 * Interface VoucherRepositoryInterface
 *
 * @package App\Repositories\Contacts
 */
interface VoucherRepositoryInterface
{
	/**
	 * @return mixed
	 */
	public function all();

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function get($id);

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function create(array $params);

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function delete($id);

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function update($id, $attributes);
}

?>