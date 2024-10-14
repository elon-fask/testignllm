
<ul class="nav nav-tabs" role="tablist" style="font-size: 1.25em;  margin-bottom: 40px; margin-top: 25px;">
    <li<?= ($active == 'details' )? ' class="active"' : '' ?>>
        <a href="<?= ($active == 'details' )? '#' : '/admin/candidates/view?id=' . md5($candidate->id) ?>">
            <i class="fa fa-user"></i> Student Details
        </a>
    </li>
    <li<?= ($active == 'account' )? ' class="active"' : '' ?>>
        <a href="/admin/candidates/account-balance?id=<?= md5($candidate->id) ?>">
            <i class="fa fa-dollar"></i> Account Balance
        </a>
    </li>
    <li<?= ($active == 'notes' )? ' class="active"' : '' ?>>
        <a href="/admin/candidates/notes?id=<?= md5($candidate->id) ?>">
            <i class="fa fa-file-text-o"></i> Notes</a>
    </li>
    <li<?= ($active == 'files' )? ' class="active"' : '' ?>>
        <a href="/admin/candidates/files?id=<?= md5($candidate->id) ?>">
            <i class="fa fa-file-image-o"></i> Files</a>
    </li>
    <li class="pull-right <?= ($active == 'edit' )? ' active' : '' ?>">
        <a href="/admin/candidates/update?id=<?= md5($candidate->id) ?>" class="text-danger" style="color: #843534">
            <i class="fa fa-pencil"></i> Edit Information
        </a>
    </li>
</ul>
