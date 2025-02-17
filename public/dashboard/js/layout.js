! function() {
    "use strict";
    if (sessionStorage.getItem("defaultAttribute")) {
        for (var t = document.documentElement.attributes, e = {}, a = 0; a < t.length; a++) {
            var o = t[a];
            o.nodeName && "undefined" != o.nodeName && (e[o.nodeName] = o.nodeValue)
        }
        if (JSON.stringify(e) !== sessionStorage.getItem("defaultAttribute")) sessionStorage.clear(), location.reload();
        else {
            var s, d = {
                "data-layout": sessionStorage.getItem("data-layout"),
                "data-sidebar-size": sessionStorage.getItem("data-sidebar-size"),
                "data-bs-theme": sessionStorage.getItem("data-bs-theme"),
                "data-layout-width": sessionStorage.getItem("data-layout-width"),
                "data-sidebar": sessionStorage.getItem("data-sidebar"),
                "data-sidebar-image": sessionStorage.getItem("data-sidebar-image"),
                "data-layout-direction": sessionStorage.getItem("data-layout-direction"),
                "data-layout-position": sessionStorage.getItem("data-layout-position"),
                "data-layout-style": sessionStorage.getItem("data-layout-style"),
                "data-topbar": sessionStorage.getItem("data-topbar"),
                "data-preloader": sessionStorage.getItem("data-preloader")
            };
            for (s in d) d[s] && document.documentElement.setAttribute(s, d[s])
        }
    }
}();