<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (isset($nav_links)) {
	?>
        <div class="toolbar1" style="width:100%;">
        <?php 
				echo "<div id=\"main-links\">";
				echo "<ul id=\"main-links-nav\">";
				foreach ($nav_links as $link => $title) {
					if ($title == "Print Preview")
						echo '<li><a class="nav-links-item btn" onclick="printPopUp('."'".$link."'".')" style="background-image:url(\'' . asset_url() . 'style/images/navlink.png\');">'.$title.'</a></li>';
						//echo "<li>" . anchor_popup($link, $title, array('title' => $title, 'class' => 'nav-links-item', 'style' => 'background-image:url(\'' . asset_url() . 'style/images/navlink.png\');', 'width' => '1024')) . "</li>";
					else
						echo "<li>" . anchor($link, $title, array('title' => $title, 'class' => 'nav-links-item', 'style' => 'background-image:url(\'' . asset_url() . 'style/images/navlink.png\');')) . "</li>";
				}
				echo "</ul>";
				echo "</div>";
			 ?>

                        </div>
<?php } ?>