<?php 
/* @var $activeLesson Lesson */
$activeLesson = $this->ThisLesson;
?>
<div id="MainLesson">
    <div id="LessonEmbed">
        <?php $activeLesson->PrintEmbedString();?>
    </div>
    <div id="AdditionalContent">
        <div id="DetailsBox">
            <div id="PubDate">
                <span class="detLabel">Posted: </span>
                <span class="detValue"> <?php echo $activeLesson->DatePublished(); ?></span>
            </div>
            <div id="Category">
                <span class="detLabel">From: </span>
                <span class="detValue">
                    <a href="<?php echo $activeLesson->Category->GetLink();?>">                   
                        <?php echo $activeLesson->Category->Name; ?>
                    </a>
                </span>
            </div>
            <?php if($activeLesson->Series) : ?>
            <div id="Series">
                <span class="detLabel">Series: </span>
                <span class="detValue">
                    <a href="<?php echo $activeLesson->Series->GetLink();?>">
                        <?php echo $activeLesson->Series->SeriesName ?>
                    </a>
                </span>
            </div>
            <?php endif; ?>
            <?php if($activeLesson->SourceCredit) : ?>
            <div id="SourceCredit">
                <span class="detLabel">Credit: </span>
                <span class="detValue"><?php echo $activeLesson->SourceCredit; ?></span>    
            </div>
            <?php endif ?>
        </div>

        <div id="LessonDescription">
            <strong>Description:</strong>
            <?php echo $activeLesson->Description ?>
        </div>
    </div>
</div>
