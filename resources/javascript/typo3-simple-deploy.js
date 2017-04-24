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
(function($) {
  var formDbData = $(".form-db-data"),
      formInstallTool = $(".form-install-tool");
  $('.t3_function').on('change', function() {
    var that = $(this), val = that.val();
    if(val === "firstinstall") {
      formDbData.fadeIn();
      formInstallTool.fadeIn();
    } else if(val === "downloadextract") {
      formDbData.fadeOut();
      formInstallTool.fadeOut();
    } else if(val === "downloadextractlink") {
      formDbData.fadeOut();
      formInstallTool.fadeOut();
    }
  });
})(jQuery);

(function($) {
  var dictionary, set_lang;

  // Object literal behaving as multi-dictionary
  dictionary = {
    "english": {
        "_aftersuccess": "After success:",
        "_pleasedelete": "Please delete this file (deploy.php)! Or click",
        "_deleteme": "delete me!",
        "_yourversion": "Enter your desired version:",
        "_t3function": "Please choose:",
        "_pleaseuseform": "(Please use this form: 6.2.12)",
        "_databaseisstored": "Database Access data are stored in 'typo3_config/typo3_db.php'.",
        "_databasename": "Database name",
        "_databaseuser": "Database username",
        "_databaseuserpassword": "Database userpassword",
        "_databasehost": "Database host",
        "_databasesocket": "Database socket",
        "_installtoolstoredin": "Install Tool password is stored in 'typo3_config/typo3_db.php'.",
        "_installpassword": "Install Tool password",
        "_generatepassword": "Generate a password",
        "_send": "Send",
        "_t3functiondelete": "Here you can specify and delete the Typo3 version you no longer need:",
        "_t3functiondelete_existsversions": "Typo3 versions which exists in '../typo3_sources/':",
        "_senddelete": "Delete Typo3 source"

    },
    "german": {
        "_aftersuccess": "Nach erfolgreicher Installation:",
        "_pleasedelete": "Bitte lösche diese Datei (deploy.php)! Oder klicke hier",
        "_deleteme": "lösche mich!",
        "_yourversion": "Gib deine gewünschte Version ein:",
        "_t3function": "Bitte auswählen:",
        "_pleaseuseform": "(bitte in dieser Form: 6.2.12)",
        "_databaseisstored": "Datenbank Zugangsdaten sind in 'typo3_config/typo3_db.php' gespeichert.",
        "_databasename": "Datenbank Name",
        "_databaseuser": "Datenbank Benutzer",
        "_databaseuserpassword": "Datenbank Benutzerpasswort",
        "_databasehost": "Datenbank Host",
        "_databasesocket": "Datenbank Socket",
        "_installtoolstoredin": "Install Tool Passwort ist gespeichert in 'typo3_config/typo3_db.php'.",
        "_installpassword": "Install Tool Passwort",
        "_generatepassword": "Generiere ein Passwort",
        "_send": "Absenden",
        "_t3functiondelete": "Hier kannst du die Typo3 Version(en) löschen die du nicht mehr benötigst:",
        "_t3functiondelete_existsversions": "Typo3 Versionen die in '../typo3_sources/' liegen:",
        "_senddelete": "Lösche diesen Typo3 Source"
    }
}

    // Function for swapping dictionaries
    set_lang = function (dictionary) {
        $("[data-translate]").text(function () {
            var key = $(this).data("translate");
            if (dictionary.hasOwnProperty(key)) {
                return dictionary[key];
            }
        });
    };

    // Swap languages when menu changes
    $("#lang").on("change", function () {
        var language = $(this).val().toLowerCase();
        if (dictionary.hasOwnProperty(language)) {
            set_lang(dictionary[language]);
        }
    });

    // Set initial language to English
    set_lang(dictionary.english);
})(jQuery);
