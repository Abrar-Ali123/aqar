document.querySelectorAll("[toast-list]") &&
    document.writeln(
        "<script src='dashboard/libs/toastify-js/src/toastify.js'></script>"
    ),
    document.querySelectorAll("[data-provider]") &&
        document.writeln(
            "<script src='dashboard/libs/flatpickr/flatpickr.min.js'></script>"
        ),
    document.querySelectorAll("[data-choices]") &&
        document.writeln(
            "<script src='dashboard/libs/choices.js/public/assets/scripts/choices.min.js'></script>"
        );
