<div class="report">
<div class="date"><b><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?> | Title: <?php echo $message['Message']['title'] ?></b></div>
<div class="reportBody"><?php echo $message['Message']['body']; ?><p /> ~ <?php echo $message['Message']['sender'] ?><br /> <br />

</div></div>
<div class="separator">&nbsp;</div>
