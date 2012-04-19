You may use TechCoins to reduce the time remaining on your technology. <br />Each coin is worth 1 tick. <br />You may only reduce your time to 1 tick remaining. 
<br />
If you input more coins than needed, they will be automatically reduced.
<br />
You currently have <?php echo $user['Technology']['coins']; ?> coins and <?php echo $user['Technology']['time']; ?> ticks remaining on your research.
<?php 
echo $form->create('Technology', array('url' => '/technologies/coins/')); 
echo $form->input('coins');
echo $form->submit();
echo $form->end(); ?>