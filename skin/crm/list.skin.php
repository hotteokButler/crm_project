<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/info_list.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/list.css">', 0);
?>

<!-- 게시판 목록 시작 { -->
<div id="bo_gall">
    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>

    <?if($stx){?>
        <?if($is_admin || $member['mb_level'] >=9 ){?>        
            <a class="back_to_list" href="/bbs/board.php?bo_table=<?=$bo_table;?>">차트 전체보기</a>        
        <?}?>
    <?}?>

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div id="bo_btn_top">
        <?if($is_admin || $member['mb_level'] >=9){?>
            <!-- 게시판 검색 시작 { -->
            <div class="bo_sch_wrap top_sch_wrap">	
                <fieldset class="bo_sch">
                    <h3 class="sound_only">검색</h3>
                    <form name="fsearch" method="get">
                        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                        <input type="hidden" name="sca" value="<?php echo $sca ?>">
                        <input type="hidden" name="sop" value="and">

                        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                        <div class="sch_bar">
                            <input  type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="sch_input" required size="25" maxlength="20" placeholder="차트번호 5자리를 입력해주세요">
                            <button type="submit" value="검색" class="sch_btn"><img src="<?=$board_skin_url;?>/img/search_icon.png" alt="검색"><span class="sound_only">검색</span></button>
                        </div>
                        <button type="button" id="show_detail_sch">상세 검색 옵션</button>
                        <div class="sch_select_wrap">
                            <label for="sfl" class="sound_only">검색 옵션</label>
                            <select name="sfl" id="sfl" class="sch_input">
                                <option value="wr_1" <?php echo get_selected($sfl, 'wr_1', true); ?>>차트번호</option>
                                <option value="wr_2" <?php echo get_selected($sfl, 'wr_2', false); ?>>환자명</option>
                            </select>
                        </div>
                        
                    </form>
                </fieldset>

            </div>
        <!-- } 게시판 검색 끝 -->
        <?}?>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->


    <form name="fboardlist"  id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">


      
    <? // 검색된 회원 정보 가져오기
    
    $sql_is_member = "select * from {$g5['member_table']} where (mb_level in (6,5)) and mb_id = '".$member['mb_id']."' order by mb_name ";        
    $sql_by_stx = "select * from {$g5['member_table']} where (mb_level in (6,5)) and mb_id = '$stx' order by mb_name ";
    $sql_by_birth_and_name = "select * from {$g5['member_table']} where (mb_level in (6,5)) and mb_name = '$stx' and mb_1 = '$wr_5' order by mb_name ";


    if ( $is_member && !$is_admin) {
        $sql1 = $sql_is_member;
    } elseif($wr_5) {
        $sql1 =  $sql_by_birth_and_name;
    } else {
        $sql1 =  $sql_by_stx;
    }

    $res1=sql_query($sql1);
    $row= sql_fetch_array($res1);
    ?>

    <? //D-day 계산 (오늘날짜 기준)

    $d_start = date("Y-m-d", time());;
    $d_day_count = floor((strtotime(date($row['mb_fdate'])) - strtotime($d_start)) / 86400 );
    if ($d_day_count <= 0) {
      $d_day_count = '<span class="event_btn event_end fw900 ft_sblue">치료종료</span>';
    } else {
      $d_day_count = '<span class="event_btn event_ongoing fw900 ft_sblue">D-' . $d_day_count . '</span>';
    }
    ?>

    <? // 만나이 계산
    $birth_time   = date('Ymd',strtotime($row['mb_1']));
    $now          = date('Ymd');
    $age     = floor(($now - $birth_time)/10000);
    ?>



    <?if(!$is_admin && $member['mb_level'] <=8){?>
    <div class="crm_info_wrap">
        <div class="crm_pf_wrap">
            <p class="crm_pf">
                <?    
                    $mb_prof_dir = substr($row['mb_id'],0,2);
                    $mb_prof_file = G5_DATA_PATH.'/member/'.$mb_prof_dir.'/'.get_mb_icon_name($row['mb_id']).'.gif';
                    
                    if (file_exists($mb_prof_file)) 
                        echo get_member_profile_img($row['mb_id']);
                ?>
                <span> <?= $row['mb_name']?></span>
            </p>
        </div>

        <ul class="crm_info">
            <li class="crm_info_li">
                <span class="fw900 ft_sblue"><?=$row['mb_sdate'] ? $row['mb_sdate']: '0' ?></span>
                <p class="fw700 ft_blue">치료 시작일</p>
            </li>
            <li class="crm_info_li">
                <span class="fw900 ft_sblue"><?=$row['mb_sdate'] ? $row['mb_fdate']: '0' ?></span>
                <p class="fw700 ft_blue">치료 종료 예상일</p>
            </li>
            <li class="crm_info_li">
                <span class="fw900 ft_sblue"><?php echo number_format($total_count) ?></span>
                <p class="fw700 ft_blue">스토리</p>
            </li>
            <li class="crm_info_li">
                <?= $d_day_count ?>
                <p class="fw700 ft_blue">남은 기간</p>
            </li>
        </ul>
    </div>

    <ul class="crm_info_detail">
        <li class="crm_info_detail_li">
            <p class="fw700 ft_blue">CASE</p>
            <p><?= $row['mb_2']?></p>
        </li>
        <li class="crm_info_detail_li">
            <p class="fw700 ft_blue">치료형태</p>
            <p><?= $row['mb_3']?></p>
        </li>
        <li class="crm_info_detail_li">
            <p class="fw700 ft_blue">사용장치</p>
            <p><?= $row['mb_7']?></p>
        </li>
    </ul>
   <?}?>

   <?if(($is_admin || $member['mb_level'] >=9 )){?>

    <?if(!$stx || !$sfl){?>
        <div class="crm_total_info">
            <div class=" crm_total_story">
                <span class="fw900 crm_total_count ft_sblue">
                    <?
                    $list_a = sql_fetch("select bo_count_write from g5_board where bo_table='crm';"); 
                    echo $list_a['bo_count_write']; // 전체 게시글 수
                    ?>
                </span>  
                <br>
                <p class="ft_blue fw700">총 스토리</p>
            </div>
        </div>
    <?} else {?>

        <div class="crm_info_wrap">
            <div class="crm_pf_wrap">
                <p class="crm_pf">
                    <a href="<?=G5_ADMIN_MEMBER_UPDATE_URL.$row['mb_id']?>">
                        <?    
                            $mb_prof_dir = substr($row['mb_id'],0,2);
                            $mb_prof_file = G5_DATA_PATH.'/member/'.$mb_prof_dir.'/'.get_mb_icon_name($row['mb_id']).'.gif';
                            
                            if (file_exists($mb_prof_file)) 
                                echo get_member_profile_img($row['mb_id']);
                        ?>
                    </a>
     
                <span> <?= $row['mb_name']?></span>

                </p>
            </div>
            <ul class="crm_info">
                <li class="crm_info_li">
                    <span class="fw900 ft_sblue"><?=$row['mb_sdate'] ? $row['mb_sdate']: '0' ?></span>
                    <p class="fw700 ft_blue">치료 시작일</p>
                </li>
                <li class="crm_info_li">
                    <span class="fw900 ft_sblue"><?=$row['mb_sdate'] ? $row['mb_fdate']: '0' ?></span>
                    <p class="fw700 ft_blue">치료 종료 예상일</p>
                </li>
                <li class="crm_info_li">
                    <span class="fw900 ft_sblue"><?php echo number_format($total_count) ?></span>
                    <p class="fw700 ft_blue">스토리</p>
                </li>
                <li class="crm_info_li">
                    <?= $d_day_count ?>
                    <p class="fw700 ft_blue">남은 기간</p>
                </li>
            </ul>
        </div>

        <ul class="crm_info_detail">
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">차트번호</p>
                <p><?= $row['mb_id']?></p>
            </li>
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">환자정보</p>
                <p><?= $row['mb_name']?>(<?=$row['mb_8'] == '0' ? '남' : '여'?>) / <span ><?= $row['mb_1']?> </span>(만 <span><?=$age?></span>세)</p>
            </li>
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">전화번호</p>
                <p class=""><?= $row['mb_hp']?> <? if($row['mb_tel']) {?><b class="fw700 ft_blue pt_tel">보호자</b> <?= $row['mb_tel']?> <? } ?></p>
            </li>
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">CASE</p>
                <p><?= $row['mb_2']?></p>
            </li>
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">치료형태</p>
                <p class="crm_info_detail_type">
                    <span>발치 진행 유무 : <?= $row['mb_3']?></span>
                    <span>교정 범위 : <?= $row['mb_4']?></span>
                    <span>교정 부위 : <?= $row['mb_5']?></span>
                    <span>교정 악궁 : <?= $row['mb_6']?></span>
                </p>
            </li>
            <li class="crm_info_detail_li">
                <p class="fw700 ft_blue">사용장치</p>
                <p><?= $row['mb_7']?></p>
            </li>
        </ul>
    <?}?>
<?}?>

<?if($sfl=='wr_2'){?>
<div class="crm_notice">
    환자명으로 검색하신 경우 아래 목록에서 아이콘 을 선택하신 후<br>"환자 정보가 나오는 화면"으로 이동 후 새글을 작성해주세요.
</div> 
<?}?>

    <div class="button_wrap">
        <?php if ($is_checkbox) { ?>
        <div id="gall_allchk" class="all_chk chk_box">
            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
            <label for="chkall">
                <span></span>
                <b class="sound_only">현재 페이지 게시물 </b> 전체선택
            </label>
        </div>
        <?php } ?>
        <?if($is_admin || $member['mb_level'] >=9 ){?>     
            <ul class="btn_bo_user">
                <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="관리자"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">관리자</span></a></li><?php } ?>
                <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>

                <?if($sfl=='wr_1'){?>
                <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>&stx=<?=$stx?>" class="btn_b01 btn" title="글쓰기"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">글쓰기</span></a></li><?php } ?>
                <?}?>
                <?php if ($is_admin == 'super' || $is_auth) {  ?>         
                <li>
                    <button type="button" class="btn_more_opt is_list_btn btn_b01 btn" title="게시판 리스트 옵션"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">게시판 리스트 옵션</span></button>
                    <?php if ($is_checkbox) { ?>	
                    <ul class="more_opt is_list_btn">  
                        <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
                        <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value"><i class="fa fa-files-o" aria-hidden="true"></i> 선택복사</button></li>
                        <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value"><i class="fa fa-arrows" aria-hidden="true"></i> 선택이동</button></li>
                    </ul>
                    <?php } ?>
                </li>
                <?php }  ?>
            </ul>
        <?php }  ?>
    </div>

    <ul id="gall_ul" class="gall_row">
        <!-- 핀고정 아래 추가 -->


        <?php for ($i=0; $i<count($list); $i++) {

            $classes = array();
            
            $classes[] = 'gall_li';

            if( $i && ($i % $bo_gallery_cols == 0) ){
                $classes[] = 'box_clear';
            }

            if( $wr_id && $wr_id == $list[$i]['wr_id'] ){
                $classes[] = 'gall_now';
            }

        ?>

        <li class="<?php echo implode(' ', $classes); ?>">
            <div class="gall_box">
                <div class="gall_chk">
                <?php if ($is_checkbox) { ?>
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                <?php } ?>

                <span>
                <?php
                    if ($wr_id == $list[$i]['wr_id'])
                        echo "<span class=\"bo_current\">열람중</span>";
                    else
                        echo "<span class='sound_only'>".$list[$i]['num']."</span>";
                ?>
                </span>
                </div>
                <div class="gall_con">
                <?if($is_admin && $member['mb_level'] >=9){?>
                    <div class="gall_pos">
                        <b><?=$list[$i]['wr_1']?></b>
                        <span><?=$list[$i]['wr_2']?></span>
                    </div>
                    <div class="gall_more">
                        <a href="/bbs/board.php?bo_table=<?=$bo_table;?>&page=&sca=&sfl=wr_1&stx=<?=$list[$i]['wr_1']?>">
                            <img src="<?=$board_skin_url?>/img/chart_icon.png" alt="환자 리스트 보러가기">
                        </a>
                    </div>
                <?}?>
                    <div class="gall_img">
                        <a href="<?php echo $list[$i]['href'] ?>">
                        <?
                            $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height'], false, true);
                            $cut_content = cut_str(strip_tags($list[$i]['wr_content']),40);

                            if($thumb['src']) {
                                echo '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
                            } else {
                                echo '<p style="padding:0 20px;">'.$cut_content.'</p>';
                            }
                        ?>
                        
                        </a>
                
                        
                    </div>
                    <div class="gall_text_href">       

						<?php
                        if ($is_category) {
                         ?>
                        <?php } ?>					
	
                        <a href="<?php echo $list[$i]['href'] ?>" class="bo_tit">			
                            <?php if ($list[$i]['comment_cnt']) { ?>
                                <div class="crm_comment">
                                <span class="cnt_cmt fw700"><?php echo $list[$i]['wr_comment']; ?></span>
                               </div>
                            <?php } ?>                          
                         </a>
                    </div>                  	
                </div>
				
            </div>

        </li>

        <?php } ?>
        <?php if (count($list) == 0) { echo "<li class=\"empty_list\"><p>게시물이 없습니다.</p></li>"; } ?>
    </ul>
	
	<!-- 페이지 -->
	<?php echo $write_pages; ?>
	<!-- 페이지 -->
	
	<?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="bo_fx">
        <?php if ($list_href || $write_href) { ?>
        <ul class="btn_bo_user">
        	<?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="관리자"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">관리자</span></a></li><?php } ?>
            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
        </ul>	
        <?php } ?>
    </div>
    <?php } ?> 


    </form>


</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}

</script>
<!--게시판 리스트 관리자 옵션  -->
<script src="<?=$board_skin_url?>/foradm.custom.js"></script>
<?php } ?>
<script src="<?=$board_skin_url?>/skin.custom.js"></script>

<!-- } 게시판 목록 끝 -->