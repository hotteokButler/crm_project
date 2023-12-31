<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가


// 관리자가 아닌 회원의 경우 로그인 아이디(차트번호)와 게시글 차트번호 체크
if ($is_member && !$is_admin && $view['wr_1']!== $member['mb_id']) {
    alert("잘못된 접근입니다" ,"/bbs/board.php?bo_table=".$bo_table."&page=&sca=&sfl=wr_1&stx=".$member['mb_id']);
}

// 현 url 
$view_url_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/view.css">', 0);
add_javascript('<script src="'.$board_skin_url.'/js/skin.custom.js"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/info_list.css">', 0);
// 최고 관리자일때만
if(!$is_admin) {
    add_javascript('<script src="'.$board_skin_url.'/js/block.js"></script>', 1);
}
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>



<? include_once('menu.skin.php');?>

<!-- 게시물 읽기 시작 { -->

<article id="bo_v">
    <header>
        <div id="bo_v_title">
            <?php if ($category_name) { ?>
            <span class="bo_v_cate"><?php echo $view['ca_name']; // 분류 출력 끝 ?></span> 
            <?php } ?>            
            <a href="/bbs/board.php?bo_table=crm<?=($is_admin || $member['mb_level'] >=9 ) ? '&page=&sca=&sfl=wr_1&stx='.$view['wr_1'] :'' ?>"></a>
            <p class="bo_v_tit color_point m">
            <?php
            echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
            ?></p>
        </div>
    </header>


    <?if($is_admin || $member['mb_level'] >=9 ){?>
    <section id="bo_v_info">
     

    	<!-- 게시물 상단 버튼 시작 { -->
        <?if($is_admin || $member['mb_level'] >=9 ){?>
	    <div id="bo_v_top">
	        <?php ob_start(); ?>

	        <ul class="btn_bo_user bo_v_com">
	            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>&stx=<?=$view['wr_1']?>" class="btn_b01 btn" title="글쓰기"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">글쓰기</span></a></li><?php } ?>
	        	<?php if($update_href || $delete_href || $copy_href || $move_href || $search_href) { ?>
	        	<li>
	        		<button type="button" class="btn_more_opt is_view_btn btn_b01 btn" title="게시판 리스트 옵션"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">게시판 리스트 옵션</span></button>
		        	<ul class="more_opt is_view_btn"> 
			            <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>">수정<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li><?php } ?>
			            <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;">삭제<i class="fa fa-trash-o" aria-hidden="true"></i></a></li><?php } ?>
			            <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;">복사<i class="fa fa-files-o" aria-hidden="true"></i></a></li><?php } ?>
			            <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;">이동<i class="fa fa-arrows" aria-hidden="true"></i></a></li><?php } ?>
			            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>">검색<i class="fa fa-search" aria-hidden="true"></i></a></li><?php } ?>
			        </ul> 
	        	</li>
	        	<?php } ?>
	        </ul>
	        <script>
            jQuery(function($){
                // 게시판 보기 버튼 옵션
				$(".btn_more_opt.is_view_btn").on("click", function(e) {
                    e.stopPropagation();
				    $(".more_opt.is_view_btn").toggle();
				});
                $(document).on("click", function (e) {
                    if(!$(e.target).closest('.is_view_btn').length) {
                        $(".more_opt.is_view_btn").hide();
                    }
                });
            });
            </script>
	        <?php
	        $link_buttons = ob_get_contents();
	        ob_end_flush();
	         ?>
	    </div>
	    <!-- } 게시물 상단 버튼 끝 -->
	    <div id="bo_v_top">
    <?php //echo $view['wr_1']; ?>
	</div>
        <?}?>
    </section>
    <?}?>

    <? // 검색된 회원 정보 가져오기
    $sql1 = "select * from {$g5['member_table']} where mb_id = {$view['wr_1']} ";
    $res1 = sql_query( $sql1);
    $member_row = sql_fetch_array( $res1);
    ?>

    <section>
		<div class="view_tit">
            <?
                if ($view['wr_6'] && $view['wr_7']) { 
                    echo '❤ '.$member_row['mb_name'].'님의 다음 진료 예약일은 '.$view['wr_6'].'('.$view['wr_7'].') 입니다.';
                } else {
                    echo   '❤ '.$member_row['mb_name'].'님의 다음 진료 예약이 없습니다.<br class="mobile_view"> 예약 진행 시 전화 문의 부탁드립니다.';
            } ?>
            <br>
            ❤ 진료일, 시간 변경은 전화로만 가능합니다. 
		</div>
    </section>



    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>
        <?php
        // 파일 출력
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<=count($view['file']); $i++) {
                echo get_file_thumbnail($view['file'][$i]);
            }

            echo "</div>\n";
        }
         ?>

        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php //echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>
        <!-- } 본문 내용 끝 -->

        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>


        <!--  추천 비추천 시작 { -->
        <?php if ( $good_href || $nogood_href) { ?>
        <div id="bo_v_act">
            <?php if ($good_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></a>
                <b id="bo_v_act_good"></b>
            </span>
            <?php } ?>
            <?php if ($nogood_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></a>
                <b id="bo_v_act_nogood"></b>
            </span>
            <?php } ?>
        </div>
        <?php } else {
            if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act">
            <?php if($board['bo_use_good']) { ?><span class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></span><?php } ?>
        </div>
        <?php
            }
        }
        ?>
        <!-- }  추천 비추천 끝 -->
    </section>



    <section>
	  <? if($member['mb_level'] == "10") {?>
		<div class="view_kakao_btn"><a href=""><input type="button" value="카카오톡 알림"></a></div>
		<br>
		<div class="view_send_txt">발송일시 : <?=$view['wr_8']?> / 발송인: <?=$view['wr_9']?> / 단축 URL : <?=$view['wr_10']?></div>
	 <? } ?>
    </section>


    <?php
    $cnt = 0;
    if ($view['file']['count']) {
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }
	?>

    <?php if($cnt) { ?>
    <!-- 첨부파일 시작 { -->
    <section id="bo_v_file">
        <h2>첨부파일</h2>
        <ul>
        <?php
        // 가변 파일
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
         ?>
            <li>
               	<i class="fa fa-folder-open" aria-hidden="true"></i>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download" download>
                    <strong><?php echo $view['file'][$i]['source'] ?></strong> <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                </a>
                <br>
                <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드 | DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <!-- } 첨부파일 끝 -->
    <?php } ?>

    <?php if(isset($view['link']) && array_filter($view['link'])) { ?>
    <!-- 관련링크 시작 { -->
    <section id="bo_v_link">
        <h2>관련링크</h2>
        <ul>
        <?php
        // 링크
        $cnt = 0;
        for ($i=1; $i<=count($view['link']); $i++) {
            if ($view['link'][$i]) {
                $cnt++;
                $link = cut_str($view['link'][$i], 70);
            ?>
            <li>
                <i class="fa fa-link" aria-hidden="true"></i>
                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                    <strong><?php echo $link ?></strong>
                </a>
                <br>
                <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
            </li>
            <?php
            }
        }
        ?>
        </ul>
    </section>
    <!-- } 관련링크 끝 -->
    <?php } ?>

    <?php
        if($is_admin || $member['mb_level'] >=9) {
            // 코멘트 입출력
            include_once(G5_BBS_PATH.'/view_comment.php');
        }
	?>
