<?php

/**
 * These are the required functions for a Series Repository (in addition to those
 * required by IRepository).
 * @author jfalkenstein
 */
interface ISeriesRepository extends IRepository {
    public function GetById($id, $default=null);
    public function GetAllSeries();
}
