<?php
/**
 * @property Category $Category This is the category to which this lesson belongs.
 * @property LessonSeries $Series
 * @property ILessonsRepository $db 
 */
class Lesson{
    private $contentType;
    public $Id;
    public $Title; 
    public $Series; //Of type Series
    public $Category; //of type Category
    public $ImagePath; //Path to cover image
    public $SourceCredit; //If sourced externally
    public $Content; //various string representation, depending on contentType. - can include html
    public $Description; //string description - can include html content
    public $SeriesOrder; //The order in which this will appear in its associated series, if any.
    public $Published; //Whether or not the lesson is published.
    private $datePublished; //date
    
    
    public function __construct(ContentType $contentType, 
                                $title, 
                                Category $category,
                                $description = '',
                                $id = null,
                                $content = null,
                                LessonSeries $series = null, 
                                $imagePath = null, 
                                $sourceCredit = null,
                                $datePublished = null,
                                $seriesOrder = null,
                                $published = false){
        if(!$contentType instanceof ContentType){
            throw new Exception("Invalid Content Type.");
        }
        if(!$category instanceof Category){
            throw new Exception("Invalid Category.");
        }
        $this->contentType = $contentType;
        $this->Title = $title;
        $this->Series = $series;
        $this->Category = $category;
        $this->ImagePath = $imagePath;
        $this->SourceCredit = $sourceCredit;
        $this->Content = $content;
        $this->Description = $description;
        $this->datePublished = $datePublished;
        $this->Id = $id;
        $this->SeriesOrder = $seriesOrder;
        $this->Published = $published;
    }
    /**
     * This is the read-only ContentType that describes this lesson.
     * @return ContentType
     */
    public function ContentType(){
        return $this->contentType;
    }
    
    public function DatePublished(){
        return date('m/d/y',  strtotime($this->datePublished));
    }
    
    public function DateForSql(){
        return date('Y-m-d', strtotime($this->datePublished));
    }
    
    public function TrueSeriesPosition($publishedOnly = true){
        if(is_null($this->Series)){
            return '';
        }
        return $this->Series->GetLessonPosition($this, null, $publishedOnly)+1;
    }
    
    public function GetLink(){
        return UrlMaker::Lesson($this->Id);
    }    
    
    /**
     * PrintEmbedString:
     * This method will return the html string to embed the contentType into the lesson page.
     * It switechs on the contentType for this lesson, functioning differently depending on
     * the particular contentType.
     * 
     * The raw material for the returned embed code comes from the content property.
     */
    public function PrintEmbedString(){
        echo LessonEmbedder::EmbedLesson($this);
    }
}


