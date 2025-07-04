// a little JS for the mobile nav button
if (document.getElementById("nav-mobile-btn")) {
  document
    .getElementById("nav-mobile-btn")
    .addEventListener("click", function () {
      if (this.classList.contains("close")) {
        document.getElementById("nav").classList.add("hidden");
        this.classList.remove("close");
      } else {
        document.getElementById("nav").classList.remove("hidden");
        this.classList.add("close");
      }
    });
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const id = this.getAttribute("href").substring(1);
    const el = document.getElementById(id);
    if (el) {
      e.preventDefault();
      el.scrollIntoView({ behavior: "smooth" });
    }
  });
});

$("#form-organik").slideUp();
$("#form-anorganik").slideUp();
$("#form-b3").slideUp();

$("#checkbox_organik").change(function () {
  if ($(this).is(":checked")) {
    $("#form-organik").slideDown();
  } else {
    $("#form-organik").slideUp();
  }
});

$("#checkbox_anorganik").change(function () {
  if ($(this).is(":checked")) {
    $("#form-anorganik").slideDown();
  } else {
    $("#form-anorganik").slideUp();
  }
});

$("#checkbox_b3").change(function () {
  if ($(this).is(":checked")) {
    $("#form-b3").slideDown();
  } else {
    $("#form-b3").slideUp();
  }
});

function toggleSVGsBasedOnCheckboxes() {
  const isAnyChecked =
    $("#checkbox_organik").is(":checked") ||
    $("#checkbox_anorganik").is(":checked") ||
    $("#checkbox_b3").is(":checked");
  if (isAnyChecked) {
    $("#triangle-left, #triangle-right").fadeIn();
  } else {
    $("#triangle-left, #triangle-right").fadeOut();
  }
}

$("#checkbox-organik, #checkbox-anorganik, #checkbox-b3").change(function () {
  toggleSVGsBasedOnCheckboxes();
});

$(document).ready(function () {
  toggleSVGsBasedOnCheckboxes();
});
