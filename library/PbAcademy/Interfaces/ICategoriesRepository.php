<?php

/**
 *
 * @author jfalkenstein
 */
interface ICategoriesRepository  extends IRepository{
    public function GetById($id, $default = null);
    public function GetAll();
}
