
<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once('./_common.php');

// sql문 최초 실행 여분필드 추가용
// for ($k = 11; $k <= 20; $k++) {
//      sql_query(" ALTER TABLE `{$write_table}` ADD `wr_{$k}` varchar(255) NOT NULL DEFAULT '' ");
//  }

// $sql = " update $write_table
//             set wr_11 = '$wr_11',
//                  wr_12 = '$wr_12',
//                  wr_13 = '$wr_13',
//                  wr_14 = '$wr_14',
//                  wr_15 = '$wr_15',
//                  wr_16 = '$wr_16',
//                  wr_17 = '$wr_17',
//                  wr_18 = '$wr_18',
//                  wr_19 = '$wr_19',
//                  wr_20 = '$wr_20'
//           where wr_id = '$wr_id' ";
// sql_query($sql);
?>

