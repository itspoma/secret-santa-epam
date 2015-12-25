window.proceedRegistration = ->
    formEl = document.getElementById('form-initial')
    formErrorEl = document.getElementById('form-initial-errors')
    formErrorEl.innerHTML = ''
    
    emailEl = document.getElementsByName('email')[0]
    email = emailEl.value

    isAgreementChecked = document.getElementsByName('agreement')[0].checked

    if !isAgreementChecked
        formErrorEl.innerHTML = 'Треба прийняти умови, щоб прийняти участь у грі!'
        return false

    if !email.length
        formErrorEl.innerHTML = 'Ви забули ввести емейл!'
        emailEl.focus()
        return false

    if !/^.+?@.+?\.\w{2,}$/.test(email)
        formErrorEl.innerHTML = 'Введіть коректний емейл!'
        emailEl.focus()
        return false

    if !/@epam\.com$/.test(email)
        formErrorEl.innerHTML = 'Гра тільки для працівників фірми EPAM!\nВведіть ваш @epam емейл.'
        emailEl.focus()
        return false

    formEl.classList.add('loading')

    ajax '/play', 'email='+email, (response) ->
        jsonResponse = JSON.parse(response)

        formEl.classList.remove('loading')

        if jsonResponse.ok
            formEl.classList.add('hidden')
            document.getElementById('form-initial-after').classList.remove('hidden')

        else
            if jsonResponse.error == 'exists'
                error = 'Цей мейл вже бере участь у грі.'
            else
                error = 'Невідома помилка.'

            formErrorEl.innerHTML = error
            emailEl.focus()

        return false

    return false