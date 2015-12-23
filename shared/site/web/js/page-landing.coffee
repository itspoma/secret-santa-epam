ajax = (url, data, done) ->
    getXmlHttp = ->
        try 
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP")
        catch e
            try
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
            catch E
                xmlhttp = false

            if !xmlhttp && typeof XMLHttpRequest!='undefined'
                xmlhttp = new XMLHttpRequest()

            return xmlhttp

    xmlhttp = getXmlHttp()

    if data
        xmlhttp.open('POST', url, true)
    else
        xmlhttp.open('POST', url, true)

    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    xmlhttp.onreadystatechange = ->
        return if xmlhttp.readyState != 4

        if xmlhttp.status == 200
            done?(xmlhttp.responseText)
        else
            handleError(xmlhttp.statusText)

    xmlhttp.send(data)

window.proceed = ->
    formEl = document.getElementById('form-initial')

    emailEl = document.getElementsByName('email')[0]
    email = emailEl.value

    isAgreementChecked = document.getElementsByName('agreement')[0].checked

    if !isAgreementChecked
        alert 'Треба прийняти умови, щоб продовжити'
        return false

    if !email.length
        alert 'Ви забули ввести емейл'
        emailEl.focus()
        return false

    if !/^.+?@.+?\.\w{2,}$/.test(email)
        alert 'Введіть коректний емейл'
        emailEl.focus()
        return false

    formEl.classList.add('loading')

    ajax '/play', 'email='+email, (data) ->
        formEl.classList.remove('loading')

        formEl.classList.add('hidden')
        document.getElementById('form-initial-after').classList.remove('hidden')

        alert('Ура! Ви зареєстровані!')

    false