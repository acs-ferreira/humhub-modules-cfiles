<?php
use yii\helpers\Html;
use humhub\compat\CActiveForm;

$this->registerJs('initDirectoryList();', \yii\web\View::POS_END);

function renderFolder($folder)
{
    echo "<li><span class='selectable' id='" . $folder['folder']->id . "'>" . $folder['folder']->title . "</span>";
    if (! empty($folder['subfolders'])) {
        echo "<ul>";
        foreach ($folder['subfolders'] as $subfolder) {
            renderFolder($subfolder);
        }
        echo "</ul>";
    }
    echo "</li>";
}

?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = CActiveForm::begin(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">
                <?php echo Yii::t('CfilesModule.views_browse_moveFiles', '<strong>Move</strong> files'); ?>
            </h4>
        </div>

        <div class="modal-body">
            <?php
            
            if (! empty($errorMsgs)) :
                echo "<ul>";
                foreach ($errorMsgs as $error) :
                    echo "<li>$error</li>";
                endforeach
                ;
                echo "</ul>";
            
            
            endif;
            ?>
            <br />
            <div class="directory-list">
                <div class="selectable" id="0">/ (root)</div>
                <ul>
                <?php
                
                foreach ($folders as $dir) :
                    renderFolder($dir);
                endforeach
                ;
                ?>
                </ul>
            </div>

            <input id="input-hidden-selectedFolder" type="hidden"
                name="destfid" value="" />
            
            <?php
            if (is_array($selectedItems)) {
                foreach ($selectedItems as $index => $item) {
                    echo "<input class='input-hidden-selectedItem' type='hidden' name='selected[]' value='$item'/>";
                }
            }
            ?>
            <div class="modal-footer">
            <?php
            echo \humhub\widgets\AjaxButton::widget([
                'label' => Yii::t('CfilesModule.views_browse_moveFiles', 'Save'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); }'),
                    'url' => $contentContainer->createUrl('/cfiles/browse/move-files', [])
                ],
                'htmlOptions' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
            ?>
            <button type="button" class="btn btn-primary"
                    data-dismiss="modal"><?php echo Yii::t('CfilesModule.views_browse_moveFiles', 'Close'); ?></button>

            </div>
        <?php CActiveForm::end()?>
    </div>
    </div>
</div>