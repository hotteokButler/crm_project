let scrollEventTimer;
let windowScrollPos = window.scrollY;
const CRM_KEY = Object.freeze({
  fixed: "fixed",
});

// function ===================================================================
function inputNumberOnly(el) {
  el.value = el.value.replace(/[^0-9]/g, "");
}

/**
 * @param {number} winpos - window scrollY
 * @param {number} refHeight -기준높이
 * @param {*} target - 적용할 targetElem
 * @param {string} classname - class명
 */
function handleClassByScroll(winpos, refHeight, target, classname) {
  if (winpos < refHeight) {
    target.classList.contains(classname) && target.classList.remove(classname);
  } else {
    target.classList.contains(classname) || target.classList.add(classname);
  }
}

// DOM =========================================================================
document.addEventListener("DOMContentLoaded", () => {
  // elem
  const crmMenu = document.getElementById("crm_nav");
  const crmMenuHeight = crmMenu.clientHeight;





  handleClassByScroll(windowScrollPos, crmMenuHeight, crmMenu, CRM_KEY.fixed);

  // scroll event

  window.addEventListener("scroll", () => {
    if (scrollEventTimer) {
      clearTimeout(scrollEventTimer);
    }
    scrollEventTimer = setTimeout(function () {
      windowScrollPos = window.scrollY;
      handleClassByScroll(windowScrollPos, crmMenuHeight, crmMenu, CRM_KEY.fixed);
    }, 20);

    // scroll event End
  });

  // DOMevent End
});
