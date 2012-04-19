Your kingdom has been destroyed. <a href="/users/restart/">Click here to restart.</a>

<?php 
if(!isset($reports)) { 
?>
<p align="center">You have no new battle reports.</p>  
<?php 
}
else { 
	foreach($reports as $report):
?>	<div class="report">
	<div class="date"><b><?php echo date('j M, Y, g:i T', $report['Report']['time']) ?></b></div>
	<div class="reportBody"><?php echo $report['Report']['body'] ?></div></div>
	<div class="separator">&nbsp;</div>
<?php
	endforeach;
}
?>
