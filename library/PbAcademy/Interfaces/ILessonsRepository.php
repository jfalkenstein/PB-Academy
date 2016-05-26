<?php

/**
 * These are the required functions for a Lessons Repository (in addition to those added by IRepository).
 * @author jfalkenstein
 */
interface ILessonsRepository extends IRepository {
    
    public function GetLessonById($id, $default = null);
    public function GetLessonsForCategory($catId, $publishedOnly = true);
    public function GetRecentLessons($numberToGet = 4, $catId = null, $publishedOnly = true);
    public function GetAllLessons($publishedOnly = true);
    public function GetLessonsForSeries($seriesId, $publishedOnly = true);
    
}
