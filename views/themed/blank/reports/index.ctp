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
	$paginator->options(array('url' => $this->passedArgs)); 
	$paginator->options['url']['?'] = $simpleParms;
?>
<?php 
if($newReport) {
 echo $html->link( 
    $html->image("/img/markreadall.png", array("alt" => "Mark Read", 'width' => '30px', 'style' => 'vertical-align:middle;')), 
    array('controller' => 'reports', 'action' => 'markAllRead'), 
    array('escape' => false )
); }
?>
<table width="100%">
<tr>
	<th width="80%">Subject</th>
	<th width="20%"><?php echo $paginator->sort('Date', 'time') ?></th>
</tr>
<?php foreach($reports as $report): ?>
	<tr>
		<td>
			<?php echo $this->Js->link( 
    $report['Report']['title'], 
    array( 'controller' => 'reports', 'action' => 'ajaxView', $report['Report']['id'] ), 
    array( 'update' => '#hidden_' . $report['Report']['id'], 'before' => "toggleDiv('hidden_" . $report['Report']['id'] . "');" . $this->Js->get('#indicator_'.$report['Report']['id'])->effect('fadeIn', array('buffer' => false)), 'after' => $this->Js->get('#indicator_'.$report['Report']['id'])->effect('fadeOut', array('buffer' => false)))); ?> 
    
    		<?php if(!$report['Report']['is_read']) { echo "(New!)"; } ?>
			
			<div id="hidden_<?php echo $report['Report']['id'] ?>" style="display:none;"><div id="indicator_<?php echo $report['Report']['id']; ?>" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div></div>
		</td>
		<td valign="top">
			<?php echo date('j M, Y, H:i', $report['Report']['time']) ?>
		</td>
	</tr>
<?php endforeach; ?><br />
<tr>
	<td colspan="2">
		<?php echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled')); ?>
		<?php echo $paginator->next(' Next »', null, null, array('class' => 'disabled')); ?>
	</td>
</tr>
<tr>
	<td colspan="2">
	<?php echo $paginator->numbers(); ?>
	</td>
</tr>
</table>