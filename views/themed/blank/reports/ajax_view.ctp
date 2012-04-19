<?php echo $report['Report']['body'] ?><br /><br />
<?php if ($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
<a href="/reports/view/<?php echo $report['Report']['id'] . "/" . substr( md5($report['Report']['id'] . '123489svk123xfjo4965oinlk1098345klj'), 0, 15 );?>">Public link</a>
<?php } ?>