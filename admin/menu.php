<ul class="section-tabs">    
    <li <?php if (strstr($_SERVER['PHP_SELF'], "home.php") != '') { ?>class="active"<?php } ?>><a href="home.php?shop=<?= $shop; ?>">Dashboard</a></li>
    <li <?php if (strstr($_SERVER['PHP_SELF'], "buttons_list.php") != '') { ?>class="active"<?php } ?>><a href="buttons_list.php?shop=<?= $shop; ?>">Share Buttons</a></li>    
	<li <?php if (strstr($_SERVER['PHP_SELF'], "settings.php") != '') { ?>class="active"<?php } ?>><a href="settings.php?shop=<?= $shop; ?>">Developer Guide</a></li>    
</ul>