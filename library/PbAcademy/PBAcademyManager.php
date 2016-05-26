<?php

/**
 * Class: PBAcademyManager
 * 
 * This class functions as the top-level interface for all the P&B Academy
 * core classes and methods. It "knows" what needs to be done in order to implement the various
 * actions the various classes can do.
 * 
 * PBAcademyManager implements the singleton pattern, thus making sure that the list of $allcategories
 * remains accurate. This helps reduce the number of db queries that are necessary.
 * 
 * To use this class, you need to obtain the singleton instance through GetInstance.
 * 
 * @property []string $ErrorMessages 
 * This is the sole public property of PBAcademyManager. It can be checked to ensure
 * no errors have cropped up. If so, they will be reported. Otherwise, it will remain empty.
 * @property RepoMapRegistry $repo
 * 
 * @author jfalkenstein
 */
require_once 'Requires/interfaces.php';
require_once 'Requires/coreClasses.php';
require_once 'RepoMapRegistry.php';


class PBAcademyManager {
    private static $instance;
    private $repo;
    private $allCategories = [];
    private $allSeries = [];
    private $allLessons = [];
    private $allContentTypes = [];
    public $ErrorMessages;
    
    /**
     * GetInstance:
     * This is the main gateway to obtaining an instance of the PBAcademyManager.
     * It enforces a singleton pattern to reduce db queries.
     * @return PBAcademyManager
     */
    public static function GetInstance(){
        if(!isset(self::$instance)){
            self::$instance = new PBAcademyManager();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->repo = RepoMapRegistry::getRegistry();
    }
    
    /**
     * This provides access to the repositories.
     * @return RepoMapRegistry
     */
    public function GetRepo(){
        return $this->repo;
    }
    
    /**
     * This method will load all categories into the private $allCategories field.
     */
    private function loadAllCategories(){
        $this->allCategories = $this->repo->Categories->GetAll();
    }
    
    /**
     * GetAllCategories:
     * This is the way the complete list of categories should be obtained. It will
     * stay updated as changes are made, but will not require repeated db queries
     * when the category list hasn't changed.
     * @return []_category
     */
    public function GetAllCategories(){
        if(count($this->allCategories) === 0){
            $this->loadAllCategories();
        }
        return $this->allCategories;
    }
    
    /**
     * This will return the category from specified by the Id.
     * @param int $id
     * @param mixed $default
     * @return Category
     */
    public function GetCategoryById($id, $default = null){
        $cats = $this->GetAllCategories();
        if(isset($cats[$id])) return $cats[$id];
        return $this->repo->Categories->GetById($id,$default);
    }
    
    /**
     * This will create a category and save it to the database. It will return the new
     * category if it created successfully.
     * @param string $name The name of the new category.
     * @param type $imagePath The path to the image that represents this category.
     * @return bool
     */
    public function CreateCategory($name, $description, $imagePath = null){
        try{
            $newCat = new Category($name, $description,null,$imagePath);
            $success = $this->repo->Categories->Save($newCat);
            $this->loadAllCategories();
            return $success;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    /**
     * This deletes the passed in category. It is essential that this category has
     * a valid Id; Otherwise, an exception will be thrown.
     * @param Category $categoryWithId
     * @throws Exception
     */
    public function DeleteCategory($catId){
        $success = $this->repo->Categories->Delete($catId);
        if(!$success){
            throw new Exception('There was a problem deleting the category.');
        }
        $this->loadAllCategories();
    }
    
    /**
     * This will update the category with the specified id and then save those changes
     * to the database.
     * @param int $id - The Id of the category to be updated.
     * @param string $name - The NEW name of the category; if null, the original name will remain.
     * @param string $description - The NEW description; if null, original will remain.
     * @param type $imagePath - The NEW imagePath; if null, original will remain.
     */
    public function UpdateCategory($id, $name = null, $description = null, $imagePath = null){
        $oldCat = $this->repo->Categories->GetById($id);
        /* @var $oldCat Category */
        $success = false;
        if(is_null($oldCat)){
            return false;
        }else{
            $oldCat->Name = (($name) ? $name : $oldCat->Name);
            $oldCat->Description = (($description) ? $description : $oldCat->Description);
            $oldCat->ImagePath = (($imagePath) ? $imagePath : $oldCat->ImagePath);
            $success = $this->repo->Categories->Save($oldCat, true);
            $this->loadAllCategories();
        }
        return success;
    }
    
    /**
     * This will obtain the lesson identified by the id from the db. If it cannot
     * be found, it will return the specified $default value.
     * @param int $id - The id of the lesson desired.
     * @param mixed $default - The value to return if the lesson cannot be found. 
     * @return Lesson
     */
    public function GetLesson($id, $default = null){
        if(is_null($id)) return $default;
        return $this->repo->Lessons->GetLessonById($id,$default);
    }
    
    /**
     * This will obtain an array of the most recent lessons, with the quanity of
     * whatever number is specified, or the max available--whichever is lesser.
     * @param int $numberToGet
     * @return []Lesson
     */
    public function GetRecentLessons($numberToGet = 4){
        return $this->repo->Lessons->GetRecentLessons($numberToGet);
    }
    
    /**
     * This will obtain an array of all lessons, ordered by date, descending.
     * @return []Lesson
     */
    public function GetAllLessons($publishedOnly = true){
        if(count($this->allLessons) === 0){
            $this->allLessons = $this->repo->Lessons->GetAllLessons($publishedOnly);
        }
        return $this->allLessons;
    }
    
    
    /**
     * This will create and save a new lesson.
     * @param ContentType $contentType
     * @param string $title
     * @param Category $category
     * @param string $datePublished
     * @param string $description
     * @param string $content
     * @param LessonSeries $series
     * @param string $imagePath
     * @param string $sourceCredit
     * @param int $seriesOrder
     * @return type
     */
    public function CreateLession(ContentType $contentType,
                                $title, 
                                Category $category,
                                $datePublished = null,
                                $description = null,
                                $content = null,
                                LessonSeries $series = null, 
                                $imagePath = null, 
                                $sourceCredit = null,
                                $seriesOrder = null,
                                $published = false){
        
        $les = new Lesson(
                $contentType, 
                $title,
                $category,
                $description,
                null,
                $content,
                $series,
                $imagePath,
                $sourceCredit,
                $datePublished,
                $seriesOrder,
                $published);
        $success = $this->repo->Lessons->Save($les);
        return $success;
    }
    
    /**
     * This updates a lesson. Any parameters not null (except for the Id) will be updated.
     * @param int $id
     * @param int $contentTypeId
     * @param int $categoryId
     * @param int $seriesId
     * @param string $title
     * @param string $description
     * @param string $content
     * @param string $imagePath
     * @param string $sourceCredit
     * @param int $seriesOrder
     * @return boolean Reports whether or not the update succeeded.
     */
    public function UpdateLession($id, 
                                $contentTypeId = null,
                                $categoryId = null,
                                $seriesId = null,
                                $title = null,
                                $description = null,
                                $content = null,
                                $imagePath = null,
                                $sourceCredit = null,
                                $seriesOrder = null,
                                $published = null
                                ){
        $lesson = $this->GetLesson($id);
        if(is_null($lesson)) return false;
        $ct = $this->GetContentTypeById($contentTypeId);
        $cat = $this->GetCategoryById($categoryId);
        $series = $this->GetSeries($seriesId);
        
        
        $ct = ($ct) ? $ct : $lesson->ContentType();
        $cat = ($cat) ? $cat : $lesson->Category;
        $series = ($series) ? $series : $lesson->Series;
        $title = ($title) ? $title : $lesson->Title;
        $description = ($description) ? $description : $lesson->Description;
        $content = ($content) ? $content : $lesson->Content;
        $imagePath = ($imagePath) ? $imagePath : $lesson->ImagePath;
        $sourceCredit = ($sourceCredit) ? $sourceCredit : $lesson->SourceCredit;
        $seriesOrder = (isset($seriesOrder)) ? $seriesOrder : $lesson->SeriesOrder;
        $published = (isset($published)) ? $published : $lesson->Published;
        $datePublished = $lesson->DateForSql();
        $newLesson = new Lesson($ct,$title,$cat,$description,$id,$content,$series,$imagePath,$sourceCredit,$datePublished,$seriesOrder,$published);
        $success = $this->repo->Lessons->Save($newLesson,true);
        return $success;
    }
    
    /**
     * DeleteLesson:
     * This will obtain the specified lesson, then call its delete method to remove it from
     * the database.
     * @param int $id - The id of the lesson you desire to use.
     * @param type $name Description
     */
    public function DeleteLesson($id){
        $success = $this->repo->Lessons->Delete($id);
        return $success;
    }
    
    /**
     * This will load all series from the db once, then return the running list of all series.
     * @return []LessonSeries
     */
    public function GetAllSeries(){
        if(count($this->allSeries) === 0){
            $this->loadAllSeries();
        }
        return $this->allSeries;
    }
    
    /**
     * Obtains an individual series by id. Returns $default if it cannot be located.
     * @param int $id
     * @param value $default The value to return if the series cannot be located.
     * @return LessonSeries
     */
    public function GetSeries($id, $default = null){
        $series = $this->GetAllSeries();    
        if(is_null($id)) return $default;
        if(isset($series[$id]))return $series[$id];
        return $this->repo->Series->GetById($id,$default);
    }
    
    /**
     * Creates and saves a new series to the Repo. Returns a success Boolean.
     * @param string $seriesName
     * @param string $imagePath
     * @param string $description
     * @return boolean
     */
    public function CreateSeries($seriesName, $imagePath = null, $description = null){
        $series = new LessonSeries($seriesName, $imagePath,null, $description);
        $success = $this->repo->Series->Save($series);
        $this->loadAllSeries();
        return $success;
    }
    
    /**
     * Updates an already existing series and returns a success Boolean.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $imagePath
     * @return boolean
     */
    public function UpdateSeries($id, $name=null, $description = null, $imagePath = null){
        $oldSeries = $this->repo->Series->GetById($id);
        /* @var $oldSeries LessonSeries */
        if(is_null($oldSeries)){
            return false;
        }
        $oldSeries->SeriesName = (($name) ? $name : $oldSeries->SeriesName);
        $oldSeries->Description = (($description) ? $description : $oldSeries->Description);
        $oldSeries->ImagePath = (($imagePath) ? $imagePath : $oldSeries->ImagePath);
        $success = $this->repo->Series->Save($oldSeries, true);
        $this->loadAllSeries();
        return $success;
    }
    
    /**
     * Deletes a series and returns a success boolean.
     * @param type $id
     * @return type
     */
    public function DeleteSeries($id){
        $success = $this->repo->Series->Delete($id);
        return $success;
    }

    /**
     * Loads all series into memory.
     */
    private function loadAllSeries(){
        $this->allSeries = $this->repo->Series->GetAllSeries();
    }
    
    /**
     * Returns an array of all contentTypes.
     * @return ContentType[]
     */
    public function GetContentTypes(){
        if(count($this->allContentTypes) === 0){
            $this->allContentTypes = $this->repo->ContentTypes->GetAll();
        }
        return $this->allContentTypes;
    }
    
    /**
     * Gets a specific content type by Id. Returns default if it cannot find it.
     * @param int $id
     * @param value $default
     * @return ContentType
     */
    public function GetContentTypeById($id, $default = null){
        $cts = $this->GetContentTypes();
        if(isset($cts[$id])) return $cts[$id];
        return $default;
    }
    
    
//    public function GetContentTypeByName($name, $default = null){
//        $name = strtolower($name);
//        $cts = $this->GetContentTypes();
//        foreach($cts as $ct){
//            if(strtolower($ct->Name) === $name) return $ct;
//        }
//        return $default;
//    }    
}
