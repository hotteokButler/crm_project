<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/write.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_javascript('<script src="'.$board_skin_url.'/js/skin.custom.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/info_list.css">', 0);


// 6등급 회원 조회 
$wr_set= array();
$sql1="select mb_id, mb_name from {$g5['member_table']} where (mb_level in (6,5)) order by mb_id desc";
$res1=sql_query( $sql1);
while( $row= sql_fetch_array( $res1)) $wr_set[ $row['mb_id']]= $row['mb_id'].'('.$row['mb_name'].')';

function tag_select(
$tuples,
$selected_value=NULL,
$flag=NULL, // 선택,전체,없음
$control_name=NULL,
$attrs=NULL ) {
        if( $control_name) { echo '<select name="',$control_name,'" ';
        	if( $attrs) echo $attrs;
        	echo ' >';
	}
        switch( $flag) {
        case 'all': echo '<option value="all">전체</option>';   break;
        case 'sel': echo '<option value="">선택</option>';      break;
        default: echo '';
        }
        foreach( $tuples as $opt_val=>$opt_text) {
                echo '<option value="',$opt_val,'" ';
                if( $stx==$opt_val) echo ' selected ';
                echo ' >',$opt_text,'</option>';
        }
        if( $control_name) echo '</select>';
}
?>

<? include_once('menu.skin.php');?>

<!------------------------------------------------------->

<style>
.cke_sc { display: none; }
</style>


<section id="bo_w">
    <h2 class="sound_only"><?php echo $g5['title'] ?></h2>
    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="margin:auto;">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) { 
        $option = '';
        if ($is_notice) {
            $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="notice" name="notice"  class="selec_chk" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice"><span></span>공지</label></li>';
        }
        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" class="selec_chk" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html"><span></span>html</label></li>';
            }
        }
        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="secret" name="secret"  class="selec_chk" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret"><span></span>비밀글</label></li>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }
        if ($is_mail) {
            $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="mail" name="mail"  class="selec_chk" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail"><span></span>답변메일받기</label></li>';
        }
    }
    echo $option_hidden;
    ?>
    
    
    <?php if ($is_category) { ?>
    <div class="bo_w_select write_div">
        <label for="ca_name" class="sound_only">분류<strong>필수</strong></label>
        <select name="ca_name" id="ca_name" required>
            <option value="">분류를 선택하세요</option>
            <?php echo $category_option ?>
        </select>
    </div>
    <?php } ?>

    <div class="bo_w_info write_div">
	    <?php if ($is_name) { ?>
	        <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
	        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input half_input required" placeholder="이름">
	    <?php } ?>
	
	    <?php if ($is_password) { ?>
	        <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
	        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input half_input <?php echo $password_required ?>" placeholder="비밀번호">
	    <?php } ?>
	
	    <?php if ($is_email) { ?>
			<label for="wr_email" class="sound_only">이메일</label>
			<input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input half_input email " placeholder="이메일">
	    <?php } ?>
	    
	    <?php if ($is_homepage) { ?>
	        <label for="wr_homepage" class="sound_only">홈페이지</label>
	        <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input half_input" size="50" placeholder="홈페이지">
	    <?php } ?>
	</div>
	
    <?php if ($option) { ?>
    <div class="write_div">
        <span class="sound_only">옵션</span>
        <!-- <ul class="bo_v_option">
        <?php echo $option ?>
        </ul> -->
    </div>
    <?php } ?>


<!-- 회원선택 -->
<div class="bo_w_select write_div">


    <? // 검색된 회원 정보 가져오기
    $sql1="select * from {$g5['member_table']} where (mb_level in (6,5)) and mb_id = '$stx' order by mb_name ";
    $res1=sql_query( $sql1);
    $row= sql_fetch_array( $res1);
    ?>

    <?php if($stx){?>


    <? //D-day 계산 (오늘날짜 기준)

        $d_start = date("Y-m-d", time());
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

   <?if($is_admin || $member['mb_level'] >=9 ){?>
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
                <p><?= $row['mb_name']?>(<?=$row['mb_8'] == '0' ? '남' : '여'?>) / <span><?= $row['mb_1']?> </span>(만 <span><?=$age?></span>세)</p>
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

    <input type="hidden" name="wr_1" value="<?=$stx?>">  
    <input type="hidden" name="wr_2" value="<?=$row['mb_name']?>">  
    <input type="hidden" name="wr_5" value="<?=$row['mb_1']?>">  
  
    <?php }else{ ?>
        <select name="wr_1" id="wr_1" required>
            <option value="">회원을 선택하세요</option>
                <?php tag_select( $wr_set, $write['wr_1']); ?>			
        </select>       
    <? }?>

    <ul class="notice_txt">
        <li><img src="<?=$board_skin_url;?>/img/notice_ico.png" alt="공지">이미지 사이즈 안내</li>
        <li>- 이미지 최소 사이즈 : 가로 900px, 세로 600px 이상</li>
        <li>- <a href="https://www.iloveimg.com/ko/resize-image/resize-jpg" target="_blank">이미지 사이즈 변경하러가기</a> ('가로세로 비율유지' 체크 후 변경)</li>
    </ul>

    </div>

    <div class="pin_fix_wrap">
    <input type="checkbox" id="pin_fix" name="notice"  class="pin_fix" value="1" <?=$notice_checked?>>
    <label for="pin_fix"><span></span>상단 고정</label>
    </div>
    <div class="bo_w_tit write_div">
        <label for="wr_subject" class="sound_only">제목<strong>필수</strong></label>

        <? $today = date("Ymd H:i:s");?>

        <div id="autosave_wrapper" class="write_div">
            <input type="hidden" name="wr_subject" value="<?php echo $today ?>" id="wr_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="제목">
            <?php if ($is_member) { // 임시 저장된 글 기능 ?>
            <script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
            <?php if($editor_content_js) echo $editor_content_js; ?>
            <button type="button" id="btn_autosave" class="btn_frmline">임시 저장된 글 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
            <div id="autosave_pop">
                <strong>임시 저장된 글 목록</strong>
                <ul></ul>
                <div><button type="button" class="autosave_close">닫기</button></div>
            </div>
            <?php } ?>
        </div>
    </div>    
    <div class="write_div">
        <label for="wr_content" class="sound_only">내용<strong>필수</strong></label>
        <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div>
        
    </div>


    <div class="write_div re_wirte_div"> 다음 진료 예약일
			<input type="date" name="wr_6" value="<?php echo $write['wr_6']?>" id="wr_6" max="9999-12-31"  class="" size="10" style="height:30px;" placeholder="예약일">
			<input type="time" name="wr_7" value="<?php echo $write['wr_7']?>" id="wr_7"  class="" size="10" style="height:30px;" placeholder="예약시간">
    </div>

    <?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>

    <div class="btn_confirm write_div btn_write_div">
        <a href="<?php echo get_pretty_url($bo_table); ?>&sca=&sop=and&sfl=wr_1&stx=<?=$stx?>" class="btn_cancel btn">취소</a>
        <button type="submit" id="btn_submit" accesskey="s" class="btn_submit btn">작성완료</button>
    </div>
    </form>

    <script>
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });
    <?php } ?>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>

<?php // footer
include_once('skin.foot.php');
?>
<!-- } 게시물 작성/수정 끝 -->
