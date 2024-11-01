<?php
 /*
   * Plugin Name: Trackback for Korea
   * Plugin URI: http://findingmyself.net/wp/wpplugins
   * Description: Trackback famouse korean site(Daum, Naver, Egloos etc)
   * Version: 0.1.0
   * Author: painnick
   * Author URI: http://findingmyself.net/wp/
   */
	 
$TBK_Trackbak_List = array(
    "Egloos" => array(
			"Book" => "http://valley.egloos.com/tb/book",
			"Movie" => "http://valley.egloos.com/tb/movie",
			"Music" => "http://valley.egloos.com/tb/music",
			"Earlyadpoter" => "http://valley.egloos.com/tb/earlyadopter",
			"Food" => "http://valley.egloos.com/tb/food",
			"News" => "http://valley.egloos.com/tb/news",
			"Photo" => "http://valley.egloos.com/tb/photo",
			"Baby" => "http://valley.egloos.com/tb/baby",
			"Tech" => "http://valley.egloos.com/tb/technology",
			"Game" => "http://valley.egloos.com/tb/game",
			"Comic" => "http://valley.egloos.com/tb/comic",
			"Animation" => "http://valley.egloos.com/tb/animation",
			"Travel" => "http://valley.egloos.com/tb/travel",
			"Sports" => "http://valley.egloos.com/tb/sports",
			"Entertainment" => "http://valley.egloos.com/tb/entertainment",
			"World" => "http://valley.egloos.com/tb/world",
			"Shopping" => "http://valley.egloos.com/tb/shopping",
			"Fashion" => "http://valley.egloos.com/tb/fashion",
			"Performance" => "http://valley.egloos.com/tb/performance",
			"Science" => "http://valley.egloos.com/tb/science",
			"Toy" => "http://valley.egloos.com/tb/toy",
			"Pet" => "http://valley.egloos.com/tb/pet",
			"Car" => "http://valley.egloos.com/tb/auto"
    ),
		
    "Daum" => array(
			"Sisa" => "http://bloggernews.media.daum.net/tb/news/1",
			"Economi" => "http://bloggernews.media.daum.net/tb/news/2",
			"Art/Culture" => "http://bloggernews.media.daum.net/tb/news/3",
			"World" => "http://bloggernews.media.daum.net/tb/news/4",
			"Sports" => "http://bloggernews.media.daum.net/tb/news/5",
			"IT/Science" => "http://bloggernews.media.daum.net/tb/news/6",
			"Entertainment" => "http://bloggernews.media.daum.net/tb/news/7",
			"Movie" => "http://bloggernews.media.daum.net/tb/news/24",
			"Book" => "http://bloggernews.media.daum.net/tb/news/8",
			"Life" => "http://bloggernews.media.daum.net/tb/news/9",
			"Travel" => "http://bloggernews.media.daum.net/tb/news/10",
			"Food" => "http://bloggernews.media.daum.net/tb/news/11",
			"Photo" => "http://bloggernews.media.daum.net/tb/news/12",
    )
);
	 
	$TBK_already_pinged_urls;

	function TBK_already_pinged($target)
	{
		global $TBK_already_pinged_urls;
		
		if(!isset($TBK_already_pinged_urls))
			return FALSE;
		
		foreach ($TBK_already_pinged_urls as $pinged_url) {
			if(wp_specialchars($pinged_url) == $target)
				return TRUE;
		}
		
		return FALSE;
	}
	
	function TBK_ping_condition_msg($target)
	{
		if(TBK_already_pinged($target))
			return 'checked disabled';
	}

	function TBK_admin_footer()
	{
		global $id, $post, $wp_version;
		global $TBK_already_pinged_urls;

		if (!isset($id))   $id   = $_REQUEST['post'];
		if (!isset($post)) $post = get_post($id);

		if (preg_match('/(edit\.php)/i', $_SERVER['SCRIPT_NAME']))
		{
			// Do nothing
		}

		if (preg_match('/(post\.php|post-new\.php)/i', $_SERVER['SCRIPT_NAME']) and ('static' != $post->post_status))
		{

			if ('' != $post->pinged)
				$TBK_already_pinged_urls = explode("\n", trim($post->pinged));
?>

	<div id='TBKwrap'>
<?php
		global $TBK_Trackbak_List;
		
		while(list($key, $valueSet) = each($TBK_Trackbak_List)) {
?>
		<div style="float:left">
			<p class="title"><?php echo $key; ?></p>
			<ul>
<?php
			while(list($category, $url) = each($valueSet)) {
?>
				<li><label class="selectit"><input type="checkbox" name="<?php echo 'TBK_'.$key.'_'.$category; ?>" value="<?php echo $url ?>"	<?php echo TBK_ping_condition_msg($url); ?> onchange="ping_onClick(this);" />&nbsp;<?php _e($category, 'TBK'); ?></label></li>
<?php
			}
?>
			</ul>
		</div>
<?php
		}
?>
	</div>

	<script type="text/javascript">
	//<![CDATA[
		function ping_onClick(obj) {
			var arrTrackbackUrl = document.getElementsByName('trackback_url');
			var htnTrackbackUrl = arrTrackbackUrl[0];
			
			var strPing = htnTrackbackUrl.value;
			var arrPings = strPing.split(' ');
			
			var strTargetUrl = obj.value;
			
			var bFound = false;
			
			for(var i = 0; i < arrPings.length; i ++) {
				if(arrPings[i] == strTargetUrl) {
					bFound = true;
					break;
				}
			}
			
			if((obj.checked) && (!bFound)) {
				if(htnTrackbackUrl.value.length == 0)
					htnTrackbackUrl.value = strTargetUrl;
				else
					htnTrackbackUrl.value = htnTrackbackUrl.value + ' ' + strTargetUrl;
			}
			else if((!obj.checked) && (bFound)) {
				var strValue = "";
				
				for(var i = 0; i < arrPings.length; i ++)
				{
					if(arrPings[i] != strTargetUrl)
						strValue += arrPings[i];
					
					if((i < arrPings.length - 1) && (arrPings[i].length > 0))
						strValue += ' ';
				}

				htnTrackbackUrl.value = strValue;
			}
		}
		
		var hdnTrackback = document.getElementById('trackback').parentNode;
		var divTBKwrap = document.getElementById('TBKwrap');

		if (hdnTrackback) hdnTrackback.appendChild(divTBKwrap);
	//]]>
	</script>
<?php
		}
}

	add_action('admin_footer', 'TBK_admin_footer');
?>
