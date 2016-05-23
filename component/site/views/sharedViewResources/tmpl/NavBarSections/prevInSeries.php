<?php /* @var $this NavBar */ ?>
<div class="NavBar-tooltip">Previous in Series: <span class="nav-name">"<?php echo $this->prevLesson->Title ?>"</span></div>
<span class="icon-previous navIcon"></span>
<a href="<?php echo $this->prevLesson->GetLink(); ?>">
    Previous
</a>