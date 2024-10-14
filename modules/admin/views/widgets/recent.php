<?php 
$list = $items['list'];
$totalCount = $items['count'];
?>

<?php $selection = isset($_GET['time']) ? $_GET['time'] : 0 ;?>
<div class="row" style="margin-bottom: 25px;">
<div class="form-horizontal">
    <label class="control-label col-sm-5 col-md-4 col-lg-2  col-lg-offset-2">Applied Last:</label>
    <div class="col-sm-3 col-md-5 col-lg-3">
        <select class="form-control" id="recent-application-time2">
            <option value="1" <?php if( $selection == '1') echo 'selected="selected"'?>>24 hours</option>
            <option value="2" <?php if( $selection == '2') echo 'selected="selected"'?>>2 days</option>
            <option value="7" <?php if( $selection == '7') echo 'selected="selected"'?>>7 days</option>
            <option value="30" <?php if( $selection == '30') echo 'selected="selected"'?>>30 days</option>
        </select>
    </div>
</div>
</div>




<?php if($totalCount == 0){?>
<h2 style="text-center">No Application</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Session Name</th>
            <th>Candidate Name</th>
            <th>Date</th>
            <th>Application Type</th>
            <th>Code Word Used</th>
            <th>Contact</th>
            <th><!--Amount Paid/Total (Owed)--> Fees <em style="font-size: 0.85em">(Owed)</em></th>
        </tr>
    </thead>
    <tbody>
<?php $array = [];?>
     <?php foreach($list as $candidate){?>
<?php if(!in_array($candidate->getFullName().number_format($candidate->amountOwed, 2, ".", ',') ,$array)): ?>
        <tr  data-id="<?php echo $candidate->id?>">
            <td><?php echo $candidate->getWrittenTestSession() !== false ? $candidate->getWrittenTestSession()->getFullTestSessionDescription() : 'N/A'?></td>
            <td>
                <a href='/admin/candidates/view?id=<?php echo md5($candidate->id)?>' data-toggle="tooltip" title="View Candidate Application" data-placement="bottom">
                    <?php echo $candidate->getFullName() ?>
                </a>
            </td>

            <td><?php echo date('m-d-Y', strtotime($candidate->date_created))?></td>
            <td><?php echo $candidate->getApplicationTypeDesc()?></td>
            <td><?php echo $candidate->getApplicationTypeKeyword()?></td>
            <td style="width: 80px">

                <a class="show-action show-contact-details" href="#"><i class="fa fa-eye"></i>&nbsp;Details</a>
                <div style="display: none" class="pop-content">
                    <ul style="list-style-type: none; margin: 0; padding: 0; font-size: 12px;width: 175px;">
                     <?php if($candidate->getFullName() != ''){?>
                           
                            <li>
                                <b>Name:</b>&nbsp;<?php echo $candidate->getFullName()?>
                            </li>
                        <?php }?>
                        <?php if($candidate->email != ''){?>
                            <li class="clearfix">
                                    <span style="display: inline-block; float: left;"><b>Email:</b>&nbsp;</span>
                                    <span style="display: inline-block; float: left; word-wrap: break-word; max-width: 100%;"><a href="mailto: <?php echo $candidate->email?>"><?php echo $candidate->email?></a></span>
                            </li>
                        <?php }?>
                        <?php if($candidate->phone != ''){?>
                            <li>
                                <b>Phone:</b>&nbsp;<a href="tel: <?php echo $candidate->phone?>"><?php echo $candidate->phone?></a>
                            </li>
                        <?php }?>
                        <?php if($candidate->cellNumber != ''){?>
                            <li>

                                <b>Mobile:</b>&nbsp;<a href="tel: <?php echo $candidate->cellNumber?>"><?php echo $candidate->cellNumber?></a>
                            </li>
                        <?php }?>
                         
                         <?php if($candidate->company_name  != '' || $candidate->company_phone  != '' || $candidate->contactEmail != ''){?>
                         <li>
                         <br />
                                <b>Business Contact Info</b>
                            </li>
                         <?php }?>
                         
                         <?php if($candidate->company_name != ''){?>
                            <li>
                                <b>Company:</b>&nbsp;<?php echo $candidate->company_name?>
                            </li>
                        <?php }?> 
                        <?php if($candidate->contactEmail != ''){?>
                            <li class="clearfix">
                                    <span style="display: inline-block; float: left;"><b>Email:</b>&nbsp;</span>
                                    <span style="display: inline-block; float: left; word-wrap: break-word; max-width: 100%;"><a href="mailto: <?php echo $candidate->contactEmail?>"><?php echo $candidate->contactEmail?></a></span>
                            </li>
                        <?php }?>                        
                        <?php if($candidate->company_phone != ''){?>
                            <li>
                                <b>Phone:</b>&nbsp;<a href="tel: <?php echo $candidate->company_phone?>"><?php echo $candidate->company_phone?></a>
                            </li>
                        <?php }?>
                        <?php if( $candidate->email == '' && $candidate->phone == '' && $candidate->cellNumber == '' && $candidate->company_phone == '' && $candidate->company_name == '' && $candidate->contactEmail == '') { ?>
                            <li>
                                No Information
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </td>
            <td class="td-fees">
                <p class="show-action show-fees text-danger" href="#">$ <?php echo number_format($candidate->amountOwed, 2, ".", ',')?></p>
            </td>
        </tr>
<?php $array[] = $candidate->getFullName().number_format($candidate->amountOwed, 2, ".", ',') ?>
<?php endif;?>
        <?php }?>
    </tbody>
</table>

<?php }?>

<div class="recent-pagination" data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>
<style>
.amount-paid{
	color: green;
}
.amount-total{
	color: #000000;
}
.amount-owed{
	color: red;
}
.table-fees.table {
    margin-bottom: 0;
}
.table-fees.table>tbody>tr>td, .table-fees.table>tbody>tr>th{
    border-top: 0;
}
</style>
