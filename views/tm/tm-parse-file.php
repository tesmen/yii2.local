
<?= \yii\helpers\Html::csrfMetaTags() ?>
<h3>TM Parts</h3>

<div class="row">
    <div class="col-xs-3">
        <form action="" method="post" enctype="multipart/form-data">
            Укажите ТМ
            <input type="file" name="file" id="file">
            <br>
            <input type="submit" value="Обработать" name="submit">
        </form>
    </div>
</div>
