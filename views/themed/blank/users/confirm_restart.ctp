Once you confirm your restart, all your kingdom information will be deleted. <br />Your premium points and time remaining will be saved.<br /><br />
<a href="/users/restart<?php echo $parms ?>">Click here to confirm your restart.</a><br /><br />

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
