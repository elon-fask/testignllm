<?php
use app\helpers\UtilityHelper;
    switch($step){
        case 1 :
            $title = 'Choose Your Class Location';
            break;
        case 2 : /* Not in use as it's ajax but to keep a ref to the ajax value see app.js */
            $title = 'Choose Your Class Dates';
            break;
        case 3 :
            $title = 'Provide Required Information for your Profile';
            break;
        case 4 :
            $title = 'Last Step! Complete your Payment Method.';
            break;
        case 5 :
            $companyName = 'California Crane School';;
            if(UtilityHelper::getCurrentBranding() == 'acs'){
                $companyName = 'American Crane School';
            }
            $title = 'Thank you for registering for the Crane Operator Certification Program.';
            break;
        Default:
            $title = "Register a Session";
    }
?>



<div class="row row-steps-title">
    <div class="col-xs-12">
        <h1 class="step-title"><?= $title;?></h1>
    </div>
</div>

