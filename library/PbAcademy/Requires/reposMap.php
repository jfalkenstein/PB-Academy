<?php
/* @var $this RepoMapRegistry */
if(is_a($this,'RepoMapRegistry')){
    $this->Lessons = new LessonsRepository();
    $this->Categories = new CategoriesRepository();
    $this->ContentTypes = new ContentTypesRepository();
    $this->Series = new SeriesRepository();
}
