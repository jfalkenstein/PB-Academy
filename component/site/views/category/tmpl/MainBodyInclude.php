<div class="headerSection">
    <div class="headerImage">
        <img src="<?php echo $this->ThisCategory->ImagePath; ?>">
    </div>
    <div class="headerDetails">
        <h4>About this school:</h4>
        <?php echo $this->ThisCategory->Description; ?>
        <br/><br/>
        <span class="detLabel">Number of lessons:</span>
        <?php echo $this->ThisCategory->LessonCount(); ?>
    </div>
</div>

<div id="CategoryLessons">
    <h2>Lessons from this school:</h2>
    <?php include __DIR__ . IListLessonsView::LIST_LESSONS_INCLUDE; ?>
</div>