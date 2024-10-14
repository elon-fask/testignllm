<style>
    h2{margin-top: 50px;}
    .panel-body h2{margin-top: 0px;}
    /* All fee list */
    .row-fees ul{
        list-style-type: none; margin: 0; padding-left: 0; border: 1px solid black; padding: 5px 15px;
    }
    .row-fees ul .clearfix .col-xs-5:first-child{
        padding-left: 0;
    }
    .row-fees ul .clearfix .col-xs-5:last-child{
        padding-right: 0;
    }
    .row-fees ul .control-label {
        width: 100%;
        margin-bottom: 0;
        font-weight: normal;
        color: black;
        display: flex;
    }
    .row-fees li{
        font-size: 12px;
        line-height: 20px;
        margin-bottom: 5px;
    }
    .row-fees ul li .w-90 , .row-fees ul li .w-10 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
        float: left;
    }
    .row-fees ul li .w-90{
        padding-right: 0;
        width: 90%;
    }
    .row-fees ul li .w-10{
        padding-left: 0;
        width: 10%;
    }
    .row-fees ul li .control-label div:first-of-type{
        display: flex;
        width: 100%;
        white-space: nowrap;
        align-items: baseline;
    }
    .row-fees ul li .total{
        display: flex;
        white-space: nowrap;
        align-items: baseline;
        padding-right: 0;
    }
    .row-fees ul li .total span{
        width: 100%;
        border-bottom: 2px dotted;
        margin-left: 5px;
        margin-right: 5px;
    }
    .row-fees ul li .control-label div span:last-child{
        width: 100%;
        border-bottom: 1px dotted;
        margin-left: 5px;
        margin-right: 5px;
    }
    li.fee-title{
        border-bottom: 0;
        margin-bottom: 0;
    }
    .row-fees li.fee-title h4{
        margin-bottom: 5px;
        font-weight: bold;
    }
    .row-fees li .form-group{
        margin-bottom: 0
    }
    .row-fees ul input[type=checkbox] , .row-fees ul input[type=radio]{
        position: relative;
        margin-right: 4px;
        color: black;
    }
    /* Written Exams */
    ul.written-exams li{
        border-bottom: 1px dotted black;
        line-height: 20px;
        padding: 5px 0;
    }
    .written-exams > li:last-child, .written-exams > li:first-child{
        border-bottom: 0;
        margin-bottom: 0;
    }
    /* Other Fees*/
    .row-fees ul.other-fees{
        margin-top: 85px;
    }
    .other-fees > li > div:nth-child(2){
        text-align: right
    }
    /* Test fees */
    .test-fees{
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .test-fees h4{
        padding-top: 10px; border-top: 1px solid black;
    }
    .test-fees .fee-title:first-of-type h4,
    .test-fees .fee-title:last-child h4{
        margin-top: 0;
        border-top: none;
    }
    .test-fees > li > div:nth-child(2){
        text-align: right
    }
    .fee-total{
        padding-right: 15px;
    }
    .fee-total > div:nth-child(2) {
        border: 1px solid black;
        margin-top: 5px;
        font-size:18px;
        line-height: 1.1em;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
    }
</style>
<style>
    h2{margin-top: 50px;}
    .container-civil-state .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-civil-state, .container-types {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }
    .row-types {
    }
    .row-types ul{
        list-style-type: none; margin: 0; padding-left: 0; /*border: 1px solid #ddd; padding: 5px; border-radius: 4px;*/
    }
    .row-types ul .control-label {
        width: 100%;
        margin-bottom: 0;
        font-weight: normal;
    }
    .row-types li{
        /*font-size: 12px;*/
        line-height: 20px;
        margin-bottom: 5px;
    }
    .row-types ul input[type=checkbox]{
        position: relative;
        top:2px;
        margin-right: 4px
    }
    label{ font-weight: normal;}
    @media (max-width: 991px) {
        .form-horizontal .control-label{text-align: left !important;}
    }
</style>





