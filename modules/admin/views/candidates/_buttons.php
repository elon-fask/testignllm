<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Candidates;
use app\models\ApplicationType;

    /* View or Edit */
    if(isset($isView) && $isView == true){
        $isView = true;
    }else{
        $isView = false;
    }

    $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $model->getFolderDirectory() . '/attachments/';
    $certPDF = $candidateFolder . 'certificates.pdf';
    $certPDFWebURL = '/app-forms/' . $model->getFolderDirectory() . '/attachments/certificates.pdf';
?>

<style>
    .btn-wrapper{margin-bottom: 15px;}
    .btn-wrapper{
        border:1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
    }
    .btn-wrapper ul {
        list-style-type: none;
    }
    .btn-wrapper ul li{
        margin-bottom: 10px;
    }
    .btn-wrapper ul li a{
        display: block;
        width: 175px;
        text-align: center;
    }

    @media only screen and (min-width : 992px) {
        .btn-wrapper{margin-bottom:0;}
    }
    @media only screen and (min-width : 992px) and (max-width : 1200px){
        .btn-wrapper .col-xs-6+.col-xs-6 ul{padding-left:0;}
    }
</style>
<script>
    $(function(){
        /* http://craftpip.github.io/jquery-confirm/#api */
        jconfirm.defaults = {
            animation: 'zoom',
            confirmButtonClass: 'btn-primary',
            cancelButtonClass: 'btn-warning',
            columnClass: 'col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2',
            confirmButton: 'Yes, Confirm',
            cancelButton:'No, Cancel',
            opacity:1,
            backgroundDismiss: true,
            closeIcon: true,
            closeIconClass: 'fa fa-times'
        };

        // IAI SUMIBMISSION
        $('.btn-IAI-submit').on('click', function(e){
            e.preventDefault();
            $(this).blur();
            var id = $(this).data('id');
            var iaivalue = $(this).data('iaivalue');
            var msg = 'Are you sure you want to mark this application as ';
            var options = {
                title: 'AI Application Submission',
                confirmButton: 'Yes, Generate Application Forms',
                cancelButton:'No, Cancel',
            };

            if (iaivalue == 0){
            	iaivalue = 1;
                msg += 'submitted';
                options.cancelButton='No, Leave un-submitted';
                options.confirmButton='Yes, Mark Submitted';
            }else{
            	iaivalue = 0;
                msg += 'un-submitted';
                options.cancelButton ='No, Leave submitted';
                options.confirmButton='Yes, Mark un-submitted';
            }
            options.confirm= function(){markApp(id,iaivalue);};
            options.content = msg;
            $.confirm(options);
        });

        // GENERATE APPLICATION FORMS
        $('.btn-generate').on('click', function(e){
            e.preventDefault();
            $(this).blur();
            $.confirm({
                title: 'Generate Application Form',
                content: 'Are you sure you want to generate new application forms?',
                confirmButton: 'Yes, Generate Application Forms',
                cancelButton:'No, Cancel',
                confirm: function(){ $('#form-generate-app').submit();}
            });
        });

        // RESET APPLICATION FORMS
        $('.reset-form').on('click', function(e){
            e.preventDefault();
            $(this).blur();
            $.confirm({
                title: 'Reset Application Form',
                content: 'Are you sure you want to reset and generate the student\'s application forms?',
                confirmButton: 'Yes, Reset Application',
                cancelButton:'No, Cancel',
                confirm: function(){ $('form#reset').submit();}
            });
        });

        // SEND / RESEND APPLICATION FORMS
        $('.send-app-form').on('click', function(e){
            e.preventDefault();
            $(this).blur();
            var cid  = $(this).data('candidate-id');

            $.confirm({
                title: 'Resend Application Form',
                content: 'Are you sure you want to resend the forms to the student?',
                confirmButton: 'Yes, Resend PDF Documents',
                cancelButton:'No, Cancel',
                confirm: function(){
                    NProgress.start();
                    $.post('/admin/candidates/sendappform', 'id=' + cid, function (resp) {
                        // Have to assume this is working; cannot test locally nor emulate !!
                        // But I believe it was not working since we are not supposed to have genefic .alert !!
                        NProgress.done();
                        var data = $.parseJSON(resp);
                        $('.alert').removeClass('alert-danger');
                        $('.alert').removeClass('alert-success');
                        if (data.status == 1) {
                            $('.alert').addClass('alert-success').html('Latest Application Form Sent to Student');
                        } else {
                            $('.alert').addClass('alert-danger').html('Application Form not sent, please try again.');
                        }
                        $('.alert').show();
                    });
                }
            });
        });
    });

