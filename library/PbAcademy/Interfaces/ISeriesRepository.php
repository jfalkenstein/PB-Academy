<?php

/**
 *
 * @author jfalkenstein
 */
interface ISeriesRepository extends IRepository {
    public function GetById($id, $default=null);
    public function GetAllSeries();
}
