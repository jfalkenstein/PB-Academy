<?php
/*
 * This is the main page framework that the rest of the PB Academy front end fits
 * into. It includes the menu, the given "MainBodyInclude" and the NavBar.
 */
?>
<div class="wholePage">
    <h1 id="pbTitle">P&B Academy</h1>
    <div class="leftMenu">
        <?php include 'Menu.php'; ?>
    </div>
    <div class="mainBody">
        <h1 id="mainBodyTitle"><?php echo $this->PageTitle; ?></h1>
        <?php include $this->MainBodyInclude; ?>
        <div class="NavBar">
        <?php
            if(is_a($this, 'INavBarView')){
                /* @var $this INavBarView */
                $this->PrintNavBar();
            }
        ?>
        </div>
        <div id="BottomMenu">
            <?php include 'Menu.php'; ?>
        </div>
    </div>
</div>