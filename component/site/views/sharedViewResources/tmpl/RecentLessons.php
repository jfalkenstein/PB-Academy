<?php
/* To use this include, you must implement IRecentLessonsView in the view and 
 * IRecentLessonsModel in the model. */
/* @var $this IRecentLessonsView */
/* @var $firstLesson Lesson */
if(is_a($this, 'IRecentLessonsView')){
    $this->RecentLessons = $this->GetRecentLessons();
    if(isset($this->RecentLessons[0])){
        $firstLesson = $this->RecentLessons[0];
    }
    if(isset($firstLesson)):
?>
    <div id="recentLessons">
        <div class="latestLesson">
            <h2><?php echo $this->GetRecentLessonTitle(); ?></h2>
            <h3 id="latestTitle">
                <a href="<?php echo $firstLesson->GetLink(); ?>">
                    <?php echo $firstLesson->Title ?>
                </a>
            </h3>
            <div class="shadowbox">
                <a href="<?php echo $firstLesson->GetLink(); ?>">
                    <img src="<?php echo $firstLesson->ImagePath; ?>">
                </a>
                <img class="superimpose" src="/components/com_pbacademy/images/icons/teach.png">
            </div>
            <div class="latestLessonDescrip"><?php echo $firstLesson->Description; ?></div>
            <div class="latestLessonDetails">
                <div class="latestLessonCategory"><span class="detLabel">From:</span> 
                    <a href="<?php echo $firstLesson->Category->GetLink(); ?>">
                        <?php echo $firstLesson->Category->Name; ?>
                    </a>
                </div>
                <div class="latestLessonSeries">
                    <?php if($firstLesson->Series): ?><span class="detLabel">Series:</span>
                    <a href="<?php echo $firstLesson->Series->GetLink(); ?>">
                        <?php echo $firstLesson->Series->SeriesName; ?>
                    </a>
                    <span class="seriesPosition"> (#<?php echo $firstLesson->TrueSeriesPosition()?>)</span>
                    <?php endif; ?>
                </div>
                <div class="latestLessonDate">
                    <span class="detLabel">Posted: </span>
                    <span class="detDate"><?php echo $firstLesson->DatePublished(); ?></span>
                </div>
            </div>
        </div>
        <div id="otherRecentLessons">
            <?php 
                for($i = 1; $i < count($this->RecentLessons);$i++) :
                    /* @var $currentLesson Lesson */
                    $currentLesson = $this->RecentLessons[$i];
                ?>
                    <div class="lessonThumb" <?php if($i == (count($this->RecentLessons) - 1)) echo 'id="lastLesson"';?>>
                        <h3 class="thumbtitle">
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
                                <?php if($currentLesson->Series): ?><span class="detLabel">Series:</span>
                                <a href="<?php echo $currentLesson->Series->GetLink(); ?>">
                                    <?php echo $currentLesson->Series->SeriesName; ?>
                                </a>
                                <span class="seriesPosition"> (#<?php echo $currentLesson->TrueSeriesPosition()?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="thumbdate">
                                <span class="detLabel">Posted: </span>
                                <span class="detDate"><?php echo $currentLesson->DatePublished();?></span>
                            </div>
                        </div>
                    </div>
            <?php endfor; ?>
        </div>
    </div>
<?php endif; ?>
<?php }; ?>