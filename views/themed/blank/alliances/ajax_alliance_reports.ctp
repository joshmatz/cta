<script language="javascript">
  function toggleDiv(divid){
    if(document.getElementById(divid).style.display == 'none'){
      document.getElementById(divid).style.display = 'block';
    }else{
      document.getElementById(divid).style.display = 'none';
    }
  }
</script>

<?php 
	$paginator->options(array(
	'update' => '#reports', 
    'before' => $js->get('#reports')->effect('fadeOut', array('buffer' => false)),
    'success' => $js->get('#reports')->effect('fadeIn', array('buffer' => false)),
    'url'=>array('controller'=>'alliances', 'action'=>'ajaxAllianceReports')));
?>

<table width="100%">
<tr>
	<th width="20%">Member</th>
	<th width="60%">Subject</th>
	<th width="20%"><?php echo $paginator->sort('Date', 'time') ?></th>
</tr>
<?php foreach($reports as $report): ?>
	<tr>
		<td>
			<?php echo $members[$report['Report']['user_id']]; ?>
		</td>
		<td>
			<?php echo $ajax->link( 
    $report['Report']['title'], 
    array( 'controller' => 'alliances', 'action' => 'ajaxView', $report['Report']['id'] ), 
    array( 'update' => 'hidden_' . $report['Report']['id'], 'before' => "toggleDiv('hidden_" . $report['Report']['id'] . "')", 'indicator' => 'indicator_' . $report['Report']['id'])); ?> <?php if(!$report['Report']['is_read']) { echo "(Unread!)"; } ?>
			
			<div id="hidden_<?php echo $report['Report']['id'] ?>" style="display:none;"><div id="indicator_<?php echo $report['Report']['id']; ?>" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div></div>
		</td>
		<td valign="top">
			<span class="report-time"><?php echo date('j M, Y, H:i', $report['Report']['time']) ?></span>
		</td>
	</tr>
<?php endforeach; ?><br />
<tr>
	<td colspan="3">
	<?php echo $paginator->first();?> | 
	<?php echo $paginator->numbers(); ?> | 
	<?php echo $paginator->last();?>
	</td>
</tr>
</table>
<?php echo $this->Js->writeBuffer(); ?>