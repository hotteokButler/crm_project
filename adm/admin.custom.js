// 공통 함수

const oninputPhone = function (target) {
  target.value = target.value.replace(/[^0-9]/g, "").replace(/(^02.{0}|^01.{1}|[0-9]{3,4})([0-9]{3,4})([0-9]{4})/g, "$1-$2-$3");
};

// DOM Load 후

addEventListener("DOMContentLoaded", (e) => {
  
});
