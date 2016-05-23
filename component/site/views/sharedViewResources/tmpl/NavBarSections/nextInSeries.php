<?php /* @var $this NavBar */ ?>
<div class="NavBar-tooltip">Next in Series: <span class="nav-name">"<?php echo $this->nextLesson->Title ?>"</span></div>
<span class="icon-next navIcon"></span>
<a href="<?php echo $this->nextLesson->GetLink(); ?>">
    Next 
</a>