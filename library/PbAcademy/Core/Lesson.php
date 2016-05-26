<?php
/**
 * Lessons are the most important unit of the entire PB Academy.
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
    
    /**
     * Formats the publish date to a human-readable format.
     * @return string
     */
    public function DatePublished(){
        return date('m/d/y',  strtotime($this->datePublished));
    }
    /**
     * Formats the date published to a format MySQL will accept.
     * @return string
     */
    public function DateForSql(){
        return date('Y-m-d', strtotime($this->datePublished));
    }
    
    /**
     * This will obtain the ACTUAL position this lesson holds in the Series. It
     * is contingent upon the seriesOrder, but is determined by the Series object
     * to which this Lesson belongs.
     * @param bool $publishedOnly Determins whether unpublished lessons should be
     * considered in determining this lesson's position.
     * @return int
     */
    public function TrueSeriesPosition($publishedOnly = true){
        if(is_null($this->Series)){
            return '';
        }
        return $this->Series->GetLessonPosition($this, null, $publishedOnly)+1;
    }
    
    /**
     * Obtains the link to this lesson.
     * @return type
     */
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


