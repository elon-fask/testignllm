<?php
/**
BreadCrumb partial for TCportal --- 21-05-2015 -- Unifying portal V0
 */
?>
<style>
    .container-breadcrumb{ background: #fcfcfc; }
    .breadcrumb{font-size: 0.8em; margin: 0; padding:4px 2px; background: transparent;}
    @media screen and (max-width: 768px) {
        .breadcrumb{margin-left:15px;}
    }
</style>

<div class="row">
    <div class="container-breadcrumb">
        <div class="container">
        <ol class="breadcrumb">
        <?php
           //var_dump($this->params['breadcrumbs']);
           $arraySize  = isset($this->params['breadcrumbs']) ?  sizeof($this->params['breadcrumbs']) : 0;
           $pointer    = 0;

           while( $pointer < $arraySize){

               $crumb = $this->params['breadcrumbs'][$pointer];
            //var_dump($crumb['label']);
               if(!is_array($crumb)){
                   echo '<li class="active">' . $crumb . '</li>'."\r\n";
               }else{
                   if ( $pointer == $arraySize-1){
                       echo '<li class="active">' . $crumb['label'] . '</li>'."\r\n";
                   }else {
                       echo '<li><a href="' .Yii::$app->getUrlManager()->createUrl($crumb['url'])  . '">' . $crumb['label'] . '</a></li>'."\r\n\t";
                   }
               }
               $pointer++;
           }
           ?>
        </ol>
        </div>
    </div>
</div>