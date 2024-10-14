
<?php
    $totalSteps  = 5;
    $currentStep = isset($step) ? $step : '1';
?>




<div class="row row-steps">
    <div class="col-xs-12">
        <div class="steps clearfix">
            <?php
                $i=1;
                while( $i < $totalSteps+1 )
                {
                    $currentClass ="";

                    if ($i < $currentStep ){ $currentClass= " step-previous"; }
                    if ($i == $currentStep ){ $currentClass= " step-current" ;}

                    echo "<div class=\"step step-{$i}{$currentClass}\">{$i}</div>";
                    $i++;
                }
            ?>
            <div class="steps-line"></div>
        </div>
    </div>
</div>
