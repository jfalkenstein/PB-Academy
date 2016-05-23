<?php 
    /* @var $this BaseViewMaster */
    $SeriesCount = count($this->AllSeries);
    $truncate;
    if($SeriesCount > 5){
        $truncate = true;
    }else{
        $truncate = false;
    }
?>
<h3 class="CategoryMenu">
    <a href="<?php echo UrlMaker::Categories();?>">
        <?php echo $this->MenuTitle ?>
    </a>
</h3>
<ul>
    <?php foreach($this->Categories as $cat) : ?>
    <li>
        <?php /* @var $cat Category */ ?>
        <a href="<?php echo $cat->GetLink();?>">
            <?php echo $cat->Name; ?>
        </a>
        <span class="count"> (<?php echo $cat->LessonCount(); ?>) </span>
    </li>
    <?php endforeach; ?>
</ul>
<?php if(count($this->AllSeries) > 0) : ?>
<h3 class="SeriesMenu">
    <a href="<?php echo UrlMaker::AllSeries();?>">
        Lesson Series:
    </a>
</h3>
<ul>
    <?php 
    $counter=1;
    foreach($this->AllSeries as $index => $ser) : 
        /* @var $ser LessonSeries */
    if($counter > 5) break;    
    ?>
    <li>
        <a href="<?php echo $ser->GetLink(); ?>">
            <?php echo $ser->SeriesName; ?>
        </a>
        <span class="count"> (<?php echo $ser->LessonCount(); ?>) </span>
    </li>
    <?php 
    $counter++;
    endforeach; ?>
</ul>
    <?php if($truncate) :?>
    <a href="<?php echo UrlMaker::AllSeries(); ?>">
        <span class="seeMore">See More...</span>
    </a>
    <?php endif; ?>
<?php endif; ?>