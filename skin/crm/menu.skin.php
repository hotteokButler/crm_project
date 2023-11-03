<?
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/nav.css">', 0);
?>

<nav id="crm_nav">
  <div id="crm_logo"><h1><a href="<?=G5_URL;?>" target="_blank"><img src="<?=$board_skin_url;?>/img/crm_logo.png" alt=""></a></h1></div>
  <ul>
    <li class="chart_ico">
      <a href="<?=G5_BBS_URL."/board.php?bo_table=".$bo_table;?>"><img src="<?=$board_skin_url;?>/img/chart_icon.png" alt=""></a>
    </li>
  </ul>
</nav>