const mbIdCheck = function (id) {
  let result = "";
  $.ajax({
    type: "POST",
    url: "../bbs/ajax.mb_id.php",
    data: {
      reg_mb_id: id,
    },
    cache: false,
    async: false,
    success: function (data) {
      result = data;
    },
  });
  return result;
};

addEventListener("DOMContentLoaded", (e) => {
  // 차트번호 중복체크
  const chartIdCheck = document.getElementById("mb_id_check_btn") || null;
  const chartId = document.querySelector("form[name='fmember'] input[name='mb_id']");
  let idCMsg;

  chartIdCheck &&
    chartIdCheck.addEventListener("click", (ev) => {
      ev.preventDefault();
      idCMsg = mbIdCheck(chartId.value)

      if (idCMsg == "" || idCMsg == null) {
        if (!confirm("등록 가능한 차트번호입니다.\n현재 차트번호를 사용하시겠습니까?")) {
          chartId.value = "";
        }
      } else {
        alert("이미 등록 된 차트번호입니다.\n차트번호를 다시 확인해주세요");
      }
    });
});
