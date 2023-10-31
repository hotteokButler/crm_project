const checkDataInputElem =
  '<div class="sch_bar_date"><input type="date" name="wr_5" value="" id="stx" required class="sch_input" size="25" maxlength="20"><span class="sch_bar_date">정확한 조회를 위해 <b>생년월일</b>을 필히 입력해주세요</span></div>';

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
  const searchFieldElem = $(".bo_sch select[name='sfl']");

  if (searchFieldElem.children("option:selected").val() == "wr_2") {
    $(".bo_sch .sch_bar").after(checkDataInputElem);
  }



  $(".sch_bar_date").length > 0 && $("div,span").remove(".sch_bar_date");

  searchFieldElem.on("change", function () {
    if ($(this).children("option:selected").val() == "wr_2") {
      $('.bo_sch input[name="stx"]').attr("placeholder", "성함을 입력해주세요").removeAttr("oninput");
      searchFieldElem.after(checkDataInputElem);
    } else {
      $('.bo_sch input[name="stx"]').attr({
        placeholder: "차트번호 5자리를 입력해주세요",
        oninput: "inputNumberOnly(this)",
      });

      $(".sch_bar_date").length > 0 && $("div,span").remove(".sch_bar_date");
    }
  });

  $("#show_detail_sch").on("click", function () {
    const target = $(".sch_select_wrap");
    target.toggleClass("visible");

    if (target.hasClass("visible")) {
      $('.bo_sch input[name="stx"]').attr({
        placeholder: "차트번호를 입력해주세요",
        oninput: "inputNumberOnly(this)",
      });
    }
    searchFieldElem.val("wr_2") && searchFieldElem.val("wr_1").trigger("change");

    target.hasClass("visible") && target.css("display", "flex");
    target.hasClass("visible") || target.css("display", "none");
  });
});
