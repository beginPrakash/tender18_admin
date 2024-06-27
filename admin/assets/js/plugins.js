var link = document.getElementById("admin_url").value
document.querySelectorAll("[toast-list]") &&
  document.writeln(
    "<script src='" +
      link +
      "assets/libs/toastify-js/src/toastify.js'></script>"
  ),
  document.querySelectorAll("[data-provider]") &&
    document.writeln(
      "<script src='" +
        link +
        "assets/libs/flatpickr/flatpickr.min.js'></script>"
    ),
  document.querySelectorAll("[data-choices]") &&
    document.writeln(
      "<script src='" + link + "assets/libs/choices/choices.min.js'></script>"
    )
