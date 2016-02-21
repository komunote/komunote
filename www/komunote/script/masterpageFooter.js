$(function() {
    errorSum = 0;
    $("#accordeon").accordion({autoHeight: false, animated: "bounceslide"});
    createDigicode("#digicode");
    fields = {"#pseudo": {regName: "numberLetterOnly"}, "#email": {regName: "email"}, "#email2": {regName: "email", equals: "#email"}, "#password": {regName: "numberLetterOnly"}, "#password2": {regName: "numberLetterOnly", equals: "#password"}, "#answer": {regName: "numberLetterOnly"}};
    for (var b in fields) {
        $(b).change(function() {
            var a = "#" + $(this).attr("id");
            if (!this.value.match(formats[fields[a].regName].reg) || (fields[a].equals != undefined && $(a).val() !== $(fields[a].equals).val())) {
                this.value = "";
                errorSum--;
                $("#errorForm").html(this.title + "1");
                showPopup(this, "#errorForm")
            }
        })
    }
    $("#email,#email2").attr("pattern", formats.email.reg);
    $('input:submit, .menuSelected, .menuNotSelected, [id^="link"], input:button').button();
    $(".menuSelected").addClass("ui-state-hover").hover(function() {
        $(this).addClass("ui-state-hover")
    }, function() {
        $(this).addClass("ui-state-hover")
    });
    $("#popupLogin, #popupLogout, #menuShop").hide();
    $("#linkLogout").click(function() {
        showPopup(this, "#popupLogout")
    });
    $("#linkLogin").click(function() {
        showPopup(this, "#popupLogin")
    });
    $('[id^="ui-alert"]').removeClass().addClass("ui-icon ui-icon-alert").css("float", "left");
    $('[id^="ui-info"]').removeClass().addClass("ui-icon ui-icon-info").css("float", "left");
    $("[type=text],[type=textarea],[type=email],[type=password]").css("width", "90%");
    //$("#bodyCenterDiv").show(1000);
    //$("#loginMenuNav").show(3000);
});