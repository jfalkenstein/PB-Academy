<?php

/**
 * These are the required functions for a Categories Repository (in addition to those
 * required by IRepository).
 * @author jfalkenstein
 */
interface ICategoriesRepository  extends IRepository{
    public function GetById($id, $default = null);
    public function GetAll();
}
