(function($) {
  var i = 0, label_info_version = $(".choose-version input"), label_info_version_init_text = label_info_version.text();
  $.fn.reverse = [].reverse;
  function validate(s) {
    var rgx = /^[0-9]*\.?[0-9]*\.?[0-9]*$/;
    if(s.match(rgx)) {
      return true;
    } else {
      return false;
    }
  }
  $(".loading").fadeOut(0);
  $("#submit").on("click touchend", function() {
    $(".loading").fadeIn(500);
  });
  var inter = setInterval(function() {
    if($(".readyToTakeOff, .error, .warning").length > 0) {
      $(".loading").fadeOut(0);
      $("body").animate({ scrollTop: $(document).height() }, 1000);
      clearInterval(inter);
    }
  }, 200);
  $("#form-t3-install").attr("action", "deploy.php");
  label_info_version.text("Please wait");
  $.getJSON("https://get.typo3.org/json", function(data) {
    var items = [];
    $.each(data, function(key, val) {
      i++;
      $.each(val.releases, function(k, v) {
        var s = v.version.toString();
        if(validate(s)) {
          items.push(v.version);
        }
      });
      if(i > 3) {
        return false;
      }
    });
    items.sort().reverse();
    label_info_version.replaceWith("<div class='dropdown dropdown-dark'><select class='t3_version dropdown-select' name='t3_version' required></select></div>");
    var selectT3Version = $("select.t3_version");
    $.each(items, function(i, el) {
      selectT3Version.prepend("<option value=" + el + ">Typo3 " + el + "</option>");
    });
    selectT3Version.find("option:first-child").attr("selected='selected'");
    $(".t3_version_label .info").fadeOut('fast');
  }).fail(function() {
    label_info_version.text(label_info_version_init_text);
    console.log("getJSON failed!");
  });
})(jQuery);
 (function($) {
  $('#generate-install-pw').pGenerator({
      'bind': 'click',
      'passwordElement': '#install-tool-pw',
      'displayElement': '#install-tool-pw-element',
      'passwordLength': 16,
      'uppercase': true,
      'lowercase': true,
      'numbers':   true,
      'specialChars': true,
      'onPasswordGenerated': function(generatedPassword) {
      alert('My new generated password is ' + generatedPassword);
      }
  });
})(jQuery);
