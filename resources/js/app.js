require("./bootstrap");
window.toastr = require("toastr");
import select2 from "select2";
import $ from "jquery";

$(() => {
    $(".select2").select2({
        placeholder: "Choose",
    });
    $(".tag-select2").select2({
        tags: true,
    });

});
