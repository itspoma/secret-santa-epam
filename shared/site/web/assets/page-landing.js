var ajax = function (url, data, done) {
    var getXmlHttp = function () {
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (E) {
                xmlhttp = false;
            }

            if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
                xmlhttp = new XMLHttpRequest();
            }

            return xmlhttp;
        }
    }

    var xmlhttp = getXmlHttp();

    if (data) {
        xmlhttp.open('POST', url, true)
    }
    else {
        xmlhttp.open('POST', url, true)
    }

    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState != 4) {
            return;
        }

        if (xmlhttp.status == 200) {
            done(xmlhttp.responseText);
        }
        else {
            handleError(xmlhttp.statusText)
        }
    }

    xmlhttp.send(data);
}

window.proceed = function () {
    var emailEl = document.getElementsByName('email')[0];
    var email = emailEl.value;

    if (!email.length) {
        alert('Ви забули ввести емейл');
        emailEl.focus()
        return false;
    }

    if (!/^.+?@.+?\.\w{2,}$/.test(email)) {
        alert('Введіть коректний емейл');
        emailEl.focus()
        return false;
    }

    ajax('/play', 'email=' + email, function (data) {
        alert(data)
    });

    return false;
}