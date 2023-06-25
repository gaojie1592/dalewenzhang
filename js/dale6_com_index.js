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
    Http.open("POST", dale6_com_global['ajaxurl'], true);
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
    var t = document.getElementById('dale6_com_ajaxpingluntop').value;
    document.querySelectorAll('.pinglun_up,.pinglun_down').forEach(function(e) {
        e.addEventListener("click", function(event) {
            ds_djs(this, 5, 0);
            var c = this.getAttribute('tid'),
                d = this.getAttribute('dale6_com_data'),
                e = this.getAttribute("ty");
            ds_post({
                'action': 'ajaxpingluntop',
                'key': t,
                'cid': c,
                'type': d,
                'ty': e
            }, function(a) {
                if (a.status == 200) {
                    var b = JSON.parse(a.responseText);
                    if (b.data.c == 200) {
                        document.getElementById(e + '_top_sum-' + b.data.data.cid).innerHTML = b.data.data.sum;
                    }
                    alert(b.data.ms);
                }
            });
        });
    });
});