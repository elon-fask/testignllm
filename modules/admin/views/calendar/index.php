<?php
use app\assets\ReactCalendarRootAsset;

ReactCalendarRootAsset::register($this);
?>

<?php $this->params['breadcrumbs'][] = ['label' => 'Calendar', 'url' => '']; ?>
<div id="react-entry"></div>
<script>
/*
var elem = document.getElementsByClassName('rbc-show-more');
var f = function(){
	alert('ok');
}
for(var i = 0;i<elem.length;i++){
elem[i].addEventListener('click',f,false);
}
*/
//$(document).ready(function(){
//setTimeout(function(){
$('.rbc-show-more').click(function(){alert(1);
setTimeout(function(){  
$('.rbc-overlay').each(function(){alert(1);
var el = $(this).find('.rbc-event-content');
if(el){
//alert('ok');
}
var arr = [];
el.each(function(){
var t = $(this).attr('title');
if(arr.indexOf(t.split(',')[0])<0){ 
arr.push(t.split(',')[0]);console.log(arr);
}else{ 
$(this).parent().remove();
}

})
})

},1000)
})
//},10000)

//})
</script>
