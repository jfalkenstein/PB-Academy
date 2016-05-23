<?php /* @var $this PbAcademyViewSeries */ ?>
<div class="headerSection">
    <div class="headerImage">
        <img src="<?php echo $this->ThisSeries->ImagePath; ?>">
    </div>
    <div class="headerDetails">
        <h4>About this Series:</h4>
        <?php echo $this->ThisSeries->Description; ?>
        <br/><br/>
        <span class="detLabel">Number of lessons:</span>
        <?php echo count($this->GetLessons()); ?>
    </div>
</div>
<div id="SeriesLessons">
    <h2>Lessons in this series:</h2>
    <?php include __DIR__ . IListLessonsView::LIST_LESSONS_INCLUDE;?>
</div>