<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/common.php');
// 게시글 추가 저장

$bo_table = trim($_POST['bo_table']);	//bo_table
$wr_id = trim($_POST['wr_id']);	//wr_id

if(!$bo_table)
	die(json_encode(array('error'=>'위젯설정에서 보드아이디를 설정해주세요.')));

if(!$wr_id)
	die(json_encode(array('error'=>'필수값이 전달되지 않았습니다.')));


$wr_8 = trim($_POST['wr_8']);	//발송일시
$wr_9 = trim($_POST['wr_9']);	//발송인
$temp_url = trim($_POST['wr_10']);	//발송 url

$tel_number = str_replace('-', '',$_POST['tel_number']);	//전번
$temp_reserv_date = trim($_POST['temp_reserv_date']);	//예약 날짜
$temp_reserv_time = trim($_POST['temp_reserv_time']);	//예약 시간
$temp_name = trim($_POST['temp_name']);	//이름
$write_table = $g5['write_prefix'] . $bo_table;


$sql = "
	update {$write_table}
        set wr_8 = '$wr_8',
            wr_9 = '$wr_9',
            wr_10 = '$temp_url'
	where wr_id = '{$wr_id}'
";


$temp_reserv_txt = $temp_reserv_date && $temp_reserv_time ? $temp_reserv_date."(".$temp_reserv_time.")" : "다음 예약이 없습니다.";


if(sql_query($sql)){


/*
 * 한번 요청으로 1만건의 알림톡 발송이 가능합니다.
 * 템플릿 내용에 변수가 있는 경우 반드시 변수: 값 을 입력하세요.
 */
require_once("message.php");
// sms solapi 사용 시에만 발송
if($config['cf_sms_use'] == 'solapi') { 

$messages = array(
    array(
        "to" => $tel_number,
        "from" => $config['cf_5'],
        "kakaoOptions" => array(
            "pfId" => $config['cf_3'],
            "templateId" => $config['cf_4'],
            "variables" => array(
                "#{name}" => $temp_name,
                "#{date}" => $temp_reserv_txt,
                "#{board_url}" => $temp_url,
            ),
            "disableSms" => TRUE // 문자로 대체발송되지 않도록 합니다.
        )
    ),

);


send_messages($messages);

// 중복 수신번호를 허용하실 경우 아래와 같이 실행하시면 됩니다.
// print_r(send_messages($messages, true));
 } else {
    print_r(send_messages($messages, true));
 }
	// die(json_encode(array('error'=>'')));
}else{
	die(json_encode(array('error'=>'접수에 문제가 있습니다. 관리자에게 문의하세요.')));
}


