<?php

namespace App\Repositories\Contacts;

/**
 * Interface ProductRepositoryInterface
 *
 * @package App\Repositories\Contacts
 */
interface ProductRepositoryInterface
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
     * @param $attributes
     *
     * @return mixed
     */
    public function update($id, $attributes);
}