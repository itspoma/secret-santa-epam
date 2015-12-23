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
    errorEl = document.getElementById('form-error')

    emailEl = document.getElementsByName('email')[0]
    email = emailEl.value

    isAgreementChecked = document.getElementsByName('agreement')[0].checked

    if !isAgreementChecked
        errorEl.innerHTML = 'Треба прийняти умови, щоб прийняти участь у грі!'
        return false

    if !email.length
        errorEl.innerHTML = 'Ви забули ввести емейл!'
        emailEl.focus()
        return false

    if !/^.+?@.+?\.\w{2,}$/.test(email)
        errorEl.innerHTML = 'Введіть коректний емейл!'
        emailEl.focus()
        return false

    if !/@epam\.com$/.test(email)
        errorEl.innerHTML = 'Гра тільки для працівників фірми EPAM!\nВведіть ваш @epam емейл.'
        emailEl.focus()
        return false

    formEl.classList.add('loading')

    ajax '/play', 'email='+email, (response) ->
        formEl.classList.remove('loading')

        if response == 'exists'
            errorEl.innerHTML = 'Цей мейл вже бере участь у грі.'
            emailEl.focus()
            return false

        formEl.classList.add('hidden')
        document.getElementById('form-initial-after').classList.remove('hidden')

    false