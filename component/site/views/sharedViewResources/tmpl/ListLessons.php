<?php
/* @var $this IListLessonsView */
//To use this view, you must implement IListLessonsView

if(is_a($this, 'IListLessonsView')){

$numThumbsPerPage = 12;
$ViewStart;
$ViewEnd;
$numAlreadyViewed = 0;
if($this->PageNumber === 1){//If this is the first page...
    $ViewStart = 0;
    //Decide the index of the last item to be displayed.
    $ViewEnd = (
                //Is the number of lessons greater than the number per page?
                (count($this->GetLessons()) > $numThumbsPerPage)
                ? $numThumbsPerPage  //If so, then end by displaying the max nubmer.
                : count($this->GetLessons()) //Else end by displaying the number remaining.
              ) - 1; //Index is zero-based.
}else{ //If this is a page number greater than 1...
    //The number already viewed is the starting index. This is calculated by the number to be displayed
    //per page multiplied by the page number, subtracted by 1.
    $numAlreadyViewed = $ViewStart = $numThumbsPerPage * ($this->PageNumber - 1);
    $ViewEnd = (
                //Is the # of lessons total (less the n# already viewed) greater than the number to display per page?   
                ((count($this->GetLessons()) - $numAlreadyViewed) > $numThumbsPerPage)
                ? $numThumbsPerPage + $numAlreadyViewed //If so, then stop after displaying #thumbs per page more...
                : count($this->GetLessons()) //Else, stop at the index of the total number of lessons.
              ) - 1; //Index is zero-based.
}
?>
<?php if($this->PageNumber != 1): ?>
    <h3>Page <?php echo $this->PageNumber; ?></h3>
<?php endif;?>
    <div class="lessonList">
<?php 
    for($i = $ViewStart; $i < $ViewEnd + 1;$i++) :
        /* @var $currentLesson Lesson */
        $currentLesson = $this->GetLessons()[$i];?>
        <div class="lessonThumb">
            <h3 class="thumbtitle">
                <?php if($this->ShowSeriesPositionInTitle()) : ?>
                #<?php echo $currentLesson->TrueSeriesPosition();?>. 
                <?php endif;?>
                <a href="<?php echo $currentLesson->GetLink(); ?>">
                    <?php echo $currentLesson->Title; ?>
                </a>
            </h3>
            <div class="shadowbox">
                <a href="<?php echo $currentLesson->GetLink(); ?>">
                    <img src="<?php echo $currentLesson->ImagePath; ?>">
                </a>
                <img class="superimpose" src="/components/com_pbacademy/images/icons/teach.png">

            </div>
            <div class="thumbFooter">
                <div class="thumbcategory"><span class="detLabel">From:</span>
                    <a href="<?php echo $currentLesson->Category->GetLink(); ?>">
                        <?php echo $currentLesson->Category->Name; ?>
                    </a>
                </div>
                <div class="thumbseries">
                    <?php if($currentLesson->Series && !$this->ShowSeriesPositionInTitle()): ?><span class="detLabel">Series:</span>
                    <a href="<?php echo $currentLesson->Series->GetLink(); ?>">
                        <?php echo $currentLesson->Series->SeriesName; ?>
                    </a>
                    <span class="seriesPosition"> (#<?php echo $currentLesson->TrueSeriesPosition()?>)</span>
                    <?php endif; ?>
                </div>
                <div class="thumbdate">
                    <span class="detLabel">Posted: </span>
                    <span class="detDate"><?php echo $currentLesson->DatePublished(); ?></span>
                </div>
            </div>
        </div>
<?php endfor; ?>
    </div>
<?php if($this->PageNumber != 1) : ?>
    <div id="prevPage">
        <h5>
            <span class="icon-arrow-left-4"></span>
            <a href="<?php echo $this->GetLink() . '&page='. ($this->PageNumber - 1); ?>">
                Previous page
            </a>
        </h5>
    </div>
<?php endif; ?>
<?php if(($numAlreadyViewed + $numThumbsPerPage) < count($this->GetLessons())) : ?>
    <div id="nextPage">
        <h5>
            <a href="<?php echo $this->GetLink() . '&page=' . ($this->PageNumber + 1); ?>">
                Next page
            </a>
            <span class="icon-arrow-right-4"></span>
        </h5>
    </div>
<?php endif; ?>
<?php } ?>