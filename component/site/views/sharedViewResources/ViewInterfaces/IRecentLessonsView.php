<?php

/**
 *
 * @author jfalkenstein
 */
interface IRecentLessonsView{
    /**
     * @return []Lesson
     */
    public function GetRecentLessons();
    public function GetRecentLessonTitle();
    const RECENT_LESSON_INCLUDE = '/../../sharedViewResources/tmpl/RecentLessons.php';
}
