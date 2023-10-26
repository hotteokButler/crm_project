$(function () {
  $(".btn_more_opt.is_list_btn").on("click", function (e) {
    e.stopPropagation();
    $(".more_opt.is_list_btn").toggle();
  });
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".is_list_btn").length) {
      $(".more_opt.is_list_btn").hide();
    }
  });
  // script 추가
  $(".bo_sch select[name='sfl']").on("change", function () {
    if ($(this).children("option:selected").val() == "wr_2") {
      $('.bo_sch input[name="stx"]').attr("placeholder", "성함을 입력해주세요");
      $(".bo_sch .sch_bar").before(
        '<div class="sch_bar sch_bar_date"><input type="date" name="wr_5" value="" required id="stx" class="sch_input" size="25" maxlength="20"></div><span>정확한 조회를 위해 생년월일을 입력해주세요</span>'
      );
    } else {
      $(".sch_bar_date").length > 0 && $("div").remove(".sch_bar_date");
    }
  });
});



