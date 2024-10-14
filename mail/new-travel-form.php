<?php
use yii\helpers\Url;

$travelFormUrl = Url::base(true) . '/admin/travel-form/view?id=' . $travelForm->id;
?>

<div>
    <p>Dear Travel Manager,</p>
    <p><b><?= $travelForm->name ?> has submitted a Travel Form.</b><br /><a href="<?= $travelFormUrl ?>">View in Crane Admin</a></p>
    <br />
    <p><b>Details:</b></p>
    <table>
        <tbody>
        <tr>
            <td style="padding-right: 10px">Name:</td>
            <td><?= $travelForm->name ?></td>
        </tr>
        <?php if (isset($travelForm->starting_location)) { ?>
        <tr>
            <td style="padding-right: 10px">Starting Airport Location:</td>
            <td><?= $travelForm->starting_location ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td style="padding-right: 10px">Destination Location:</td>
            <td><?= $travelForm->destination_loc ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px">Destination Depart Date:</td>
            <td><?= date_format(date_create($travelForm->destination_date), 'm/d/Y') ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px">Destination Depart Time:</td>
            <td><?= $travelForm->destination_time ?></td>
        </tr>
        <?php if ($travelForm->one_way) { ?>
        <tr>
            <td style="padding-right: 10px">One Way Travel Only:</td>
            <td>Yes</td>
        </tr>
        <?php } else { ?>
        <tr>
            <td style="padding-right: 10px">Return Location:</td>
            <td><?= $travelForm->return_loc ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px">Return Depart Date:</td>
            <td><?= date_format(date_create($travelForm->return_date), 'm/d/Y') ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px">Return Depart Time:</td>
            <td><?= $travelForm->return_time ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td style="padding-right: 10px">Hotel Required:</td>
            <td><?= $travelForm->hotel_required ? 'Yes' : 'No' ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px">Car Rental Required:</td>
            <td><?= $travelForm->car_rental_required ? 'Yes' : 'No' ?></td>
        </tr>
        <tr>
            <td style="padding-right: 10px" width="160">Comment:</td>
            <td><?= $travelForm->comment ?></td>
        </tr>
        </tbody>
    </table>
</div>
