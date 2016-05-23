<?php

/**
 *
 * @author jfalkenstein
 */
interface IListLessonsView {

    const LIST_LESSONS_INCLUDE = '/../../sharedViewResources/tmpl/ListLessons.php';
    
    /**
     * @return bool
     */
    public function ShowSeriesPositionInTitle();
    
    /**
     * @return []Lesson
     */
    public function GetLessons();
    
    public function GetLink();
}
