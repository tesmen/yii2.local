
<?= \yii\helpers\Html::csrfMetaTags() ?>
<h3>TM Parts</h3>

<div class="row">
    <div class="col-xs-3">
        <form action="" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="file" id="file">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </div>
</div>
