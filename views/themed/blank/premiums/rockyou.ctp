In order to purchase and receive premium points, please make sure any ad blockers are disabled.<br />
To use your new premium points, head over to the <a href="/premiums/<?php echo $parms ?>">premium shop</a>.
<?php if(isset($birthday) && isset($sex)) { ?>
<iframe
    src="http://www.peanutlabs.com/userGreeting.php?userId=<?php echo $endUserId . "-" . $applicationId . "-" . $userGo ?>&dob=<?php echo $birthday ?>&sex=<?php echo $sex ?>"
    width="650px" height="2500px" frameborder="0" scrolling="no"
></iframe>
<?php } elseif(isset($birthday)) { ?>
<iframe
    src="http://www.peanutlabs.com/userGreeting.php?userId=<?php echo $endUserId . "-" . $applicationId . "-" . $userGo ?>&dob=<?php echo $birthday ?>"
    width="650px" height="2500px" frameborder="0" scrolling="no"
></iframe>
<?php } elseif(isset($sex)) { ?>
<iframe
    src="http://www.peanutlabs.com/userGreeting.php?userId=<?php echo $endUserId . "-" . $applicationId . "-" . $userGo ?>&sex=<?php echo $sex ?>"
    width="650px" height="2500px" frameborder="0" scrolling="no"
></iframe>
<?php } else { ?>
<iframe
    src="http://www.peanutlabs.com/userGreeting.php?userId=<?php echo $endUserId . "-" . $applicationId . "-" . $userGo ?>"
    width="650px" height="2500px" frameborder="0" scrolling="no"
></iframe>
<?php } ?>