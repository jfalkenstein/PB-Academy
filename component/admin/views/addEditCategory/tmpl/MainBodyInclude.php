<?php
/* @var $this PBAcademyViewAddEditCategory */
$ThisCategory = $this->Category;
?>
<script>
    //Load relevant variables from PHP into the PB namespace. 
    PB.editCategory.PostUrl = '<?php echo AdminUrlMaker::AjaxPostCategory();?>';
    PB.AddNewLink = '<?php echo AdminUrlMaker::AddEditCategory(); ?>';
</script>
<div id="formContainer">
    <form id="editCategoriesForm">
        <input type="hidden" id="token" name="<?php echo JSession::getFormToken(); ?>" value="1">
        <input type="hidden" id="categoryId" value="<?php echo(($ThisCategory) ? $ThisCategory->Id : ''); ?>">
        <fieldset id="basicInfo">
            <legend>School Information:</legend> 
                <label for="title">School Name:</label>
                <input 
                    id="categoryName"
                    type="text" 
                    maxlength="250" 
                    value="<?php echo(($ThisCategory) ? trim(htmlspecialchars($ThisCategory->Name,ENT_QUOTES)) : ''); ?>" 
                    required>
                <label for="description">Description:</label>
                <textarea id="Description" rows="5" cols="50" maxlength="250" required><?php echo(($ThisCategory) ? trim(htmlspecialchars($ThisCategory->Description,ENT_QUOTES)) : '');?></textarea>
                <div class="imagePreview"></div>
                <label for="imagePath">Path to Image:</label>
            <ul>
                <li>Use image with aspect ratio of 4:3 (preferably 600px x 450px).</li>
                <li>Place the image in /images/pbacademy/schools/.</li>
            </ul>
                <input
                    type="text" 
                    id="imagePath" 
                    value="<?php echo(($ThisCategory) ? $ThisCategory->ImagePath : ''); ?>" 
                    required>
        </fieldset>
        <input type="submit" value="Save School">
    </form>
    <?php include __DIR__ . '/../../sharedViewResources/tmpl/EditFeedbackDivs.php'; ?>
</div>
