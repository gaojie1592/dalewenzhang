function dalewenzhang_addLoadEvent(func) {
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

function dalewenzhang_djs(e, time, t) {
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
        dalewenzhang_djs(e, time, t);
    }, 1000);
    e.innerHTML = e.dstHTML + (t ? '(' + time + ')' : '');
}

dalewenzhang_addLoadEvent(function() {
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

dalewenzhang_addLoadEvent(function() {
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

dalewenzhang_addLoadEvent(function() {
    var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasTop'));
    if (!myOffcanvas) return false;
    myOffcanvas._element.addEventListener('shown.bs.offcanvas', function() {
        var inputElement = document.getElementById("ssinput");
        inputElement.focus();
    });
});

dalewenzhang_addLoadEvent(function() {
    var textarea = document.getElementById('comment');
    if (!textarea) return false;
    textarea.addEventListener('keydown', function(event) {
        if (event.keyCode === 13 && !event.shiftKey) {
            event.preventDefault();
            document.getElementById('commentform').submit();
        }
        if (event.keyCode === 13 && event.shiftKey) {
            event.preventDefault();
            var startPos = textarea.selectionStart;
            var endPos = textarea.selectionEnd;
            textarea.value = textarea.value.substring(0, startPos) + '\n' + textarea.value.substring(endPos, textarea.value.length);
            textarea.selectionStart = startPos + 1;
            textarea.selectionEnd = startPos + 1;
        }
    });
});