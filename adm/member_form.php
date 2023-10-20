<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

$mb = array(
'mb_certify' => null,
'mb_adult' => null,
'mb_sms' => null,
'mb_intercept_date' => null,
'mb_id' => null,
'mb_name' => null,
'mb_nick' => null,
'mb_point' => null,
'mb_email' => null,
'mb_homepage' => null,
'mb_hp' => null,
'mb_tel' => null,
'mb_zip1' => null,
'mb_zip2' => null,
'mb_addr1' => null,
'mb_addr2' => null,
'mb_addr3' => null,
'mb_addr_jibeon' => null,
'mb_signature' => null,
'mb_profile' => null,
'mb_memo' => null,
'mb_leave_date' => null,
'mb_1' => null,
'mb_2' => null,
'mb_3' => null,
'mb_4' => null,
'mb_5' => null,
'mb_6' => null,
'mb_7' => null,
'mb_8' => null,
'mb_9' => null,
'mb_10' => null,
'mb_sdate' => null,
'mb_fdate' => null,
);

$sound_only = '';
$required_mb_id_class = '';
$required_mb_password = '';

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
    $mb['mb_sdate'] = get_text($mb['mb_sdate']);
    $mb['mb_fdate'] = get_text($mb['mb_fdate']);
}

else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if(isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if(!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if(!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if(!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

// 이메일인증 체크 필드추가
if(!isset($mb['mb_email_certify2'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_email_certify2` varchar(255) NOT NULL DEFAULT '' AFTER `mb_email_certify` ", false);
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;
include_once('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="mb_homepage" value="notuse">
<input type="hidden" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="mb_id">아이디(차트번호)<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  maxlength="20">
            <?php if ($w=='u'){ ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>" class="btn_frmline">접근가능그룹보기</a><?php } ?>
        </td>
        <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
        <td><input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15"  maxlength="20" ></td>
        <th scope="row"><label for="mb_1">생년월일<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="date" name="mb_1" value="<?php echo $mb['mb_1'] ?>" id="mb_1" required class="required frm_input" size="15"  maxlength="20">
            <span class="example_txt">예&#41;2003-01-01</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_level">회원 권한</label></th>
        <td>
            <?php echo help('교정 중인 경우 권한 6, 교정 마무리 후 권한 5로 변경') ?>
            <?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?>
        </td>
        <th scope="row">포인트</th>
        <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 점</td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
        <th scope="row"><label for="mb_sex">성별</label></th>
        <td>
            <div>
                <input type="radio" name="mb_sex" value="0" id="man" class="frm_input"  <?=$mb['mb_sex'] =='0'? 'checked' : '' ?> >
                <label for="man">남자</label>
            </div>
            <div>
                <input type="radio" name="mb_sex" value="1" id="woman" class="frm_input" <?=$mb['mb_sex'] =='1'? 'checked' : '' ?>>
                <label for="woman">여자</label>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_hp">휴대폰번호</label></th>
        <td>
            <input class="required frm_input" type="text" name="mb_hp" oninput="oninputPhone(this)" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" size="15" maxlength="20">
            <span>예&#41;010-1234-1234</span>
        </td>
        <th scope="row"><label for="mb_tel">보호자 휴대폰번호</label></th>
        <td>
            <input class="required frm_input" type="text" name="mb_tel" oninput="oninputPhone(this)" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" size="15" maxlength="20">
            <span>예&#41;010-1234-1234</span>
        </td>
    </tr>
    <tr>
        <th scope="row">본인확인방법</th>
        <td colspan="3">
            <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if($mb['mb_certify'] == 'ipin') echo 'checked="checked"'; ?>>
            <label for="mb_certify_ipin">아이핀</label>
            <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if($mb['mb_certify'] == 'hp') echo 'checked="checked"'; ?>>
            <label for="mb_certify_hp">휴대폰</label>
        </td>
    </tr>
    <tr>
        <th scope="row">주소</th>
        <td colspan="3" class="td_addr_line">
            <label for="mb_zip" class="sound_only">우편번호</label>
            <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
            <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
            <label for="mb_addr1">기본주소</label><br>
            <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
            <label for="mb_addr2">상세주소</label>
            <br>
            <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
            <label for="mb_addr3">참고항목</label>
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_icon">회원아이콘</label></th>
        <td colspan="3">
            <?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="mb_icon" id="mb_icon">
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
            if (file_exists($icon_file)) {
                $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file);
                $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file) : '';
                echo '<img src="'.$icon_url.$icon_filemtile.'" alt="">';
                echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_img">회원이미지</label></th>
        <td colspan="3">
            <?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_img_width'].'픽셀 높이 '.$config['cf_member_img_height'].'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="mb_img" id="mb_img">
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.get_mb_icon_name($mb['mb_id']).'.gif';
            if (file_exists($icon_file)) {
                echo get_member_profile_img($mb['mb_id']);
                echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row">메일 수신</th>
        <td>
            <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
            <label for="mb_mailling_yes">예</label>
            <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
            <label for="mb_mailling_no">아니오</label>
        </td>
        <th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
        <td>
            <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
            <label for="mb_sms_yes">예</label>
            <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
            <label for="mb_sms_no">아니오</label>
        </td>
    </tr>
    <tr>
        <th scope="row">정보 공개</th>
        <td colspan="3">
            <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
            <label for="mb_open_yes">예</label>
            <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
            <label for="mb_open_no">아니오</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_sdate">치료시작일</label></th>
        <td colspan="3">
            <input class="required frm_input" type="date" name="mb_sdate" id="mb_sdate" value="<?=$mb['mb_sdate'];?>" pattern="\d{4}-\d{2}-\d{2}">
            <span class="example_txt">ex&#41;연도-월-일</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_fdate">치료종료예상일</label></th>
        <td colspan="3">
            <input class="required frm_input" type="date" name="mb_fdate" id="mb_fdate" value="<?=$mb['mb_fdate'];?>" pattern="\d{4}-\d{2}-\d{2}">
            <span class="example_txt">ex&#41;연도-월-일</span>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="mb_memo">추가 기입 사항</label></th>
        <td colspan="3"><textarea name="mb_memo" id="mb_memo"><?php echo $mb['mb_memo']; ?></textarea></td>
    </tr>
    
    <?php if ($config['cf_use_recommend']) { // 추천인 사용 -> 소개 환자 시 사용! 차트번호 기입으로 체크 ?>
    <tr>
        <th scope="row">추천인</th>
        <td colspan="3"><?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?></td>
    </tr>
    <?php } ?>

    <?//여분필드추가 시작-> ?>
    <tr id="ortho_case">
        <th scope="row"><label for="mb_2">CASE</label></th>
        <td colspan="3">
            <?php
            //case 추가 시 list에만 추가
            $ortho_case_li = ['돌출입','덧니','치아사이공간','개방교합','과개교합','반대교합','비수술주걱턱','비수술무턱','수술교정','재교정','소아교정'];
            $ortho_case_checked="";
            for ($i=1; $i<=count($ortho_case_li); $i++) { 
                $ortho_case_checked = $ortho_case_li[$i-1] == $mb['mb_2'] ? 'checked' : '';
                ?>
                <input type="radio" name="mb_2" value="<?=$ortho_case_li[$i-1];?>" id="<?='case'.$i;?>" <?=$ortho_case_checked?>>
                <label for="<?='case'.$i;?>"><?=$ortho_case_li[$i-1];?>&nbsp;&nbsp;</label>
            <?php } ?>
        </td>
    </tr>
                
    <tr id="treat_case">
        <th scope="row">치료형태</th>
        <td colspan="3">
            <!-- 01 -->
            <ul class="treat_case01">
                <li class="treat_idx">발치 진행 유무</li>
                <li>
                    <input type="radio" name="mb_3" value="발치" id="treat_c0101" <?=$mb['mb_3'] == "발치" ? 'checked' : ''?>>
                    <label for="treat_c0101">발치&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_3" value="비발치" id="treat_c0102" <?=$mb['mb_3'] == "비발치" ? 'checked' : ''?>>
                    <label for="treat_c0102">비발치&nbsp;&nbsp;</label>
                </li>
            </ul>
            <!-- 02 -->
            <ul class="treat_case02">
                <li class="treat_idx">교정 범위</li>
                <li>
                    <input type="radio" name="mb_4" value="전체" id="treat_c0201" <?=$mb['mb_4'] == "전체" ? 'checked' : ''?>>
                    <label for="treat_c0201">전체&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_4" value="부분" id="treat_c0202" <?=$mb['mb_4'] == "부분" ? 'checked' : ''?>>
                    <label for="treat_c0202">부분&nbsp;&nbsp;</label>
                </li>
            </ul>
            <!-- 03 -->
            <ul class="treat_case03">
                <li class="treat_idx">교정 부위</li>
                <li>
                    <input type="radio" name="mb_5" value="순측" id="treat_c0301" <?=$mb['mb_5'] == "순측" ? 'checked' : ''?>>
                    <label for="treat_c0301">순측&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_5" value="설측" id="treat_c0302" <?=$mb['mb_5'] == "설측" ? 'checked' : ''?>>
                    <label for="treat_c0302">설측&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_5" value="콤비" id="treat_c0303" <?=$mb['mb_5'] == "콤비" ? 'checked' : ''?>>
                    <label for="treat_c0303">콤비&nbsp;&nbsp;</label>
                </li>
            </ul>
            <!-- 04 -->
            <ul class="treat_case04">
                <li class="treat_idx">교정 악궁</li>
                <li>
                    <input type="radio" name="mb_6" value="양악" id="treat_c0401" <?=$mb['mb_6'] == "양악" ? 'checked' : ''?>>
                    <label for="treat_c0401">양악&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_6" value="상악" id="treat_c0402" <?=$mb['mb_6'] == "상악" ? 'checked' : ''?>>
                    <label for="treat_c0402">상악&nbsp;&nbsp;</label>
                    <input type="radio" name="mb_6" value="하악" id="treat_c0403" <?=$mb['mb_6'] == "하악" ? 'checked' : ''?>>
                    <label for="treat_c0403">하악&nbsp;&nbsp;</label>
                </li>
            </ul>
        </td>
    </tr>

    <tr id="ortho_inc">
        <th scope="row"><label for="mb_7">치료장치</label></th>
        <td colspan="3">
            <?php
            //장치 추가 시 list에만 추가
            $ortho_inc_li = ['클리피엠','클리피씨','데이몬클리어','크라리티울트라','인비절라인','클리피엘','MTA'];
            $ortho_inc_checked="";
            for ($i=1; $i<=count($ortho_inc_li); $i++) { 
                $ortho_inc_checked = $ortho_inc_li[$i-1] == $mb['mb_7'] ? 'checked' : '';
                ?>
                <input type="radio" name="mb_7" value="<?=$ortho_inc_li[$i-1];?>" id="<?='ortho_inc'.$i;?>" <?=$ortho_inc_checked?>>
                <label for="<?='ortho_inc'.$i;?>"><?=$ortho_inc_li[$i-1];?>&nbsp;&nbsp;</label>
            <?php } ?>
        </td>
    </tr>

    <?php
    run_event('admin_member_form_add', $mb, $w, 'table');
    ?>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('회원이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}


</script>
<script src="./admin.custom.js"></script>
<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');