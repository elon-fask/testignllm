<div class="row">
    <div class="container-breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <?php
                $arraySize = isset($this->params['breadcrumbs']) ? sizeof($this->params['breadcrumbs']) : 0;
                $pointer = 0;
                while ($pointer < $arraySize) {
                    $crumb = $this->params['breadcrumbs'][$pointer];
                    if (!is_array($crumb)) {
                        echo '<li class="active">' . $crumb . '</li>' . "\r\n";
                    } else {
                        if ($pointer == $arraySize - 1) {
                            echo '<li class="active">' . $crumb['label'] . '</li>' . "\r\n";
                        } else {
                            echo '<li><a href="' . Yii::$app->getUrlManager()->createUrl($crumb['url']) . '">' . $crumb['label'] . '</a></li>' . "\r\n\t";
                        }
                    }
                    $pointer++;
                }
                ?>
            </ol>
        </div>
    </div>
</div>