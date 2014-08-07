<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Menu{

	var $CI;

	/**
	 * Authentication Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	function CI_Menu($params = array())
	{
		log_message('debug', "Authencation Class Initialized");
		// Set the super object to a local variable for use throughout the class
		$this->CI =& get_instance();
		$this->CI->load->model('menu/mdl_menu');
	}
	function printMenu($menus,$level,$i)
	{
		$this->CI->mdl_menu->menucCheckByUserID();	
		if($level ==0)
		{
			$menus = $this->CI->mdl_menu->getMenuByParentId(0);
		}
		foreach($menus as $menu)
		{
			$total = $this->CI->mdl_menu->childNumber($menu->id);
			$seg = uri_string();
			$active='';
			if($seg==$menu->menu_link){
				$active = " active";
			}
			echo '<li ';
			if($menu->parent_id==0)
			{
				echo 'class="menuid'.$menu->id.$active.'"';
			}
			else{
				echo 'class="menuid'.$menu->id.$active.'"';
			}
			echo '>';
			$count = count($this->CI->mdl_menu->getMenuByParentId($menu->id));
			if($count==0){
				$subclass = 'class="no-submenu '.$menu->menu_class.$active.'"';
			}else{
				$subclass = 'class="'.$menu->menu_class.' '.trim($active).'"';
			}
			if($menu->menu_link!='')
			{
				echo '<a href="'.site_url().''.$menu->menu_link.'" '.$subclass.'>';
			}
			else
			{
				echo '<a class="'.$menu->menu_class.'">';
			}
			echo $menu->title;
			echo '</a>';
			if($total>0)
			{
				echo '<ul>';
				$childs = $this->CI->mdl_menu->getMenuByParentId($menu->id);
				$this->printMenu($childs,($level+1),($i+1));
				echo '</ul>';
			}
			echo '</li>';
		}
	}

}
// END Authencation Class

/* End of file authencation.php */
/* Location: ./system/libraries/authencation.php */