</script>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="btn-wrapper">
            <h4>Student Management</h4>
            <div class="row">
                <div class="col-xs-6">
                    <ul>
                        <?php if ($isView) { ?>
                        <li>
                            <?php echo Html::a('<i class="fa fa-pencil"></i> Edit Information', [
                                'update',
                                'id' => md5($model->id)
                            ], [
                                'class' => 'btn btn-primary'
                            ]) ?>
                        </li>
                        <?php } ?>
                        <li id="react-entry-cert"></li>
                        <li>
                            <a href="/admin/candidates/create?id=<?php echo md5($model->id)?>" class="btn btn-primary" data-candidate-id="<?php echo md5($model->id)?>"><i class="fa fa-copy"></i> Clone Application</a>
                        </li>
                    </ul>
                </div>
                <?php
                if(!$isView) {
                    if ($model->isNewRecord == false) {
                        ?>
                <div class="col-xs-12">
                    <ul>
                        <li>
                            <a style="display: <?php echo $model->hasNoSession() ? 'inline-block' : 'none' ?>" class="mark-student-not-signing-up btn btn-warning"
                               href="javascript: markStudentNotSigningUp('<?php echo md5($model->id) ?>', true, '/admin/candidates')"
                               data-id="<?php echo md5($model->id) ?>">
                                <i style="width:15px" class="fa fa-user-times"></i><span style="font-size: 14px;"> Mark Not Signed Up</span>
                            </a>
                        </li>
                        </ul>
                    </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="btn-wrapper">
            <h4>Application Forms</h4>

            <div class="row">

                <?php if($model->hasAppForms()){?>
                    <div class="col-xs-6">
                        <ul>
                            <li>
                                <?= Html::a('<i class="fa fa-download"></i> Download', ['/register/form', 'cId' => base64_encode($model->id), 'i' => md5($model->id)], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
                            </li>
                            <li>
                                <?php echo Html::a('<i class="fa fa-cog"></i> Generate', '#', ['class' => 'btn btn-primary btn-generate']) ?>
                                <form id="form-generate-app" method="post" action="/admin/candidates/generate?cid=<?php echo base64_encode($model->id)?>&i=<?php echo md5($model->id)?>">
                                    <input type="hidden" name="cId" value="<?php echo base64_encode($model->id);?>"/>
                                    <input type="hidden" name="i" value="<?php echo md5($model->id);?>"/>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xs-6">
                        <ul>
                            <li>
                                <a href="javascript: void(0)" class="btn btn-primary send-app-form" data-candidate-id="<?php echo md5($model->id)?>"><i class="fa fa-envelope-o"></i> Resend</a>
                            </li>
                            <li>
                                <a href="javascript: void(0)" class="btn btn-warning reset-form" data-candidate-id="<?php echo md5($model->id)?>"><i class="fa fa-cogs"></i> Reset Application</a>
                            </li>
                        </ul>
                    </div>

                    <?php if (is_file($certPDF)) { ?>
                        <div class="col-xs-6">
                        <ul>
                            <li>
                                <a href="<?= $certPDFWebURL ?>" target="_blank" class="btn btn-primary"><i class="fa fa-certificate"></i> Download Certificate</a>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>

                <?php }else{?>
                    <div class="col-xs-6">
                        <p>No Application Form Currently Available.</p>
                    </div>
                <?php }?>
                </div>
        </div>
    </div>


</div>

<br />
<br />
<?php // Html::a('Account Balance', ['/admin/candidates/payment', 'id' => md5($model->id)], ['class' => 'btn btn-primary']) ?>
