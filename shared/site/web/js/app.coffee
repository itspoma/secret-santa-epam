window.ajax = (url, data, done) ->
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
            handleError?(xmlhttp.statusText)

    xmlhttp.send(data)