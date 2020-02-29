/* eslint-disable no-undef */
function readURL(input, selector) {
    var id = $("#" + selector + "_preview");
    var id2 = $("#" + selector + "_preview2");
    var id3 = $("#" + selector + "_preview3");
    var id4 = $("#" + selector + "_preview4");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            id.html(
                '<img src="' +
                    e.target.result +
                    '" class="img-responsive" style="max-width:100%; height:100px" alt="preview image">'
            );
            id2.html(
                '<img src="' +
                    e.target.result +
                    '" class="img-responsive" style="max-width:100%; height:100px" alt="preview image">'
            );
            id3.html(
                '<img src="' +
                    e.target.result +
                    '" class="img-responsive" style="max-width:100%; height:100px" alt="preview image">'
            );
            id4.html(
                '<img src="' +
                    e.target.result +
                    '" class="img-responsive" style="max-width:100%; height:100px" alt="preview image">'
            );
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        id.html(id);
        id2.html(id2);
        id3.html(id3);
        id4.html(id4);
    }
}
$("#image").change(function() {
    readURL(this, "image");
});
$("#image2").change(function() {
    readURL(this, "image2");
});
$("#image3").change(function() {
    readURL(this, "image3");
});
$("#image4").change(function() {
    readURL(this, "image4");
});
