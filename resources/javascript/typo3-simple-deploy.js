// magic do not touch
(function($) {
    $(function() {
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

        label_info_version.text("Please wait");

        $.post('api/index.php', '{ "formtype":"ajaxpost", "ajax_function":"getTypo3Json" }', function(returnedData) {
            returnedData = JSON.parse(returnedData);
            var items = [];
            $.each(returnedData, function(key, val) {
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

            var selectT3Version = $("#t3_version");
            selectT3Version.html("");
            $.each(items, function(i, el) {
                if (i === 0) {
                    selectT3Version.prepend("<option value=" + el + " selected>Typo3 " + el + "</option>");
                } else {
                    selectT3Version.prepend("<option value=" + el + ">Typo3 " + el + "</option>");
                }

            });
            $("#s2id_t3_version").find("span").text($("#t3_version").find("option:last-child").text());
        }).fail(function(e) {
            alert( "Can't load json from http://get.typo3.org/json: " + e );
        });
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
    var t3_function = $("#t3function"), display = t3_function.parent().find(".display");

    function switchInAll(element) {
        element.each(function(i, el) {
            $(el).fadeIn(400);
        });
    }
    function switchOutAll(element) {
        element.each(function(i, el) {
            $(el).fadeOut(400);
        });
    }

    function t3_function_hide() {
        t3_function.find(".select2-container").find("span").on("DOMSubtreeModified", function() {
            var val = $(this).text();
            switch (val) {
                case "Full Install":
                    switchInAll(display);
                    break;
                default:
                    switchOutAll(display);
            }
        });
    }

    var refreshIntervalId = setInterval(function() {
        if(t3_function.find(".select2-container").find("span").length > 0) {
            t3_function_hide();
            clearInterval(refreshIntervalId);
        }
    }, 200);
})(jQuery);


(function($) {
    function assocJSON(formArray) {
        var tmp = {};
        $.each(formArray, function() {
            tmp[this.name] = this.value;
        });
        return tmp;
    }

    $("body").on("submit", "#form-login, #form-logout", function(e) {
        e.preventDefault();
        var $this = $(this), data = $this.serializeArray();
        var tmpData = JSON.stringify(assocJSON(data));

        $.post('api/index.php', tmpData, function(returnedData) {
            $("#form-login-out").html(returnedData);
            console.log(returnedData);
        });
    });

    var loader = $("#loader");
    $("body").on("submit", "#form-delete-deployment, #form-install-typo3, #form-delete-typo3source, #form-delete-typo3temp, #form-set-typo3filepermission", function(e) {
        e.preventDefault();
        loader.fadeIn("slow");
        var $this = $(this), data = $this.serializeArray();
        var tmpData = JSON.stringify(assocJSON(data));

        $.post('api/index.php', tmpData, function(returnedData) {
            $("#deploy-output").html(returnedData);

            var ajaxData = $("#list-typo3-sources").data("ajax");
            var ajaxTmpData = JSON.stringify(ajaxData);
            $.post('api/index.php', ajaxTmpData, function(returnedData) {
                $("#list-typo3-sources").html(returnedData);
                loader.fadeOut("fast");
            });
        });
    });

    $("body").on("submit", "#form-create-installfile", function(e) {
        e.preventDefault();
        var $this = $(this), data = $this.serializeArray();
        var tmpData = JSON.stringify(assocJSON(data));
        $.post('api/index.php', tmpData, function(returnedData) {
            $("#form-create-installfile").prepend("<div class='info'>"+returnedData+"</div>").fadeIn();
            setTimeout(function() {
                $("#form-create-installfile .info").fadeOut();
            }, 3000);
        });
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
            "_senddelete": "Delete or zips and delete source(s)"

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
            "_senddelete": "Lösche oder zippe und lösche dann den Typo3 Source"
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

// Page change
(function($) {
    var sidebar = $("#sidebar"), container = $("#content");

    sidebar.children("ul").find('a').on("click touchend", function(e) {
        e.preventDefault();
        var a = $(this), active = sidebar.find(".active");
        if (!a.parent("li").hasClass('active')) {
            var url = a.data("target"), current = active.children("a").data("target");
            container.find("."+current).fadeOut(400, function() {
                container.find("."+url).fadeIn();
                active.removeClass('active');
                a.parent("li").addClass('active');
            });
        }
    });
})(jQuery);

// Accordion
(function($) {
    $(document).on("click", ".acc-btn", function(e) {
        e.preventDefault();
        $this = $(this), parent = $this.parent(".acc");
        parent.toggleClass("active");
    })
})(jQuery);