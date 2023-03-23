<!--
<div id="<?php echo $key; ?>Message" class="<?php echo!empty($params['class']) ? $params['class'] : 'message'; ?>">
    <?php echo $message; ?>
</div>
//-->
<div id="<?php echo $key; ?>Message" class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <p>
        <strong>
            <?php echo $message; ?>
        </strong>
    </p>
</div>
