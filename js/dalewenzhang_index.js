function dale6_addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            oldonload();
            func();
        }
    }
}

function ds_djs(e, time, t) {
    if (!e.dstHTML) e.dstHTML = e.innerHTML;
    if (time <= 0) {
        e.innerHTML = e.dstHTML;
        delete e.dstHTML;
        e.disabled = false;
        return;
    }
    time--;
    e.disabled = true;
    setTimeout(function() {
        ds_djs(e, time, t);
    }, 1000);
    e.innerHTML = e.dstHTML + (t ? '(' + time + ')' : '');
}


function ds_is_checked(radio_class) {
    let inputs = document.getElementsByName(radio_class);
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked) {
            return true;
        }
    }
    return false;
}

function ds_comp_usermail(id) {
    var reg = /^[0-9a-zA-Z_.-]+[@][0-9a-zA-Z_.-]+([.][a-zA-Z]+){1,2}$/;
    if (reg.test(document.getElementById(id).value.trim())) {
        return true;
    }
    return false;
}

function ds_post(data, fn) {
    var Http = createXHR();
    Http.open("POST", dalewenzhang_global['ajaxurl'], true);
    Http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    Http.onreadystatechange = () => {
        if (Http.readyState == 4) fn && fn(Http);
    };
    Http.send(ds_send_toString(data));
}

function createXHR() {
    var XHR = [
        function() {
            return new XMLHttpRequest();
        },
        function() {
            return new ActiveXObject("Msxml2.XMLHTTP");
        },
        function() {
            return new ActiveXObject("Msxml3.XMLHTTP");
        },
        function() {
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    ];
    var xhr = null;
    for (var i = 0; i < XHR.length; i++) {
        try {
            xhr = XHR[i]();
        } catch (e) {
            continue;
        }
        break;
    }
    return xhr;
}

function ds_send_toString(data) {
    var a = [];
    if (data.constructor == Array) {
        for (var i = 0; i < data.length; i++) {
            a.push(data[i].name + "=" + encodeURIComponent(data[i].value));
        }
    } else {
        for (var i in data) {
            a.push(i + "=" + encodeURIComponent(data[i]));
        }
    }
    return a.join("&");
}

dale6_addLoadEvent(function() {
    const colors = ["#778ca3", "#F57F17", "#5ec162", "#9575CD", "#999", "#00BCD4", "#c57c3b", "#6D4C41", "#5C6BC0", "#FBC02D", "#45aaf2", "#757575", "#EF5350", "#7986CB", "#2bcbba", "#37474F", "#546E7A", "#00838F", "#FFD54F", "#607D8B"];
    let colorIndex = 0;
    document.querySelectorAll('.dalewenzhang_user_ico').forEach(function(e) {
        let el = e.firstElementChild;
        let url = el.getAttribute('data-src');
        let alt = el.getAttribute('alt');
        let width = el.getAttribute('width');
        let height = el.getAttribute('height');
        e.style.width = width + "px";
        e.style.height = height + "px";
        el.remove();

        var img = new Image();
        img.src = url;
        img.onload = function() {
            let newimg = new Image();
            newimg.src = url;
            newimg.alt = alt;
            newimg.width = width;
            newimg.height = height;
            e.firstElementChild.remove();
            e.appendChild(newimg);
        };

        let div = document.createElement('div');
        div.style.backgroundColor = colors[colorIndex++ % colors.length];
        div.style.lineHeight = height + 'px';
        div.classList.add('dalewenzhang_user_ico_div');
        div.innerText = alt.substr(0, 1).toUpperCase();
        e.appendChild(div);
    });
});

function ds_login(e) {
    var useremail = document.getElementById('r_mail');
    if (!ds_comp_usermail("r_mail")) {
        useremail.focus();
        return false;
    }
    var codemm = document.getElementById('codemm');
    if (codemm.value.trim().replace(/(^s*)|(s*$)/g, "").length == 0) {
        codemm.focus();
        return false;
    }
    ds_djs(e, 3, 1);
    ds_post({
        'action': 'ajaxdenglu',
        'email': useremail.value,
        'code': codemm.value,
    }, function(a) {
        var b = JSON.parse(a.responseText);
        document.getElementById('userlogin').innerHTML = '<span style="color:' + (b.success ? 'green' : 'red') + ';">' + b.data.ms + '</span>';
        if (b.data.c == 300) window.location.href = b.data.d;
    });
}

function ds_send_mail_code(e) {
    var useremail = document.getElementById('r_mail');
    if (!ds_comp_usermail("r_mail")) {
        useremail.focus();
        return false;
    }
    ds_djs(e, 60, 1);
    ds_post({
        'action': 'sendmailcode',
        'email': useremail.value,
    }, function(a) {
        var b = JSON.parse(a.responseText);
        document.getElementById('userlogin').innerHTML = '<span style="color:' + (b.success ? 'green' : 'red') + ';">' + b.data + '</span>';
    });
}

dale6_addLoadEvent(function() {
    var links, i, len,
        menu = document.querySelector('.navbar-nav');
    if (!menu) {
        return false;
    }
    links = menu.getElementsByTagName('a');
    for (i = 0, len = links.length; i < len; i++) {
        links[i].addEventListener('focus', toggleFocus, true);
        links[i].addEventListener('blur', toggleFocus, true);
    }

    function toggleFocus() {
        var self = this;
        while (-1 === self.className.indexOf('navbar-nav')) {
            if ('li' === self.tagName.toLowerCase()) {
                if (-1 !== self.className.indexOf('focus')) {
                    self.className = self.className.replace(' focus', '');
                } else {
                    self.className += ' focus';
                }
            }
            self = self.parentElement;
        }
    }
});