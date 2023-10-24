<? 
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

$sql = "select * from m3rating where bo_table = '$bo_table' and star_list = '$member[mb_id]'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$star_data = $row[star_data];
$wr_25 = $star_data;



$wr_19 = $res1['documents'][0]['y'];
$wr_20 = $res1['documents'][0]['x'];



if(!$wr_comment) {  // 코멘일때는 저장하면 안됩. 

	$sql = " update $write_table
                set wr_11 = '$wr_11',
	    			wr_12 = '$wr_12',
					wr_13 = '$wr_13',
					wr_14 = '$wr_14',
					wr_15 = '$wr_15',
					wr_16 = '$wr_16',
					wr_17 = '$wr_17',
					wr_18 = '$wr_18',
					wr_19 = '$wr_19',
					wr_20 = '$wr_20',
		    		wr_21 = '$wr_21',
					wr_22 = '$wr_22',
					wr_23 = '$wr_23',
					wr_24 = '$wr_24',
					wr_25 = '$wr_25',
					wr_26 = '$wr_26',
					wr_27 = '$wr_27',
					wr_28 = '$wr_28',
					wr_29 = '$wr_29',
					wr_30 = '$wr_30',
					wr_31 = '$wr_31'
					
                    $sql_ip
                    $sql_password
              where wr_id = '$wr_id' ";
	  $result = sql_query($sql);
	
	}

?>