</article>
<!-- } 게시판 읽기 끝 -->
<?php // footer
include_once('skin.foot.php');
?>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>



<script>
$(function() {

    

    $('.view_kakao_btn') && 
        $('.view_kakao_btn').on('click', (e)=>{ 
            e.preventDefault();
            if (confirm(`
             수신인 : <?=$member_row["mb_name"] ?>\n
             수신번호 : <?=$member_row['mb_hp'] ? $member_row['mb_hp'] : $member_row['mb_tel'] ?> \n
             (확인 클릭 시 알림톡 발송됩니다)
            `)) {
                $.ajax({
                    url: "<?=$board_skin_url;?>/solapi/send_alimtalk.php",
                    data: {
                    "bo_table" : "<?=$bo_table?>",
                    "wr_id" : "<?=$wr_id?>",
                    "wr_8": "<?=date('Y-m-d')?>",
                    "wr_9": "<?=$member["mb_name"] ?>",
                    "wr_10": "<?=$view_url_link?>",
                    "tel_number" : "<?=$member_row['mb_hp'] ? $member_row['mb_hp'] : $member_row['mb_tel'] ?>",
                    "temp_reserv_date" : "<?=$view['wr_6']?>",
                    "temp_reserv_time" : "<?=$view['wr_7']?>",
                    "temp_name" : "<?=$member_row['mb_name']?>" ,
                    },
                    type: "POST",
                    dataType: "json",
                    }).done(function(data) {
                        console.log(data);
                        if (data.error) {
                            alert(data.error);
                            return false;
                        } else {
                        alert('정상적으로 전송 되었습니다');
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.status); // 상태값 출력 (404,500)
                        console.log(textStatus); // 상태에 대한 텍스트 출력 (error)
                        if(jqXHR.status == 404){
                            alert('올바른 방법으로 다시 이용해주세요-');
                        }
                    });
                } else {
                   alert('취소되었습니다.');
                }
            });




    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>
<!-- } 게시글 읽기 끝 -->
