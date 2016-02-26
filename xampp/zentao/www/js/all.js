/*!
 * jQuery JavaScript Library v1.5.2
 * http://jquery.com/
 *
 * Copyright 2011, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 * Copyright 2011, The Dojo Foundation
 * Released under the MIT, BSD, and GPL Licenses.
 *
 * Date: Thu Mar 31 15:28:23 2011 -0400
 */
(function (aR, aP) {
    function af(b) {
        return aL.isWindow(b) ? b : b.nodeType === 9 ? b.defaultView || b.parentWindow : !1
    }

    function al(e) {
        if (!cn[e]) {
            var d = aL("<" + e + ">").appendTo("body"), f = d.css("display");
            d.remove();
            if (f === "none" || f === "") {
                f = "block"
            }
            cn[e] = f
        }
        return cn[e]
    }

    function an(e, d) {
        var f = {};
        aL.each(ao.concat.apply([], ao.slice(0, d)), function () {
            f[this] = e
        });
        return f
    }

    function c2() {
        try {
            return new aR.ActiveXObject("Microsoft.XMLHTTP")
        } catch (a) {
        }
    }

    function cu() {
        try {
            return new aR.XMLHttpRequest
        } catch (a) {
        }
    }

    function cw() {
        aL(aR).unload(function () {
            for (var b in cz) {
                cz[b](0, 1)
            }
        })
    }

    function cI(B, A) {
        B.dataFilter && (A = B.dataFilter(A, B.dataType));
        var z = B.dataTypes, y = {}, x, w, v = z.length, u, t = z[0], s, r, q, d, b;
        for (x = 1; x < v; x++) {
            if (x === 1) {
                for (w in B.converters) {
                    typeof w === "string" && (y[w.toLowerCase()] = B.converters[w])
                }
            }
            s = t, t = z[x];
            if (t === "*") {
                t = s
            } else {
                if (s !== "*" && s !== t) {
                    r = s + " " + t, q = y[r] || y["* " + t];
                    if (!q) {
                        b = aP;
                        for (d in y) {
                            u = d.split(" ");
                            if (u[0] === s || u[0] === "*") {
                                b = y[u[1] + " " + t];
                                if (b) {
                                    d = y[d], d === !0 ? q = b : b === !0 && (q = d);
                                    break
                                }
                            }
                        }
                    }
                    !q && !b && aL.error("No conversion from " + r.replace(" ", " to ")), q !== !0 && (A = q ? q(A) : b(d(A)))
                }
            }
        }
        return A
    }

    function cJ(t, s, r) {
        var q = t.contents, p = t.dataTypes, o = t.responseFields, n, m, l, b;
        for (m in o) {
            m in r && (s[o[m]] = r[m])
        }
        while (p[0] === "*") {
            p.shift(), n === aP && (n = t.mimeType || s.getResponseHeader("content-type"))
        }
        if (n) {
            for (m in q) {
                if (q[m] && q[m].test(n)) {
                    p.unshift(m);
                    break
                }
            }
        }
        if (p[0] in r) {
            l = p[0]
        } else {
            for (m in r) {
                if (!p[0] || t.converters[m + " " + p[0]]) {
                    l = m;
                    break
                }
                b || (b = m)
            }
            l = l || b
        }
        if (l) {
            l !== p[0] && p.unshift(l);
            return r[l]
        }
    }

    function cK(g, d, k, j) {
        if (aL.isArray(d) && d.length) {
            aL.each(d, function (a, c) {
                k || aQ.test(g) ? j(g, c) : cK(g + "[" + (typeof c === "object" || aL.isArray(c) ? a : "") + "]", c, k, j)
            })
        } else {
            if (k || d == null || typeof d !== "object") {
                j(g, d)
            } else {
                if (aL.isArray(d) || aL.isEmptyObject(d)) {
                    j(g, "")
                } else {
                    for (var h in d) {
                        cK(g + "[" + h + "]", d[h], k, j)
                    }
                }
            }
        }
    }

    function cL(v, u, t, s, r, q) {
        r = r || u.dataTypes[0], q = q || {}, q[r] = !0;
        var p = v[r], o = 0, n = p ? p.length : 0, m = v === cR, b;
        for (; o < n && (m || !b); o++) {
            b = p[o](u, t, s), typeof b === "string" && (!m || q[b] ? b = aP : (u.dataTypes.unshift(b), b = cL(v, u, t, s, b, q)))
        }
        (m || !b) && !q["*"] && (b = cL(v, u, t, s, "*", q));
        return b
    }

    function cM(b) {
        return function (a, p) {
            typeof a !== "string" && (p = a, a = "*");
            if (aL.isFunction(p)) {
                var o = a.toLowerCase().split(cX), n = 0, m = o.length, l, k, d;
                for (; n < m; n++) {
                    l = o[n], d = /^\+/.test(l), d && (l = l.substr(1) || "*"), k = b[l] = b[l] || [], k[d ? "unshift" : "push"](p)
                }
            }
        }
    }

    function aU(g, d, k) {
        var j = d === "width" ? a1 : aZ, h = d === "width" ? g.offsetWidth : g.offsetHeight;
        if (k === "border") {
            return h
        }
        aL.each(j, function () {
            k || (h -= parseFloat(aL.css(g, "padding" + this)) || 0), k === "margin" ? h += parseFloat(aL.css(g, "margin" + this)) || 0 : h -= parseFloat(aL.css(g, "border" + this + "Width")) || 0
        });
        return h
    }

    function b7(d, c) {
        c.src ? aL.ajax({url: c.src, async: !1, dataType: "script"}) : aL.globalEval(c.text || c.textContent || c.innerHTML || ""), c.parentNode && c.parentNode.removeChild(c)
    }

    function b9(b) {
        return"getElementsByTagName" in b ? b.getElementsByTagName("*") : "querySelectorAll" in b ? b.querySelectorAll("*") : []
    }

    function ck(e, d) {
        if (d.nodeType === 1) {
            var f = d.nodeName.toLowerCase();
            d.clearAttributes(), d.mergeAttributes(e);
            if (f === "object") {
                d.outerHTML = e.outerHTML
            } else {
                if (f !== "input" || e.type !== "checkbox" && e.type !== "radio") {
                    if (f === "option") {
                        d.selected = e.defaultSelected
                    } else {
                        if (f === "input" || f === "textarea") {
                            d.defaultValue = e.defaultValue
                        }
                    }
                } else {
                    e.checked && (d.defaultChecked = d.checked = e.checked), d.value !== e.value && (d.value = e.value)
                }
            }
            d.removeAttribute(aL.expando)
        }
    }

    function aT(r, q) {
        if (q.nodeType === 1 && aL.hasData(r)) {
            var p = aL.expando, o = aL.data(r), n = aL.data(q, o);
            if (o = o[p]) {
                var m = o.events;
                n = n[p] = aL.extend({}, o);
                if (m) {
                    delete n.handle, n.events = {};
                    for (var l in m) {
                        for (var k = 0, d = m[l].length; k < d; k++) {
                            aL.event.add(q, l + (m[l][k].namespace ? "." : "") + m[l][k].namespace, m[l][k], m[l][k].data)
                        }
                    }
                }
            }
        }
    }

    function c1(d, c) {
        return aL.nodeName(d, "table") ? d.getElementsByTagName("tbody")[0] || d.appendChild(d.ownerDocument.createElement("tbody")) : d
    }

    function b8(f, d, h) {
        if (aL.isFunction(d)) {
            return aL.grep(f, function (b, j) {
                var c = !!d.call(b, j, b);
                return c === h
            })
        }
        if (d.nodeType) {
            return aL.grep(f, function (b, c) {
                return b === d === h
            })
        }
        if (typeof d === "string") {
            var g = aL.grep(f, function (b) {
                return b.nodeType === 1
            });
            if (cp.test(d)) {
                return aL.filter(d, g, !h)
            }
            d = aL.filter(d, g)
        }
        return aL.grep(f, function (b, c) {
            return aL.inArray(b, d) >= 0 === h
        })
    }

    function cj(b) {
        return !b || !b.parentNode || b.parentNode.nodeType === 11
    }

    function ct(d, c) {
        return(d && d !== "*" ? d + "." : "") + c.replace(aj, "`").replace(ah, "&")
    }

    function cv(J) {
        var I, H, G, F, E, D, C, B, A, z, y, x, w, v = [], u = [], r = aL._data(this, "events");
        if (J.liveFired !== this && r && r.live && !J.target.disabled && (!J.button || J.type !== "click")) {
            J.namespace && (x = new RegExp("(^|\\.)" + J.namespace.split(".").join("\\.(?:.*\\.)?") + "(\\.|$)")), J.liveFired = this;
            var d = r.live.slice(0);
            for (C = 0; C < d.length; C++) {
                E = d[C], E.origType.replace(am, "") === J.type ? u.push(E.selector) : d.splice(C--, 1)
            }
            F = aL(J.target).closest(u, J.currentTarget);
            for (B = 0, A = F.length; B < A; B++) {
                y = F[B];
                for (C = 0; C < d.length; C++) {
                    E = d[C];
                    if (y.selector === E.selector && (!x || x.test(E.namespace)) && !y.elem.disabled) {
                        D = y.elem, G = null;
                        if (E.preType === "mouseenter" || E.preType === "mouseleave") {
                            J.type = E.preType, G = aL(J.relatedTarget).closest(E.selector)[0]
                        }
                        (!G || G !== D) && v.push({elem: D, handleObj: E, level: y.level})
                    }
                }
            }
            for (B = 0, A = v.length; B < A; B++) {
                F = v[B];
                if (H && F.level > H) {
                    break
                }
                J.currentTarget = F.elem, J.data = F.handleObj.data, J.handleObj = F.handleObj, w = F.handleObj.origHandler.apply(F.elem, arguments);
                if (w === !1 || J.isPropagationStopped()) {
                    H = F.level, w === !1 && (I = !1);
                    if (J.isImmediatePropagationStopped()) {
                        break
                    }
                }
            }
            return I
        }
    }

    function cA(b, h, g) {
        var d = aL.extend({}, g[0]);
        d.type = b, d.originalEvent = {}, d.liveFired = aP, aL.event.handle.call(h, d), d.isDefaultPrevented() && g[0].preventDefault()
    }

    function ab() {
        return !0
    }

    function ac() {
        return !1
    }

    function aB(d) {
        for (var c in d) {
            if (c !== "toJSON") {
                return !1
            }
        }
        return !0
    }

    function aD(b, h, g) {
        if (g === aP && b.nodeType === 1) {
            g = b.getAttribute("data-" + h);
            if (typeof g === "string") {
                try {
                    g = g === "true" ? !0 : g === "false" ? !1 : g === "null" ? null : aL.isNaN(g) ? aF.test(g) ? aL.parseJSON(g) : g : parseFloat(g)
                } catch (d) {
                }
                aL.data(b, h, g)
            } else {
                g = aP
            }
        }
        return g
    }

    var aN = aR.document, aL = function () {
        function J() {
            if (!bh.isReady) {
                try {
                    aN.documentElement.doScroll("left")
                } catch (d) {
                    setTimeout(J, 1);
                    return
                }
                bh.ready()
            }
        }

        var bh = function (e, d) {
            return new bh.fn.init(e, d, be)
        }, bg = aR.jQuery, bf = aR.$, be, bd = /^(?:[^<]*(<[\w\W]+>)[^>]*$|#([\w\-]+)$)/, bc = /\S/, bb = /^\s+/, ba = /\s+$/, Z = /\d/, Y = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, X = /^[\],:{}\s]*$/, W = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, V = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, T = /(?:^|:|,)(?:\s*\[)+/g, R = /(webkit)[ \/]([\w.]+)/, P = /(opera)(?:.*version)?[ \/]([\w.]+)/, N = /(msie) ([\w.]+)/, L = /(mozilla)(?:.*? rv:([\w.]+))?/, I = navigator.userAgent, H, c, b, a = Object.prototype.toString, U = Object.prototype.hasOwnProperty, S = Array.prototype.push, Q = Array.prototype.slice, O = String.prototype.trim, M = Array.prototype.indexOf, K = {};
        bh.fn = bh.prototype = {constructor: bh, init: function (d, p, o) {
            var n, m, l, h;
            if (!d) {
                return this
            }
            if (d.nodeType) {
                this.context = this[0] = d, this.length = 1;
                return this
            }
            if (d === "body" && !p && aN.body) {
                this.context = aN, this[0] = aN.body, this.selector = "body", this.length = 1;
                return this
            }
            if (typeof d === "string") {
                n = bd.exec(d);
                if (!n || !n[1] && p) {
                    return !p || p.jquery ? (p || o).find(d) : this.constructor(p).find(d)
                }
                if (n[1]) {
                    p = p instanceof bh ? p[0] : p, h = p ? p.ownerDocument || p : aN, l = Y.exec(d), l ? bh.isPlainObject(p) ? (d = [aN.createElement(l[1])], bh.fn.attr.call(d, p, !0)) : d = [h.createElement(l[1])] : (l = bh.buildFragment([n[1]], [h]), d = (l.cacheable ? bh.clone(l.fragment) : l.fragment).childNodes);
                    return bh.merge(this, d)
                }
                m = aN.getElementById(n[2]);
                if (m && m.parentNode) {
                    if (m.id !== n[2]) {
                        return o.find(d)
                    }
                    this.length = 1, this[0] = m
                }
                this.context = aN, this.selector = d;
                return this
            }
            if (bh.isFunction(d)) {
                return o.ready(d)
            }
            d.selector !== aP && (this.selector = d.selector, this.context = d.context);
            return bh.makeArray(d, this)
        }, selector: "", jquery: "1.5.2", length: 0, size: function () {
            return this.length
        }, toArray: function () {
            return Q.call(this, 0)
        }, get: function (d) {
            return d == null ? this.toArray() : d < 0 ? this[this.length + d] : this[d]
        }, pushStack: function (f, d, h) {
            var g = this.constructor();
            bh.isArray(f) ? S.apply(g, f) : bh.merge(g, f), g.prevObject = this, g.context = this.context, d === "find" ? g.selector = this.selector + (this.selector ? " " : "") + h : d && (g.selector = this.selector + "." + d + "(" + h + ")");
            return g
        }, each: function (e, d) {
            return bh.each(this, e, d)
        }, ready: function (d) {
            bh.bindReady(), c.done(d);
            return this
        }, eq: function (d) {
            return d === -1 ? this.slice(d) : this.slice(d, +d + 1)
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, slice: function () {
            return this.pushStack(Q.apply(this, arguments), "slice", Q.call(arguments).join(","))
        }, map: function (d) {
            return this.pushStack(bh.map(this, function (e, f) {
                return d.call(e, f, e)
            }))
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: S, sort: [].sort, splice: [].splice}, bh.fn.init.prototype = bh.fn, bh.extend = bh.fn.extend = function () {
            var u, t, s, r, q, p, o = arguments[0] || {}, n = 1, m = arguments.length, d = !1;
            typeof o === "boolean" && (d = o, o = arguments[1] || {}, n = 2), typeof o !== "object" && !bh.isFunction(o) && (o = {}), m === n && (o = this, --n);
            for (; n < m; n++) {
                if ((u = arguments[n]) != null) {
                    for (t in u) {
                        s = o[t], r = u[t];
                        if (o === r) {
                            continue
                        }
                        d && r && (bh.isPlainObject(r) || (q = bh.isArray(r))) ? (q ? (q = !1, p = s && bh.isArray(s) ? s : []) : p = s && bh.isPlainObject(s) ? s : {}, o[t] = bh.extend(d, p, r)) : r !== aP && (o[t] = r)
                    }
                }
            }
            return o
        }, bh.extend({noConflict: function (d) {
            aR.$ = bf, d && (aR.jQuery = bg);
            return bh
        }, isReady: !1, readyWait: 1, ready: function (d) {
            d === !0 && bh.readyWait--;
            if (!bh.readyWait || d !== !0 && !bh.isReady) {
                if (!aN.body) {
                    return setTimeout(bh.ready, 1)
                }
                bh.isReady = !0;
                if (d !== !0 && --bh.readyWait > 0) {
                    return
                }
                c.resolveWith(aN, [bh]), bh.fn.trigger && bh(aN).trigger("ready").unbind("ready")
            }
        }, bindReady: function () {
            if (!c) {
                c = bh._Deferred();
                if (aN.readyState === "complete") {
                    return setTimeout(bh.ready, 1)
                }
                if (aN.addEventListener) {
                    aN.addEventListener("DOMContentLoaded", b, !1), aR.addEventListener("load", bh.ready, !1)
                } else {
                    if (aN.attachEvent) {
                        aN.attachEvent("onreadystatechange", b), aR.attachEvent("onload", bh.ready);
                        var d = !1;
                        try {
                            d = aR.frameElement == null
                        } catch (f) {
                        }
                        aN.documentElement.doScroll && d && J()
                    }
                }
            }
        }, isFunction: function (d) {
            return bh.type(d) === "function"
        }, isArray: Array.isArray || function (d) {
            return bh.type(d) === "array"
        }, isWindow: function (d) {
            return d && typeof d === "object" && "setInterval" in d
        }, isNaN: function (d) {
            return d == null || !Z.test(d) || isNaN(d)
        }, type: function (d) {
            return d == null ? String(d) : K[a.call(d)] || "object"
        }, isPlainObject: function (d) {
            if (!d || bh.type(d) !== "object" || d.nodeType || bh.isWindow(d)) {
                return !1
            }
            if (d.constructor && !U.call(d, "constructor") && !U.call(d.constructor.prototype, "isPrototypeOf")) {
                return !1
            }
            var e;
            for (e in d) {
            }
            return e === aP || U.call(d, e)
        }, isEmptyObject: function (e) {
            for (var d in e) {
                return !1
            }
            return !0
        }, error: function (d) {
            throw d
        }, parseJSON: function (d) {
            if (typeof d !== "string" || !d) {
                return null
            }
            d = bh.trim(d);
            if (X.test(d.replace(W, "@").replace(V, "]").replace(T, ""))) {
                return aR.JSON && aR.JSON.parse ? aR.JSON.parse(d) : (new Function("return " + d))()
            }
            bh.error("Invalid JSON: " + d)
        }, parseXML: function (d, g, f) {
            aR.DOMParser ? (f = new DOMParser, g = f.parseFromString(d, "text/xml")) : (g = new ActiveXObject("Microsoft.XMLDOM"), g.async = "false", g.loadXML(d)), f = g.documentElement, (!f || !f.nodeName || f.nodeName === "parsererror") && bh.error("Invalid XML: " + d);
            return g
        }, noop: function () {
        }, globalEval: function (f) {
            if (f && bc.test(f)) {
                var d = aN.head || aN.getElementsByTagName("head")[0] || aN.documentElement, g = aN.createElement("script");
                bh.support.scriptEval() ? g.appendChild(aN.createTextNode(f)) : g.text = f, d.insertBefore(g, d.firstChild), d.removeChild(g)
            }
        }, nodeName: function (e, d) {
            return e.nodeName && e.nodeName.toUpperCase() === d.toUpperCase()
        }, each: function (d, q, p) {
            var o, n = 0, m = d.length, l = m === aP || bh.isFunction(d);
            if (p) {
                if (l) {
                    for (o in d) {
                        if (q.apply(d[o], p) === !1) {
                            break
                        }
                    }
                } else {
                    for (; n < m;) {
                        if (q.apply(d[n++], p) === !1) {
                            break
                        }
                    }
                }
            } else {
                if (l) {
                    for (o in d) {
                        if (q.call(d[o], o, d[o]) === !1) {
                            break
                        }
                    }
                } else {
                    for (var k = d[0]; n < m && q.call(k, n, k) !== !1; k = d[++n]) {
                    }
                }
            }
            return d
        }, trim: O ? function (d) {
            return d == null ? "" : O.call(d)
        } : function (d) {
            return d == null ? "" : (d + "").replace(bb, "").replace(ba, "")
        }, makeArray: function (f, d) {
            var h = d || [];
            if (f != null) {
                var g = bh.type(f);
                f.length == null || g === "string" || g === "function" || g === "regexp" || bh.isWindow(f) ? S.call(h, f) : bh.merge(h, f)
            }
            return h
        }, inArray: function (f, e) {
            if (e.indexOf) {
                return e.indexOf(f)
            }
            for (var h = 0, g = e.length; h < g; h++) {
                if (e[h] === f) {
                    return h
                }
            }
            return -1
        }, merge: function (g, l) {
            var k = g.length, j = 0;
            if (typeof l.length === "number") {
                for (var h = l.length; j < h; j++) {
                    g[k++] = l[j]
                }
            } else {
                while (l[j] !== aP) {
                    g[k++] = l[j++]
                }
            }
            g.length = k;
            return g
        }, grep: function (j, h, o) {
            var n = [], m;
            o = !!o;
            for (var l = 0, k = j.length; l < k; l++) {
                m = !!h(j[l], l), o !== m && n.push(j[l])
            }
            return n
        }, map: function (j, h, o) {
            var n = [], m;
            for (var l = 0, k = j.length; l < k; l++) {
                m = h(j[l], l, o), m != null && (n[n.length] = m)
            }
            return n.concat.apply([], n)
        }, guid: 1, proxy: function (d, g, f) {
            arguments.length === 2 && (typeof g === "string" ? (f = d, d = f[g], g = aP) : g && !bh.isFunction(g) && (f = g, g = aP)), !g && d && (g = function () {
                return d.apply(f || this, arguments)
            }), d && (g.guid = d.guid = d.guid || g.guid || bh.guid++);
            return g
        }, access: function (s, r, q, p, o, n) {
            var m = s.length;
            if (typeof r === "object") {
                for (var l in r) {
                    bh.access(s, l, r[l], p, o, q)
                }
                return s
            }
            if (q !== aP) {
                p = !n && p && bh.isFunction(q);
                for (var d = 0; d < m; d++) {
                    o(s[d], r, p ? q.call(s[d], d, o(s[d], r)) : q, n)
                }
                return s
            }
            return m ? o(s[0], r) : aP
        }, now: function () {
            return(new Date).getTime()
        }, uaMatch: function (e) {
            e = e.toLowerCase();
            var d = R.exec(e) || P.exec(e) || N.exec(e) || e.indexOf("compatible") < 0 && L.exec(e) || [];
            return{browser: d[1] || "", version: d[2] || "0"}
        }, sub: function () {
            function f(e, h) {
                return new f.fn.init(e, h)
            }

            bh.extend(!0, f, this), f.superclass = this, f.fn = f.prototype = this(), f.fn.constructor = f, f.subclass = this.subclass, f.fn.init = function d(e, h) {
                h && h instanceof bh && !(h instanceof f) && (h = f(h));
                return bh.fn.init.call(this, e, h, g)
            }, f.fn.init.prototype = f.fn;
            var g = f(aN);
            return f
        }, browser: {}}), bh.each("Boolean Number String Function Array Date RegExp Object".split(" "), function (e, d) {
            K["[object " + d + "]"] = d.toLowerCase()
        }), H = bh.uaMatch(I), H.browser && (bh.browser[H.browser] = !0, bh.browser.version = H.version), bh.browser.webkit && (bh.browser.safari = !0), M && (bh.inArray = function (e, d) {
            return M.call(d, e)
        }), bc.test(" ") && (bb = /^[\s\xA0]+/, ba = /[\s\xA0]+$/), be = bh(aN), aN.addEventListener ? b = function () {
            aN.removeEventListener("DOMContentLoaded", b, !1), bh.ready()
        } : aN.attachEvent && (b = function () {
            aN.readyState === "complete" && (aN.detachEvent("onreadystatechange", b), bh.ready())
        });
        return bh
    }(), aJ = "then done fail isResolved isRejected promise".split(" "), aH = [].slice;
    aL.extend({_Deferred: function () {
        var g = [], d, k, j, h = {done: function () {
            if (!j) {
                var m = arguments, l, f, e, b, a;
                d && (a = d, d = 0);
                for (l = 0, f = m.length; l < f; l++) {
                    e = m[l], b = aL.type(e), b === "array" ? h.done.apply(h, e) : b === "function" && g.push(e)
                }
                a && h.resolveWith(a[0], a[1])
            }
            return this
        }, resolveWith: function (b, a) {
            if (!j && !d && !k) {
                a = a || [], k = 1;
                try {
                    while (g[0]) {
                        g.shift().apply(b, a)
                    }
                } finally {
                    d = [b, a], k = 0
                }
            }
            return this
        }, resolve: function () {
            h.resolveWith(this, arguments);
            return this
        }, isResolved: function () {
            return k || d
        }, cancel: function () {
            j = 1, g = [];
            return this
        }};
        return h
    }, Deferred: function (e) {
        var d = aL._Deferred(), h = aL._Deferred(), g;
        aL.extend(d, {then: function (b, f) {
            d.done(b).fail(f);
            return this
        }, fail: h.done, rejectWith: h.resolveWith, reject: h.resolve, isRejected: h.isResolved, promise: function (b) {
            if (b == null) {
                if (g) {
                    return g
                }
                g = b = {}
            }
            var f = aJ.length;
            while (f--) {
                b[aJ[f]] = d[aJ[f]]
            }
            return b
        }}), d.done(h.cancel).fail(d.cancel), delete d.cancel, e && e.call(d, d);
        return d
    }, when: function (f) {
        function j(b) {
            return function (a) {
                d[b] = arguments.length > 1 ? aH.call(arguments, 0) : a, --l || k.resolveWith(k, aH.call(d, 0))
            }
        }

        var d = arguments, n = 0, m = d.length, l = m, k = m <= 1 && f && aL.isFunction(f.promise) ? f : aL.Deferred();
        if (m > 1) {
            for (; n < m; n++) {
                d[n] && aL.isFunction(d[n].promise) ? d[n].promise().then(j(n), k.reject) : --l
            }
            l || k.resolveWith(k, d)
        } else {
            k !== f && k.resolveWith(k, m ? [f] : [])
        }
        return k.promise()
    }}), function () {
        aL.support = {};
        var v = aN.createElement("div");
        v.style.display = "none", v.innerHTML = "   <link/><table></table><a href='/a' style='color:red;float:left;opacity:.55;'>a</a><input type='checkbox'/>";
        var u = v.getElementsByTagName("*"), t = v.getElementsByTagName("a")[0], s = aN.createElement("select"), r = s.appendChild(aN.createElement("option")), q = v.getElementsByTagName("input")[0];
        if (u && u.length && t) {
            aL.support = {leadingWhitespace: v.firstChild.nodeType === 3, tbody: !v.getElementsByTagName("tbody").length, htmlSerialize: !!v.getElementsByTagName("link").length, style: /red/.test(t.getAttribute("style")), hrefNormalized: t.getAttribute("href") === "/a", opacity: /^0.55$/.test(t.style.opacity), cssFloat: !!t.style.cssFloat, checkOn: q.value === "on", optSelected: r.selected, deleteExpando: !0, optDisabled: !1, checkClone: !1, noCloneEvent: !0, noCloneChecked: !0, boxModel: null, inlineBlockNeedsLayout: !1, shrinkWrapBlocks: !1, reliableHiddenOffsets: !0, reliableMarginRight: !0}, q.checked = !0, aL.support.noCloneChecked = q.cloneNode(!0).checked, s.disabled = !0, aL.support.optDisabled = !r.disabled;
            var p = null;
            aL.support.scriptEval = function () {
                if (p === null) {
                    var h = aN.documentElement, l = aN.createElement("script"), k = "script" + aL.now();
                    try {
                        l.appendChild(aN.createTextNode("window." + k + "=1;"))
                    } catch (j) {
                    }
                    h.insertBefore(l, h.firstChild), aR[k] ? (p = !0, delete aR[k]) : p = !1, h.removeChild(l)
                }
                return p
            };
            try {
                delete v.test
            } catch (o) {
                aL.support.deleteExpando = !1
            }
            !v.addEventListener && v.attachEvent && v.fireEvent && (v.attachEvent("onclick", function d() {
                aL.support.noCloneEvent = !1, v.detachEvent("onclick", d)
            }), v.cloneNode(!0).fireEvent("onclick")), v = aN.createElement("div"), v.innerHTML = "<input type='radio' name='radiotest' checked='checked'/>";
            var c = aN.createDocumentFragment();
            c.appendChild(v.firstChild), aL.support.checkClone = c.cloneNode(!0).cloneNode(!0).lastChild.checked, aL(function () {
                var g = aN.createElement("div"), f = aN.getElementsByTagName("body")[0];
                if (f) {
                    g.style.width = g.style.paddingLeft = "1px", f.appendChild(g), aL.boxModel = aL.support.boxModel = g.offsetWidth === 2, "zoom" in g.style && (g.style.display = "inline", g.style.zoom = 1, aL.support.inlineBlockNeedsLayout = g.offsetWidth === 2, g.style.display = "", g.innerHTML = "<div style='width:4px;'></div>", aL.support.shrinkWrapBlocks = g.offsetWidth !== 2), g.innerHTML = "<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>";
                    var h = g.getElementsByTagName("td");
                    aL.support.reliableHiddenOffsets = h[0].offsetHeight === 0, h[0].style.display = "", h[1].style.display = "none", aL.support.reliableHiddenOffsets = aL.support.reliableHiddenOffsets && h[0].offsetHeight === 0, g.innerHTML = "", aN.defaultView && aN.defaultView.getComputedStyle && (g.style.width = "1px", g.style.marginRight = "0", aL.support.reliableMarginRight = (parseInt(aN.defaultView.getComputedStyle(g, null).marginRight, 10) || 0) === 0), f.removeChild(g).style.display = "none", g = h = null
                }
            });
            var a = function (f) {
                var e = aN.createElement("div");
                f = "on" + f;
                if (!e.attachEvent) {
                    return !0
                }
                var g = f in e;
                g || (e.setAttribute(f, "return;"), g = typeof e[f] === "function");
                return g
            };
            aL.support.submitBubbles = a("submit"), aL.support.changeBubbles = a("change"), v = u = t = null
        }
    }();
    var aF = /^(?:\{.*\}|\[.*\])$/;
    aL.extend({cache: {}, uuid: 0, expando: "jQuery" + (aL.fn.jquery + Math.random()).replace(/\D/g, ""), noData: {embed: !0, object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000", applet: !0}, hasData: function (b) {
        b = b.nodeType ? aL.cache[b[aL.expando]] : b[aL.expando];
        return !!b && !aB(b)
    }, data: function (t, s, r, q) {
        if (aL.acceptData(t)) {
            var p = aL.expando, o = typeof s === "string", n, m = t.nodeType, d = m ? aL.cache : t, b = m ? t[aL.expando] : t[aL.expando] && aL.expando;
            if ((!b || q && b && !d[b][p]) && o && r === aP) {
                return
            }
            b || (m ? t[aL.expando] = b = ++aL.uuid : b = aL.expando), d[b] || (d[b] = {}, m || (d[b].toJSON = aL.noop));
            if (typeof s === "object" || typeof s === "function") {
                q ? d[b][p] = aL.extend(d[b][p], s) : d[b] = aL.extend(d[b], s)
            }
            n = d[b], q && (n[p] || (n[p] = {}), n = n[p]), r !== aP && (n[s] = r);
            if (s === "events" && !n[s]) {
                return n[p] && n[p].events
            }
            return o ? n[s] : n
        }
    }, removeData: function (s, r, q) {
        if (aL.acceptData(s)) {
            var p = aL.expando, o = s.nodeType, n = o ? aL.cache : s, m = o ? s[aL.expando] : aL.expando;
            if (!n[m]) {
                return
            }
            if (r) {
                var d = q ? n[m][p] : n[m];
                if (d) {
                    delete d[r];
                    if (!aB(d)) {
                        return
                    }
                }
            }
            if (q) {
                delete n[m][p];
                if (!aB(n[m])) {
                    return
                }
            }
            var a = n[m][p];
            aL.support.deleteExpando || n != aR ? delete n[m] : n[m] = null, a ? (n[m] = {}, o || (n[m].toJSON = aL.noop), n[m][p] = a) : o && (aL.support.deleteExpando ? delete s[aL.expando] : s.removeAttribute ? s.removeAttribute(aL.expando) : s[aL.expando] = null)
        }
    }, _data: function (e, d, f) {
        return aL.data(e, d, f, !0)
    }, acceptData: function (d) {
        if (d.nodeName) {
            var c = aL.noData[d.nodeName.toLowerCase()];
            if (c) {
                return c !== !0 && d.getAttribute("classid") === c
            }
        }
        return !0
    }}), aL.fn.extend({data: function (b, p) {
        var o = null;
        if (typeof b === "undefined") {
            if (this.length) {
                o = aL.data(this[0]);
                if (this[0].nodeType === 1) {
                    var n = this[0].attributes, m;
                    for (var l = 0, h = n.length; l < h; l++) {
                        m = n[l].name, m.indexOf("data-") === 0 && (m = m.substr(5), aD(this[0], m, o[m]))
                    }
                }
            }
            return o
        }
        if (typeof b === "object") {
            return this.each(function () {
                aL.data(this, b)
            })
        }
        var d = b.split(".");
        d[1] = d[1] ? "." + d[1] : "";
        if (p === aP) {
            o = this.triggerHandler("getData" + d[1] + "!", [d[0]]), o === aP && this.length && (o = aL.data(this[0], b), o = aD(this[0], b, o));
            return o === aP && d[1] ? this.data(d[0]) : o
        }
        return this.each(function () {
            var a = aL(this), c = [d[0], p];
            a.triggerHandler("setData" + d[1] + "!", c), aL.data(this, b, p), a.triggerHandler("changeData" + d[1] + "!", c)
        })
    }, removeData: function (b) {
        return this.each(function () {
            aL.removeData(this, b)
        })
    }}), aL.extend({queue: function (f, d, h) {
        if (f) {
            d = (d || "fx") + "queue";
            var g = aL._data(f, d);
            if (!h) {
                return g || []
            }
            !g || aL.isArray(h) ? g = aL._data(f, d, aL.makeArray(h)) : g.push(h);
            return g
        }
    }, dequeue: function (f, d) {
        d = d || "fx";
        var h = aL.queue(f, d), g = h.shift();
        g === "inprogress" && (g = h.shift()), g && (d === "fx" && h.unshift("inprogress"), g.call(f, function () {
            aL.dequeue(f, d)
        })), h.length || aL.removeData(f, d + "queue", !0)
    }}), aL.fn.extend({queue: function (b, d) {
        typeof b !== "string" && (d = b, b = "fx");
        if (d === aP) {
            return aL.queue(this[0], b)
        }
        return this.each(function (a) {
            var c = aL.queue(this, b, d);
            b === "fx" && c[0] !== "inprogress" && aL.dequeue(this, b)
        })
    }, dequeue: function (b) {
        return this.each(function () {
            aL.dequeue(this, b)
        })
    }, delay: function (d, c) {
        d = aL.fx ? aL.fx.speeds[d] || d : d, c = c || "fx";
        return this.queue(c, function () {
            var a = this;
            setTimeout(function () {
                aL.dequeue(a, c)
            }, d)
        })
    }, clearQueue: function (b) {
        return this.queue(b || "fx", [])
    }});
    var aA = /[\n\t\r]/g, az = /\s+/, ay = /\r/g, ax = /^(?:href|src|style)$/, aw = /^(?:button|input)$/i, au = /^(?:button|input|object|select|textarea)$/i, ar = /^a(?:rea)?$/i, ap = /^(?:radio|checkbox)$/i;
    aL.props = {"for": "htmlFor", "class": "className", readonly: "readOnly", maxlength: "maxLength", cellspacing: "cellSpacing", rowspan: "rowSpan", colspan: "colSpan", tabindex: "tabIndex", usemap: "useMap", frameborder: "frameBorder"}, aL.fn.extend({attr: function (d, c) {
        return aL.access(this, d, c, !0, aL.attr)
    }, removeAttr: function (d, c) {
        return this.each(function () {
            aL.attr(this, d, ""), this.nodeType === 1 && this.removeAttribute(d)
        })
    }, addClass: function (r) {
        if (aL.isFunction(r)) {
            return this.each(function (a) {
                var e = aL(this);
                e.addClass(r.call(this, a, e.attr("class")))
            })
        }
        if (r && typeof r === "string") {
            var q = (r || "").split(az);
            for (var p = 0, o = this.length; p < o; p++) {
                var n = this[p];
                if (n.nodeType === 1) {
                    if (n.className) {
                        var m = " " + n.className + " ", l = n.className;
                        for (var k = 0, d = q.length; k < d; k++) {
                            m.indexOf(" " + q[k] + " ") < 0 && (l += " " + q[k])
                        }
                        n.className = aL.trim(l)
                    } else {
                        n.className = r
                    }
                }
            }
        }
        return this
    }, removeClass: function (d) {
        if (aL.isFunction(d)) {
            return this.each(function (a) {
                var e = aL(this);
                e.removeClass(d.call(this, a, e.attr("class")))
            })
        }
        if (d && typeof d === "string" || d === aP) {
            var p = (d || "").split(az);
            for (var o = 0, n = this.length; o < n; o++) {
                var m = this[o];
                if (m.nodeType === 1 && m.className) {
                    if (d) {
                        var k = (" " + m.className + " ").replace(aA, " ");
                        for (var j = 0, b = p.length; j < b; j++) {
                            k = k.replace(" " + p[j] + " ", " ")
                        }
                        m.className = aL.trim(k)
                    } else {
                        m.className = ""
                    }
                }
            }
        }
        return this
    }, toggleClass: function (f, d) {
        var h = typeof f, g = typeof d === "boolean";
        if (aL.isFunction(f)) {
            return this.each(function (b) {
                var a = aL(this);
                a.toggleClass(f.call(this, b, a.attr("class"), d), d)
            })
        }
        return this.each(function () {
            if (h === "string") {
                var k, e = 0, c = aL(this), b = d, a = f.split(az);
                while (k = a[e++]) {
                    b = g ? b : !c.hasClass(k), c[b ? "addClass" : "removeClass"](k)
                }
            } else {
                if (h === "undefined" || h === "boolean") {
                    this.className && aL._data(this, "__className__", this.className), this.className = this.className || f === !1 ? "" : aL._data(this, "__className__") || ""
                }
            }
        })
    }, hasClass: function (f) {
        var e = " " + f + " ";
        for (var h = 0, g = this.length; h < g; h++) {
            if ((" " + this[h].className + " ").replace(aA, " ").indexOf(e) > -1) {
                return !0
            }
        }
        return !1
    }, val: function (v) {
        if (!arguments.length) {
            var u = this[0];
            if (u) {
                if (aL.nodeName(u, "option")) {
                    var t = u.attributes.value;
                    return !t || t.specified ? u.value : u.text
                }
                if (aL.nodeName(u, "select")) {
                    var s = u.selectedIndex, r = [], q = u.options, p = u.type === "select-one";
                    if (s < 0) {
                        return null
                    }
                    for (var o = p ? s : 0, l = p ? s + 1 : q.length; o < l; o++) {
                        var d = q[o];
                        if (d.selected && (aL.support.optDisabled ? !d.disabled : d.getAttribute("disabled") === null) && (!d.parentNode.disabled || !aL.nodeName(d.parentNode, "optgroup"))) {
                            v = aL(d).val();
                            if (p) {
                                return v
                            }
                            r.push(v)
                        }
                    }
                    if (p && !r.length && q.length) {
                        return aL(q[s]).val()
                    }
                    return r
                }
                if (ap.test(u.type) && !aL.support.checkOn) {
                    return u.getAttribute("value") === null ? "on" : u.value
                }
                return(u.value || "").replace(ay, "")
            }
            return aP
        }
        var b = aL.isFunction(v);
        return this.each(function (a) {
            var j = aL(this), h = v;
            if (this.nodeType === 1) {
                b && (h = v.call(this, a, j.val())), h == null ? h = "" : typeof h === "number" ? h += "" : aL.isArray(h) && (h = aL.map(h, function (c) {
                    return c == null ? "" : c + ""
                }));
                if (aL.isArray(h) && ap.test(this.type)) {
                    this.checked = aL.inArray(j.val(), h) >= 0
                } else {
                    if (aL.nodeName(this, "select")) {
                        var g = aL.makeArray(h);
                        aL("option", this).each(function () {
                            this.selected = aL.inArray(aL(this).val(), g) >= 0
                        }), g.length || (this.selectedIndex = -1)
                    } else {
                        this.value = h
                    }
                }
            }
        })
    }}), aL.extend({attrFn: {val: !0, css: !0, html: !0, text: !0, data: !0, width: !0, height: !0, offset: !0}, attr: function (t, s, r, q) {
        if (!t || t.nodeType === 3 || t.nodeType === 8 || t.nodeType === 2) {
            return aP
        }
        if (q && s in aL.attrFn) {
            return aL(t)[s](r)
        }
        var p = t.nodeType !== 1 || !aL.isXMLDoc(t), o = r !== aP;
        s = p && aL.props[s] || s;
        if (t.nodeType === 1) {
            var n = ax.test(s);
            if (s === "selected" && !aL.support.optSelected) {
                var m = t.parentNode;
                m && (m.selectedIndex, m.parentNode && m.parentNode.selectedIndex)
            }
            if ((s in t || t[s] !== aP) && p && !n) {
                o && (s === "type" && aw.test(t.nodeName) && t.parentNode && aL.error("type property can't be changed"), r === null ? t.nodeType === 1 && t.removeAttribute(s) : t[s] = r);
                if (aL.nodeName(t, "form") && t.getAttributeNode(s)) {
                    return t.getAttributeNode(s).nodeValue
                }
                if (s === "tabIndex") {
                    var d = t.getAttributeNode("tabIndex");
                    return d && d.specified ? d.value : au.test(t.nodeName) || ar.test(t.nodeName) && t.href ? 0 : aP
                }
                return t[s]
            }
            if (!aL.support.style && p && s === "style") {
                o && (t.style.cssText = "" + r);
                return t.style.cssText
            }
            o && t.setAttribute(s, "" + r);
            if (!t.attributes[s] && (t.hasAttribute && !t.hasAttribute(s))) {
                return aP
            }
            var b = !aL.support.hrefNormalized && p && n ? t.getAttribute(s, 2) : t.getAttribute(s);
            return b === null ? aP : b
        }
        o && (t[s] = r);
        return t[s]
    }});
    var am = /\.(.*)$/, ak = /^(?:textarea|input|select)$/i, aj = /\./g, ah = / /g, ae = /[^\w\s.|`]/g, ad = function (b) {
        return b.replace(ae, "\\$&")
    };
    aL.event = {add: function (D, C, B, A) {
        if (D.nodeType !== 3 && D.nodeType !== 8) {
            try {
                aL.isWindow(D) && (D !== aR && !D.frameElement) && (D = aR)
            } catch (z) {
            }
            if (B === !1) {
                B = ac
            } else {
                if (!B) {
                    return
                }
            }
            var y, x;
            B.handler && (y = B, B = y.handler), B.guid || (B.guid = aL.guid++);
            var w = aL._data(D);
            if (!w) {
                return
            }
            var v = w.events, u = w.handle;
            v || (w.events = v = {}), u || (w.handle = u = function (c) {
                return typeof aL !== "undefined" && aL.event.triggered !== c.type ? aL.event.handle.apply(u.elem, arguments) : aP
            }), u.elem = D, C = C.split(" ");
            var t, s = 0, d;
            while (t = C[s++]) {
                x = y ? aL.extend({}, y) : {handler: B, data: A}, t.indexOf(".") > -1 ? (d = t.split("."), t = d.shift(), x.namespace = d.slice(0).sort().join(".")) : (d = [], x.namespace = ""), x.type = t, x.guid || (x.guid = B.guid);
                var b = v[t], a = aL.event.special[t] || {};
                if (!b) {
                    b = v[t] = [];
                    if (!a.setup || a.setup.call(D, A, d, u) === !1) {
                        D.addEventListener ? D.addEventListener(t, u, !1) : D.attachEvent && D.attachEvent("on" + t, u)
                    }
                }
                a.add && (a.add.call(D, x), x.handler.guid || (x.handler.guid = B.guid)), b.push(x), aL.event.global[t] = !0
            }
            D = null
        }
    }, global: {}, remove: function (L, K, J, I) {
        if (L.nodeType !== 3 && L.nodeType !== 8) {
            J === !1 && (J = ac);
            var H, G, F, E, D = 0, C, B, A, z, y, x, w, v = aL.hasData(L) && aL._data(L), d = v && v.events;
            if (!v || !d) {
                return
            }
            K && K.type && (J = K.handler, K = K.type);
            if (!K || typeof K === "string" && K.charAt(0) === ".") {
                K = K || "";
                for (G in d) {
                    aL.event.remove(L, G + K)
                }
                return
            }
            K = K.split(" ");
            while (G = K[D++]) {
                w = G, x = null, C = G.indexOf(".") < 0, B = [], C || (B = G.split("."), G = B.shift(), A = new RegExp("(^|\\.)" + aL.map(B.slice(0).sort(), ad).join("\\.(?:.*\\.)?") + "(\\.|$)")), y = d[G];
                if (!y) {
                    continue
                }
                if (!J) {
                    for (E = 0; E < y.length; E++) {
                        x = y[E];
                        if (C || A.test(x.namespace)) {
                            aL.event.remove(L, w, x.handler, E), y.splice(E--, 1)
                        }
                    }
                    continue
                }
                z = aL.event.special[G] || {};
                for (E = I || 0; E < y.length; E++) {
                    x = y[E];
                    if (J.guid === x.guid) {
                        if (C || A.test(x.namespace)) {
                            I == null && y.splice(E--, 1), z.remove && z.remove.call(L, x)
                        }
                        if (I != null) {
                            break
                        }
                    }
                }
                if (y.length === 0 || I != null && y.length === 1) {
                    (!z.teardown || z.teardown.call(L, B) === !1) && aL.removeEvent(L, G, v.handle), H = null, delete d[G]
                }
            }
            if (aL.isEmptyObject(d)) {
                var b = v.handle;
                b && (b.elem = null), delete v.events, delete v.handle, aL.isEmptyObject(v) && aL.removeData(L, aP, !0)
            }
        }
    }, trigger: function (B, A, z) {
        var y = B.type || B, x = arguments[3];
        if (!x) {
            B = typeof B === "object" ? B[aL.expando] ? B : aL.extend(aL.Event(y), B) : aL.Event(y), y.indexOf("!") >= 0 && (B.type = y = y.slice(0, -1), B.exclusive = !0), z || (B.stopPropagation(), aL.event.global[y] && aL.each(aL.cache, function () {
                var a = aL.expando, c = this[a];
                c && c.events && c.events[y] && aL.event.trigger(B, A, c.handle.elem)
            }));
            if (!z || z.nodeType === 3 || z.nodeType === 8) {
                return aP
            }
            B.result = aP, B.target = z, A = aL.makeArray(A), A.unshift(B)
        }
        B.currentTarget = z;
        var w = aL._data(z, "handle");
        w && w.apply(z, A);
        var v = z.parentNode || z.ownerDocument;
        try {
            z && z.nodeName && aL.noData[z.nodeName.toLowerCase()] || z["on" + y] && z["on" + y].apply(z, A) === !1 && (B.result = !1, B.preventDefault())
        } catch (u) {
        }
        if (!B.isPropagationStopped() && v) {
            aL.event.trigger(B, A, v, !0)
        } else {
            if (!B.isDefaultPrevented()) {
                var t, s = B.target, r = y.replace(am, ""), q = aL.nodeName(s, "a") && r === "click", d = aL.event.special[r] || {};
                if ((!d._default || d._default.call(z, B) === !1) && !q && !(s && s.nodeName && aL.noData[s.nodeName.toLowerCase()])) {
                    try {
                        s[r] && (t = s["on" + r], t && (s["on" + r] = null), aL.event.triggered = B.type, s[r]())
                    } catch (b) {
                    }
                    t && (s["on" + r] = t), aL.event.triggered = aP
                }
            }
        }
    }, handle: function (x) {
        var w, v, u, t, s, r = [], q = aL.makeArray(arguments);
        x = q[0] = aL.event.fix(x || aR.event), x.currentTarget = this, w = x.type.indexOf(".") < 0 && !x.exclusive, w || (u = x.type.split("."), x.type = u.shift(), r = u.slice(0).sort(), t = new RegExp("(^|\\.)" + r.join("\\.(?:.*\\.)?") + "(\\.|$)")), x.namespace = x.namespace || r.join("."), s = aL._data(this, "events"), v = (s || {})[x.type];
        if (s && v) {
            v = v.slice(0);
            for (var p = 0, d = v.length; p < d; p++) {
                var b = v[p];
                if (w || t.test(b.namespace)) {
                    x.handler = b.handler, x.data = b.data, x.handleObj = b;
                    var a = b.handler.apply(this, q);
                    a !== aP && (x.result = a, a === !1 && (x.preventDefault(), x.stopPropagation()));
                    if (x.isImmediatePropagationStopped()) {
                        break
                    }
                }
            }
        }
        return x.result
    }, props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "), fix: function (b) {
        if (b[aL.expando]) {
            return b
        }
        var l = b;
        b = aL.Event(l);
        for (var k = this.props.length, j; k;) {
            j = this.props[--k], b[j] = l[j]
        }
        b.target || (b.target = b.srcElement || aN), b.target.nodeType === 3 && (b.target = b.target.parentNode), !b.relatedTarget && b.fromElement && (b.relatedTarget = b.fromElement === b.target ? b.toElement : b.fromElement);
        if (b.pageX == null && b.clientX != null) {
            var d = aN.documentElement, c = aN.body;
            b.pageX = b.clientX + (d && d.scrollLeft || c && c.scrollLeft || 0) - (d && d.clientLeft || c && c.clientLeft || 0), b.pageY = b.clientY + (d && d.scrollTop || c && c.scrollTop || 0) - (d && d.clientTop || c && c.clientTop || 0)
        }
        b.which == null && (b.charCode != null || b.keyCode != null) && (b.which = b.charCode != null ? b.charCode : b.keyCode), !b.metaKey && b.ctrlKey && (b.metaKey = b.ctrlKey), !b.which && b.button !== aP && (b.which = b.button & 1 ? 1 : b.button & 2 ? 3 : b.button & 4 ? 2 : 0);
        return b
    }, guid: 100000000, proxy: aL.proxy, special: {ready: {setup: aL.bindReady, teardown: aL.noop}, live: {add: function (b) {
        aL.event.add(this, ct(b.origType, b.selector), aL.extend({}, b, {handler: cv, guid: b.handler.guid}))
    }, remove: function (b) {
        aL.event.remove(this, ct(b.origType, b.selector), b)
    }}, beforeunload: {setup: function (e, d, f) {
        aL.isWindow(this) && (this.onbeforeunload = f)
    }, teardown: function (d, c) {
        this.onbeforeunload === c && (this.onbeforeunload = null)
    }}}}, aL.removeEvent = aN.removeEventListener ? function (e, d, f) {
        e.removeEventListener && e.removeEventListener(d, f, !1)
    } : function (e, d, f) {
        e.detachEvent && e.detachEvent("on" + d, f)
    }, aL.Event = function (b) {
        if (!this.preventDefault) {
            return new aL.Event(b)
        }
        b && b.type ? (this.originalEvent = b, this.type = b.type, this.isDefaultPrevented = b.defaultPrevented || b.returnValue === !1 || b.getPreventDefault && b.getPreventDefault() ? ab : ac) : this.type = b, this.timeStamp = aL.now(), this[aL.expando] = !0
    }, aL.Event.prototype = {preventDefault: function () {
        this.isDefaultPrevented = ab;
        var b = this.originalEvent;
        b && (b.preventDefault ? b.preventDefault() : b.returnValue = !1)
    }, stopPropagation: function () {
        this.isPropagationStopped = ab;
        var b = this.originalEvent;
        b && (b.stopPropagation && b.stopPropagation(), b.cancelBubble = !0)
    }, stopImmediatePropagation: function () {
        this.isImmediatePropagationStopped = ab, this.stopPropagation()
    }, isDefaultPrevented: ac, isPropagationStopped: ac, isImmediatePropagationStopped: ac};
    var aa = function (d) {
        var c = d.relatedTarget;
        try {
            if (c && c !== aN && !c.parentNode) {
                return
            }
            while (c && c !== this) {
                c = c.parentNode
            }
            c !== this && (d.type = d.data, aL.event.handle.apply(this, arguments))
        } catch (f) {
        }
    }, cH = function (b) {
        b.type = b.data, aL.event.handle.apply(this, arguments)
    };
    aL.each({mouseenter: "mouseover", mouseleave: "mouseout"}, function (d, c) {
        aL.event.special[d] = {setup: function (a) {
            aL.event.add(this, c, a && a.selector ? cH : aa, d)
        }, teardown: function (b) {
            aL.event.remove(this, c, b && b.selector ? cH : aa)
        }}
    }), aL.support.submitBubbles || (aL.event.special.submit = {setup: function (d, c) {
        if (this.nodeName && this.nodeName.toLowerCase() !== "form") {
            aL.event.add(this, "click.specialSubmit", function (f) {
                var e = f.target, g = e.type;
                (g === "submit" || g === "image") && aL(e).closest("form").length && cA("submit", this, arguments)
            }), aL.event.add(this, "keypress.specialSubmit", function (f) {
                var e = f.target, g = e.type;
                (g === "text" || g === "password") && aL(e).closest("form").length && f.keyCode === 13 && cA("submit", this, arguments)
            })
        } else {
            return !1
        }
    }, teardown: function (b) {
        aL.event.remove(this, ".specialSubmit")
    }});
    if (!aL.support.changeBubbles) {
        var cG, cE = function (e) {
            var d = e.type, f = e.value;
            d === "radio" || d === "checkbox" ? f = e.checked : d === "select-multiple" ? f = e.selectedIndex > -1 ? aL.map(e.options,function (b) {
                return b.selected
            }).join("-") : "" : e.nodeName.toLowerCase() === "select" && (f = e.selectedIndex);
            return f
        }, cC = function cC(b) {
            var h = b.target, g, d;
            if (ak.test(h.nodeName) && !h.readOnly) {
                g = aL._data(h, "_change_data"), d = cE(h), (b.type !== "focusout" || h.type !== "radio") && aL._data(h, "_change_data", d);
                if (g === aP || d === g) {
                    return
                }
                if (g != null || d) {
                    b.type = "change", b.liveFired = aP, aL.event.trigger(b, arguments[1], h)
                }
            }
        };
        aL.event.special.change = {filters: {focusout: cC, beforedeactivate: cC, click: function (e) {
            var d = e.target, f = d.type;
            (f === "radio" || f === "checkbox" || d.nodeName.toLowerCase() === "select") && cC.call(this, e)
        }, keydown: function (e) {
            var d = e.target, f = d.type;
            (e.keyCode === 13 && d.nodeName.toLowerCase() !== "textarea" || e.keyCode === 32 && (f === "checkbox" || f === "radio") || f === "select-multiple") && cC.call(this, e)
        }, beforeactivate: function (d) {
            var c = d.target;
            aL._data(c, "_change_data", cE(c))
        }}, setup: function (e, d) {
            if (this.type === "file") {
                return !1
            }
            for (var f in cG) {
                aL.event.add(this, f + ".specialChange", cG[f])
            }
            return ak.test(this.nodeName)
        }, teardown: function (b) {
            aL.event.remove(this, ".specialChange");
            return ak.test(this.nodeName)
        }}, cG = aL.event.special.change.filters, cG.focus = cG.beforeactivate
    }
    aN.addEventListener && aL.each({focus: "focusin", blur: "focusout"}, function (d, c) {
        function g(b) {
            var e = aL.event.fix(b);
            e.type = c, e.originalEvent = {}, aL.event.trigger(e, null, e.target), e.isDefaultPrevented() && b.preventDefault()
        }

        var h = 0;
        aL.event.special[c] = {setup: function () {
            h++ === 0 && aN.addEventListener(d, g, !0)
        }, teardown: function () {
            --h === 0 && aN.removeEventListener(d, g, !0)
        }}
    }), aL.each(["bind", "one"], function (b, d) {
        aL.fn[d] = function (c, p, o) {
            if (typeof c === "object") {
                for (var n in c) {
                    this[d](n, p, c[n], o)
                }
                return this
            }
            if (aL.isFunction(p) || p === !1) {
                o = p, p = aP
            }
            var m = d === "one" ? aL.proxy(o, function (e) {
                aL(this).unbind(e, m);
                return o.apply(this, arguments)
            }) : o;
            if (c === "unload" && d !== "one") {
                this.one(c, p, o)
            } else {
                for (var l = 0, k = this.length; l < k; l++) {
                    aL.event.add(this[l], c, m, p)
                }
            }
            return this
        }
    }), aL.fn.extend({unbind: function (g, d) {
        if (typeof g !== "object" || g.preventDefault) {
            for (var j = 0, h = this.length; j < h; j++) {
                aL.event.remove(this[j], g, d)
            }
        } else {
            for (var k in g) {
                this.unbind(k, g[k])
            }
        }
        return this
    }, delegate: function (f, e, h, g) {
        return this.live(e, h, g, f)
    }, undelegate: function (e, d, f) {
        return arguments.length === 0 ? this.unbind("live") : this.die(d, null, f, e)
    }, trigger: function (d, c) {
        return this.each(function () {
            aL.event.trigger(d, c, this)
        })
    }, triggerHandler: function (e, d) {
        if (this[0]) {
            var f = aL.Event(e);
            f.preventDefault(), f.stopPropagation(), aL.event.trigger(f, d, this[0]);
            return f.result
        }
    }, toggle: function (e) {
        var d = arguments, f = 1;
        while (f < d.length) {
            aL.proxy(e, d[f++])
        }
        return this.click(aL.proxy(e, function (b) {
            var a = (aL._data(this, "lastToggle" + e.guid) || 0) % f;
            aL._data(this, "lastToggle" + e.guid, a + 1), b.preventDefault();
            return d[a].apply(this, arguments) || !1
        }))
    }, hover: function (d, c) {
        return this.mouseenter(d).mouseleave(c || d)
    }});
    var cy = {focus: "focusin", blur: "focusout", mouseenter: "mouseover", mouseleave: "mouseout"};
    aL.each(["live", "die"], function (b, d) {
        aL.fn[d] = function (D, C, B, A) {
            var z, y = 0, x, w, v, u = A || this.selector, t = A ? this : aL(this.context);
            if (typeof D === "object" && !D.preventDefault) {
                for (var s in D) {
                    t[d](s, C, D[s], u)
                }
                return this
            }
            aL.isFunction(C) && (B = C, C = aP), D = (D || "").split(" ");
            while ((z = D[y++]) != null) {
                x = am.exec(z), w = "", x && (w = x[0], z = z.replace(am, ""));
                if (z === "hover") {
                    D.push("mouseenter" + w, "mouseleave" + w);
                    continue
                }
                v = z, z === "focus" || z === "blur" ? (D.push(cy[z] + w), z = z + w) : z = (cy[z] || z) + w;
                if (d === "live") {
                    for (var r = 0, c = t.length; r < c; r++) {
                        aL.event.add(t[r], "live." + ct(z, u), {data: C, selector: u, handler: B, origType: z, origHandler: B, preType: v})
                    }
                } else {
                    t.unbind("live." + ct(z, u), B)
                }
            }
            return this
        }
    }), aL.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error".split(" "), function (d, c) {
        aL.fn[c] = function (b, e) {
            e == null && (e = b, b = null);
            return arguments.length > 0 ? this.bind(c, b, e) : this.trigger(c)
        }, aL.attrFn && (aL.attrFn[c] = !0)
    }), function () {
        function c(t, s, r, q, p, o) {
            for (var n = 0, m = q.length; n < m; n++) {
                var l = q[n];
                if (l) {
                    var k = !1;
                    l = l[t];
                    while (l) {
                        if (l.sizcache === r) {
                            k = q[l.sizset];
                            break
                        }
                        if (l.nodeType === 1) {
                            o || (l.sizcache = r, l.sizset = n);
                            if (typeof s !== "string") {
                                if (l === s) {
                                    k = !0;
                                    break
                                }
                            } else {
                                if (E.filter(s, [l]).length > 0) {
                                    k = l;
                                    break
                                }
                            }
                        }
                        l = l[t]
                    }
                    q[n] = k
                }
            }
        }

        function d(t, s, r, q, p, o) {
            for (var n = 0, m = q.length; n < m; n++) {
                var l = q[n];
                if (l) {
                    var k = !1;
                    l = l[t];
                    while (l) {
                        if (l.sizcache === r) {
                            k = q[l.sizset];
                            break
                        }
                        l.nodeType === 1 && !o && (l.sizcache = r, l.sizset = n);
                        if (l.nodeName.toLowerCase() === s) {
                            k = l;
                            break
                        }
                        l = l[t]
                    }
                    q[n] = k
                }
            }
        }

        var L = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g, K = 0, J = Object.prototype.toString, I = !1, H = !0, G = /\\/g, F = /\W/;
        [0, 0].sort(function () {
            H = !1;
            return 0
        });
        var E = function (Q, O, N, M) {
            N = N || [], O = O || aN;
            var v = O;
            if (O.nodeType !== 1 && O.nodeType !== 9) {
                return[]
            }
            if (!Q || typeof Q !== "string") {
                return N
            }
            var p, m, l, k, f, a, V, U, T = !0, S = E.isXML(O), R = [], P = Q;
            do {
                L.exec(""), p = L.exec(P);
                if (p) {
                    P = p[3], R.push(p[1]);
                    if (p[2]) {
                        k = p[3];
                        break
                    }
                }
            } while (p);
            if (R.length > 1 && C.exec(Q)) {
                if (R.length === 2 && D.relative[R[0]]) {
                    m = b(R[0] + R[1], O)
                } else {
                    m = D.relative[R[0]] ? [O] : E(R.shift(), O);
                    while (R.length) {
                        Q = R.shift(), D.relative[Q] && (Q += R.shift()), m = b(Q, m)
                    }
                }
            } else {
                !M && R.length > 1 && O.nodeType === 9 && !S && D.match.ID.test(R[0]) && !D.match.ID.test(R[R.length - 1]) && (f = E.find(R.shift(), O, S), O = f.expr ? E.filter(f.expr, f.set)[0] : f.set[0]);
                if (O) {
                    f = M ? {expr: R.pop(), set: z(M)} : E.find(R.pop(), R.length === 1 && (R[0] === "~" || R[0] === "+") && O.parentNode ? O.parentNode : O, S), m = f.expr ? E.filter(f.expr, f.set) : f.set, R.length > 0 ? l = z(m) : T = !1;
                    while (R.length) {
                        a = R.pop(), V = a, D.relative[a] ? V = R.pop() : a = "", V == null && (V = O), D.relative[a](l, V, S)
                    }
                } else {
                    l = R = []
                }
            }
            l || (l = m), l || E.error(a || Q);
            if (J.call(l) === "[object Array]") {
                if (T) {
                    if (O && O.nodeType === 1) {
                        for (U = 0; l[U] != null; U++) {
                            l[U] && (l[U] === !0 || l[U].nodeType === 1 && E.contains(O, l[U])) && N.push(m[U])
                        }
                    } else {
                        for (U = 0; l[U] != null; U++) {
                            l[U] && l[U].nodeType === 1 && N.push(m[U])
                        }
                    }
                } else {
                    N.push.apply(N, l)
                }
            } else {
                z(l, N)
            }
            k && (E(k, v, N, M), E.uniqueSort(N));
            return N
        };
        E.uniqueSort = function (f) {
            if (x) {
                I = H, f.sort(x);
                if (I) {
                    for (var e = 1; e < f.length; e++) {
                        f[e] === f[e - 1] && f.splice(e--, 1)
                    }
                }
            }
            return f
        }, E.matches = function (f, e) {
            return E(f, null, null, e)
        }, E.matchesSelector = function (f, e) {
            return E(e, null, null, [f]).length > 0
        }, E.find = function (s, r, q) {
            var p;
            if (!s) {
                return[]
            }
            for (var o = 0, n = D.order.length; o < n; o++) {
                var m, l = D.order[o];
                if (m = D.leftMatch[l].exec(s)) {
                    var k = m[1];
                    m.splice(1, 1);
                    if (k.substr(k.length - 1) !== "\\") {
                        m[1] = (m[1] || "").replace(G, ""), p = D.find[l](m, r, q);
                        if (p != null) {
                            s = s.replace(D.match[l], "");
                            break
                        }
                    }
                }
            }
            p || (p = typeof r.getElementsByTagName !== "undefined" ? r.getElementsByTagName("*") : []);
            return{set: p, expr: s}
        }, E.filter = function (W, V, U, T) {
            var S, R, Q = W, P = [], O = V, N = V && V[0] && E.isXML(V[0]);
            while (W && V.length) {
                for (var M in D.filter) {
                    if ((S = D.leftMatch[M].exec(W)) != null && S[2]) {
                        var v, u, l = D.filter[M], k = S[1];
                        R = !1, S.splice(1, 1);
                        if (k.substr(k.length - 1) === "\\") {
                            continue
                        }
                        O === P && (P = []);
                        if (D.preFilter[M]) {
                            S = D.preFilter[M](S, O, U, P, T, N);
                            if (S) {
                                if (S === !0) {
                                    continue
                                }
                            } else {
                                R = v = !0
                            }
                        }
                        if (S) {
                            for (var Y = 0; (u = O[Y]) != null; Y++) {
                                if (u) {
                                    v = l(u, S, Y, O);
                                    var X = T ^ !!v;
                                    U && v != null ? X ? R = !0 : O[Y] = !1 : X && (P.push(u), R = !0)
                                }
                            }
                        }
                        if (v !== aP) {
                            U || (O = P), W = W.replace(D.match[M], "");
                            if (!R) {
                                return[]
                            }
                            break
                        }
                    }
                }
                if (W === Q) {
                    if (R == null) {
                        E.error(W)
                    } else {
                        break
                    }
                }
                Q = W
            }
            return O
        }, E.error = function (e) {
            throw"Syntax error, unrecognized expression: " + e
        };
        var D = E.selectors = {order: ["ID", "NAME", "TAG"], match: {ID: /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/, CLASS: /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/, NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/, ATTR: /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/, TAG: /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/, CHILD: /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/, POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/, PSEUDO: /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/}, leftMatch: {}, attrMap: {"class": "className", "for": "htmlFor"}, attrHandle: {href: function (e) {
            return e.getAttribute("href")
        }, type: function (e) {
            return e.getAttribute("type")
        }}, relative: {"+": function (k, j) {
            var q = typeof j === "string", p = q && !F.test(j), o = q && !p;
            p && (j = j.toLowerCase());
            for (var n = 0, m = k.length, l; n < m; n++) {
                if (l = k[n]) {
                    while ((l = l.previousSibling) && l.nodeType !== 1) {
                    }
                    k[n] = o || l && l.nodeName.toLowerCase() === j ? l || !1 : l === j
                }
            }
            o && E.filter(j, k, !0)
        }, ">": function (j, h) {
            var o, n = typeof h === "string", m = 0, l = j.length;
            if (n && !F.test(h)) {
                h = h.toLowerCase();
                for (; m < l; m++) {
                    o = j[m];
                    if (o) {
                        var k = o.parentNode;
                        j[m] = k.nodeName.toLowerCase() === h ? k : !1
                    }
                }
            } else {
                for (; m < l; m++) {
                    o = j[m], o && (j[m] = n ? o.parentNode : o.parentNode === h)
                }
                n && E.filter(h, j, !0)
            }
        }, "": function (h, e, m) {
            var l, k = K++, j = c;
            typeof e === "string" && !F.test(e) && (e = e.toLowerCase(), l = e, j = d), j("parentNode", e, k, h, l, m)
        }, "~": function (h, e, m) {
            var l, k = K++, j = c;
            typeof e === "string" && !F.test(e) && (e = e.toLowerCase(), l = e, j = d), j("previousSibling", e, k, h, l, m)
        }}, find: {ID: function (f, e, h) {
            if (typeof e.getElementById !== "undefined" && !h) {
                var g = e.getElementById(f[1]);
                return g && g.parentNode ? [g] : []
            }
        }, NAME: function (h, g) {
            if (typeof g.getElementsByName !== "undefined") {
                var m = [], l = g.getElementsByName(h[1]);
                for (var k = 0, j = l.length; k < j; k++) {
                    l[k].getAttribute("name") === h[1] && m.push(l[k])
                }
                return m.length === 0 ? null : m
            }
        }, TAG: function (f, e) {
            if (typeof e.getElementsByTagName !== "undefined") {
                return e.getElementsByTagName(f[1])
            }
        }}, preFilter: {CLASS: function (k, j, q, p, o, n) {
            k = " " + k[1].replace(G, "") + " ";
            if (n) {
                return k
            }
            for (var m = 0, l; (l = j[m]) != null; m++) {
                l && (o ^ (l.className && (" " + l.className + " ").replace(/[\t\n\r]/g, " ").indexOf(k) >= 0) ? q || p.push(l) : q && (j[m] = !1))
            }
            return !1
        }, ID: function (e) {
            return e[1].replace(G, "")
        }, TAG: function (f, e) {
            return f[1].replace(G, "").toLowerCase()
        }, CHILD: function (f) {
            if (f[1] === "nth") {
                f[2] || E.error(f[0]), f[2] = f[2].replace(/^\+|\s*/g, "");
                var e = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec(f[2] === "even" && "2n" || f[2] === "odd" && "2n+1" || !/\D/.test(f[2]) && "0n+" + f[2] || f[2]);
                f[2] = e[1] + (e[2] || 1) - 0, f[3] = e[3] - 0
            } else {
                f[2] && E.error(f[0])
            }
            f[0] = K++;
            return f
        }, ATTR: function (j, h, o, n, m, l) {
            var k = j[1] = j[1].replace(G, "");
            !l && D.attrMap[k] && (j[1] = D.attrMap[k]), j[4] = (j[4] || j[5] || "").replace(G, ""), j[2] === "~=" && (j[4] = " " + j[4] + " ");
            return j
        }, PSEUDO: function (a, m, l, k, j) {
            if (a[1] === "not") {
                if ((L.exec(a[3]) || "").length > 1 || /^\w/.test(a[3])) {
                    a[3] = E(a[3], null, null, m)
                } else {
                    var h = E.filter(a[3], m, l, !0 ^ j);
                    l || k.push.apply(k, h);
                    return !1
                }
            } else {
                if (D.match.POS.test(a[0]) || D.match.CHILD.test(a[0])) {
                    return !0
                }
            }
            return a
        }, POS: function (e) {
            e.unshift(!0);
            return e
        }}, filters: {enabled: function (e) {
            return e.disabled === !1 && e.type !== "hidden"
        }, disabled: function (e) {
            return e.disabled === !0
        }, checked: function (e) {
            return e.checked === !0
        }, selected: function (e) {
            e.parentNode && e.parentNode.selectedIndex;
            return e.selected === !0
        }, parent: function (e) {
            return !!e.firstChild
        }, empty: function (e) {
            return !e.firstChild
        }, has: function (f, e, g) {
            return !!E(g[3], f).length
        }, header: function (e) {
            return/h\d/i.test(e.nodeName)
        }, text: function (f) {
            var e = f.getAttribute("type"), g = f.type;
            return"text" === g && (e === g || e === null)
        }, radio: function (e) {
            return"radio" === e.type
        }, checkbox: function (e) {
            return"checkbox" === e.type
        }, file: function (e) {
            return"file" === e.type
        }, password: function (e) {
            return"password" === e.type
        }, submit: function (e) {
            return"submit" === e.type
        }, image: function (e) {
            return"image" === e.type
        }, reset: function (e) {
            return"reset" === e.type
        }, button: function (e) {
            return"button" === e.type || e.nodeName.toLowerCase() === "button"
        }, input: function (e) {
            return/input|select|textarea|button/i.test(e.nodeName)
        }}, setFilters: {first: function (f, e) {
            return e === 0
        }, last: function (f, e, h, g) {
            return e === g.length - 1
        }, even: function (f, e) {
            return e % 2 === 0
        }, odd: function (f, e) {
            return e % 2 === 1
        }, lt: function (f, e, g) {
            return e < g[3] - 0
        }, gt: function (f, e, g) {
            return e > g[3] - 0
        }, nth: function (f, e, g) {
            return g[3] - 0 === e
        }, eq: function (f, e, g) {
            return g[3] - 0 === e
        }}, filter: {PSEUDO: function (r, q, p, o) {
            var n = q[1], m = D.filters[n];
            if (m) {
                return m(r, p, q, o)
            }
            if (n === "contains") {
                return(r.textContent || r.innerText || E.getText([r]) || "").indexOf(q[3]) >= 0
            }
            if (n === "not") {
                var l = q[3];
                for (var k = 0, j = l.length; k < j; k++) {
                    if (l[k] === r) {
                        return !1
                    }
                }
                return !0
            }
            E.error(n)
        }, CHILD: function (t, s) {
            var r = s[1], q = t;
            switch (r) {
                case"only":
                case"first":
                    while (q = q.previousSibling) {
                        if (q.nodeType === 1) {
                            return !1
                        }
                    }
                    if (r === "first") {
                        return !0
                    }
                    q = t;
                case"last":
                    while (q = q.nextSibling) {
                        if (q.nodeType === 1) {
                            return !1
                        }
                    }
                    return !0;
                case"nth":
                    var p = s[2], o = s[3];
                    if (p === 1 && o === 0) {
                        return !0
                    }
                    var n = s[0], m = t.parentNode;
                    if (m && (m.sizcache !== n || !t.nodeIndex)) {
                        var l = 0;
                        for (q = m.firstChild; q; q = q.nextSibling) {
                            q.nodeType === 1 && (q.nodeIndex = ++l)
                        }
                        m.sizcache = n
                    }
                    var k = t.nodeIndex - o;
                    return p === 0 ? k === 0 : k % p === 0 && k / p >= 0
            }
        }, ID: function (f, e) {
            return f.nodeType === 1 && f.getAttribute("id") === e
        }, TAG: function (f, e) {
            return e === "*" && f.nodeType === 1 || f.nodeName.toLowerCase() === e
        }, CLASS: function (f, e) {
            return(" " + (f.className || f.getAttribute("class")) + " ").indexOf(e) > -1
        }, ATTR: function (j, h) {
            var o = h[1], n = D.attrHandle[o] ? D.attrHandle[o](j) : j[o] != null ? j[o] : j.getAttribute(o), m = n + "", l = h[2], k = h[4];
            return n == null ? l === "!=" : l === "=" ? m === k : l === "*=" ? m.indexOf(k) >= 0 : l === "~=" ? (" " + m + " ").indexOf(k) >= 0 : k ? l === "!=" ? m !== k : l === "^=" ? m.indexOf(k) === 0 : l === "$=" ? m.substr(m.length - k.length) === k : l === "|=" ? m === k || m.substr(0, k.length + 1) === k + "-" : !1 : m && n !== !1
        }, POS: function (h, g, m, l) {
            var k = g[2], j = D.setFilters[k];
            if (j) {
                return j(h, m, g, l)
            }
        }}}, C = D.match.POS, B = function (f, e) {
            return"\\" + (e - 0 + 1)
        };
        for (var A in D.match) {
            D.match[A] = new RegExp(D.match[A].source + /(?![^\[]*\])(?![^\(]*\))/.source), D.leftMatch[A] = new RegExp(/(^(?:.|\r|\n)*?)/.source + D.match[A].source.replace(/\\(\d+)/g, B))
        }
        var z = function (f, e) {
            f = Array.prototype.slice.call(f, 0);
            if (e) {
                e.push.apply(e, f);
                return e
            }
            return f
        };
        try {
            Array.prototype.slice.call(aN.documentElement.childNodes, 0)[0].nodeType
        } catch (y) {
            z = function (g, f) {
                var k = 0, j = f || [];
                if (J.call(g) === "[object Array]") {
                    Array.prototype.push.apply(j, g)
                } else {
                    if (typeof g.length === "number") {
                        for (var h = g.length; k < h; k++) {
                            j.push(g[k])
                        }
                    } else {
                        for (; g[k]; k++) {
                            j.push(g[k])
                        }
                    }
                }
                return j
            }
        }
        var x, w;
        aN.documentElement.compareDocumentPosition ? x = function (f, e) {
            if (f === e) {
                I = !0;
                return 0
            }
            if (!f.compareDocumentPosition || !e.compareDocumentPosition) {
                return f.compareDocumentPosition ? -1 : 1
            }
            return f.compareDocumentPosition(e) & 4 ? -1 : 1
        } : (x = function (t, s) {
            var r, q, p = [], o = [], n = t.parentNode, m = s.parentNode, l = n;
            if (t === s) {
                I = !0;
                return 0
            }
            if (n === m) {
                return w(t, s)
            }
            if (!n) {
                return -1
            }
            if (!m) {
                return 1
            }
            while (l) {
                p.unshift(l), l = l.parentNode
            }
            l = m;
            while (l) {
                o.unshift(l), l = l.parentNode
            }
            r = p.length, q = o.length;
            for (var g = 0; g < r && g < q; g++) {
                if (p[g] !== o[g]) {
                    return w(p[g], o[g])
                }
            }
            return g === r ? w(t, o[g], -1) : w(p[g], s, 1)
        }, w = function (f, e, h) {
            if (f === e) {
                return h
            }
            var g = f.nextSibling;
            while (g) {
                if (g === e) {
                    return -1
                }
                g = g.nextSibling
            }
            return 1
        }), E.getText = function (f) {
            var e = "", h;
            for (var g = 0; f[g]; g++) {
                h = f[g], h.nodeType === 3 || h.nodeType === 4 ? e += h.nodeValue : h.nodeType !== 8 && (e += E.getText(h.childNodes))
            }
            return e
        }, function () {
            var f = aN.createElement("div"), h = "script" + (new Date).getTime(), g = aN.documentElement;
            f.innerHTML = "<a name='" + h + "'/>", g.insertBefore(f, g.firstChild), aN.getElementById(h) && (D.find.ID = function (j, m, l) {
                if (typeof m.getElementById !== "undefined" && !l) {
                    var k = m.getElementById(j[1]);
                    return k ? k.id === j[1] || typeof k.getAttributeNode !== "undefined" && k.getAttributeNode("id").nodeValue === j[1] ? [k] : aP : []
                }
            }, D.filter.ID = function (j, e) {
                var k = typeof j.getAttributeNode !== "undefined" && j.getAttributeNode("id");
                return j.nodeType === 1 && k && k.nodeValue === e
            }), g.removeChild(f), g = f = null
        }(), function () {
            var e = aN.createElement("div");
            e.appendChild(aN.createComment("")), e.getElementsByTagName("*").length > 0 && (D.find.TAG = function (g, f) {
                var k = f.getElementsByTagName(g[1]);
                if (g[1] === "*") {
                    var j = [];
                    for (var h = 0; k[h]; h++) {
                        k[h].nodeType === 1 && j.push(k[h])
                    }
                    k = j
                }
                return k
            }), e.innerHTML = "<a href='#'></a>", e.firstChild && typeof e.firstChild.getAttribute !== "undefined" && e.firstChild.getAttribute("href") !== "#" && (D.attrHandle.href = function (f) {
                return f.getAttribute("href", 2)
            }), e = null
        }(), aN.querySelectorAll && function () {
            var g = E, f = aN.createElement("div"), j = "__sizzle__";
            f.innerHTML = "<p class='TEST'></p>";
            if (!f.querySelectorAll || f.querySelectorAll(".TEST").length !== 0) {
                E = function (Q, P, O, N) {
                    P = P || aN;
                    if (!N && !E.isXML(P)) {
                        var M = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(Q);
                        if (M && (P.nodeType === 1 || P.nodeType === 9)) {
                            if (M[1]) {
                                return z(P.getElementsByTagName(Q), O)
                            }
                            if (M[2] && D.find.CLASS && P.getElementsByClassName) {
                                return z(P.getElementsByClassName(M[2]), O)
                            }
                        }
                        if (P.nodeType === 9) {
                            if (Q === "body" && P.body) {
                                return z([P.body], O)
                            }
                            if (M && M[3]) {
                                var v = P.getElementById(M[3]);
                                if (!v || !v.parentNode) {
                                    return z([], O)
                                }
                                if (v.id === M[3]) {
                                    return z([v], O)
                                }
                            }
                            try {
                                return z(P.querySelectorAll(Q), O)
                            } catch (u) {
                            }
                        } else {
                            if (P.nodeType === 1 && P.nodeName.toLowerCase() !== "object") {
                                var t = P, p = P.getAttribute("id"), l = p || j, k = P.parentNode, a = /^\s*[+~]/.test(Q);
                                p ? l = l.replace(/'/g, "\\$&") : P.setAttribute("id", l), a && k && (P = P.parentNode);
                                try {
                                    if (!a || k) {
                                        return z(P.querySelectorAll("[id='" + l + "'] " + Q), O)
                                    }
                                } catch (R) {
                                } finally {
                                    p || t.removeAttribute("id")
                                }
                            }
                        }
                    }
                    return g(Q, P, O, N)
                };
                for (var h in g) {
                    E[h] = g[h]
                }
                f = null
            }
        }(), function () {
            var h = aN.documentElement, g = h.matchesSelector || h.mozMatchesSelector || h.webkitMatchesSelector || h.msMatchesSelector;
            if (g) {
                var l = !g.call(aN.createElement("div"), "div"), k = !1;
                try {
                    g.call(aN.documentElement, "[test!='']:sizzle")
                } catch (j) {
                    k = !0
                }
                E.matchesSelector = function (e, o) {
                    o = o.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']");
                    if (!E.isXML(e)) {
                        try {
                            if (k || !D.match.PSEUDO.test(o) && !/!=/.test(o)) {
                                var n = g.call(e, o);
                                if (n || !l || e.document && e.document.nodeType !== 11) {
                                    return n
                                }
                            }
                        } catch (m) {
                        }
                    }
                    return E(o, null, null, [e]).length > 0
                }
            }
        }(), function () {
            var e = aN.createElement("div");
            e.innerHTML = "<div class='test e'></div><div class='test'></div>";
            if (e.getElementsByClassName && e.getElementsByClassName("e").length !== 0) {
                e.lastChild.className = "e";
                if (e.getElementsByClassName("e").length === 1) {
                    return
                }
                D.order.splice(1, 0, "CLASS"), D.find.CLASS = function (g, f, h) {
                    if (typeof f.getElementsByClassName !== "undefined" && !h) {
                        return f.getElementsByClassName(g[1])
                    }
                }, e = null
            }
        }(), aN.documentElement.contains ? E.contains = function (f, e) {
            return f !== e && (f.contains ? f.contains(e) : !0)
        } : aN.documentElement.compareDocumentPosition ? E.contains = function (f, e) {
            return !!(f.compareDocumentPosition(e) & 16)
        } : E.contains = function () {
            return !1
        }, E.isXML = function (f) {
            var e = (f ? f.ownerDocument || f : 0).documentElement;
            return e ? e.nodeName !== "HTML" : !1
        };
        var b = function (k, j) {
            var q, p = [], o = "", n = j.nodeType ? [j] : j;
            while (q = D.match.PSEUDO.exec(k)) {
                o += q[0], k = k.replace(D.match.PSEUDO, "")
            }
            k = D.relative[k] ? k + "*" : k;
            for (var m = 0, l = n.length; m < l; m++) {
                E(k, n[m], p)
            }
            return E.filter(o, p)
        };
        aL.find = E, aL.expr = E.selectors, aL.expr[":"] = aL.expr.filters, aL.unique = E.uniqueSort, aL.text = E.getText, aL.isXMLDoc = E.isXML, aL.contains = E.contains
    }();
    var cs = /Until$/, cr = /^(?:parents|prevUntil|prevAll)/, cq = /,/, cp = /^.[^:#\[\.,]*$/, co = Array.prototype.slice, cm = aL.expr.match.POS, cl = {children: !0, contents: !0, next: !0, prev: !0};
    aL.fn.extend({find: function (j) {
        var d = this.pushStack("", "find", j), o = 0;
        for (var n = 0, m = this.length; n < m; n++) {
            o = d.length, aL.find(j, this[n], d);
            if (n > 0) {
                for (var l = o; l < d.length; l++) {
                    for (var k = 0; k < o; k++) {
                        if (d[k] === d[l]) {
                            d.splice(l--, 1);
                            break
                        }
                    }
                }
            }
        }
        return d
    }, has: function (d) {
        var c = aL(d);
        return this.filter(function () {
            for (var b = 0, e = c.length; b < e; b++) {
                if (aL.contains(this, c[b])) {
                    return !0
                }
            }
        })
    }, not: function (b) {
        return this.pushStack(b8(this, b, !1), "not", b)
    }, filter: function (b) {
        return this.pushStack(b8(this, b, !0), "filter", b)
    }, is: function (b) {
        return !!b && aL.filter(b, this).length > 0
    }, closest: function (v, u) {
        var t = [], s, r, q = this[0];
        if (aL.isArray(v)) {
            var p, o, n = {}, m = 1;
            if (q && v.length) {
                for (s = 0, r = v.length; s < r; s++) {
                    o = v[s], n[o] || (n[o] = aL.expr.match.POS.test(o) ? aL(o, u || this.context) : o)
                }
                while (q && q.ownerDocument && q !== u) {
                    for (o in n) {
                        p = n[o], (p.jquery ? p.index(q) > -1 : aL(q).is(p)) && t.push({selector: o, elem: q, level: m})
                    }
                    q = q.parentNode, m++
                }
            }
            return t
        }
        var d = cm.test(v) ? aL(v, u || this.context) : null;
        for (s = 0, r = this.length; s < r; s++) {
            q = this[s];
            while (q) {
                if (d ? d.index(q) > -1 : aL.find.matchesSelector(q, v)) {
                    t.push(q);
                    break
                }
                q = q.parentNode;
                if (!q || !q.ownerDocument || q === u) {
                    break
                }
            }
        }
        t = t.length > 1 ? aL.unique(t) : t;
        return this.pushStack(t, "closest", v)
    }, index: function (b) {
        if (!b || typeof b === "string") {
            return aL.inArray(this[0], b ? aL(b) : this.parent().children())
        }
        return aL.inArray(b.jquery ? b[0] : b, this)
    }, add: function (f, d) {
        var h = typeof f === "string" ? aL(f, d) : aL.makeArray(f), g = aL.merge(this.get(), h);
        return this.pushStack(cj(h[0]) || cj(g[0]) ? g : aL.unique(g))
    }, andSelf: function () {
        return this.add(this.prevObject)
    }}), aL.each({parent: function (d) {
        var c = d.parentNode;
        return c && c.nodeType !== 11 ? c : null
    }, parents: function (b) {
        return aL.dir(b, "parentNode")
    }, parentsUntil: function (e, d, f) {
        return aL.dir(e, "parentNode", f)
    }, next: function (b) {
        return aL.nth(b, 2, "nextSibling")
    }, prev: function (b) {
        return aL.nth(b, 2, "previousSibling")
    }, nextAll: function (b) {
        return aL.dir(b, "nextSibling")
    }, prevAll: function (b) {
        return aL.dir(b, "previousSibling")
    }, nextUntil: function (e, d, f) {
        return aL.dir(e, "nextSibling", f)
    }, prevUntil: function (e, d, f) {
        return aL.dir(e, "previousSibling", f)
    }, siblings: function (b) {
        return aL.sibling(b.parentNode.firstChild, b)
    }, children: function (b) {
        return aL.sibling(b.firstChild)
    }, contents: function (b) {
        return aL.nodeName(b, "iframe") ? b.contentDocument || b.contentWindow.document : aL.makeArray(b.childNodes)
    }}, function (d, c) {
        aL.fn[d] = function (j, h) {
            var b = aL.map(this, c, j), a = co.call(arguments);
            cs.test(d) || (h = j), h && typeof h === "string" && (b = aL.filter(h, b)), b = this.length > 1 && !cl[d] ? aL.unique(b) : b, (this.length > 1 || cq.test(h)) && cr.test(d) && (b = b.reverse());
            return this.pushStack(b, d, a.join(","))
        }
    }), aL.extend({filter: function (e, d, f) {
        f && (e = ":not(" + e + ")");
        return d.length === 1 ? aL.find.matchesSelector(d[0], e) ? [d[0]] : [] : aL.find.matches(e, d)
    }, dir: function (b, k, j) {
        var h = [], d = b[k];
        while (d && d.nodeType !== 9 && (j === aP || d.nodeType !== 1 || !aL(d).is(j))) {
            d.nodeType === 1 && h.push(d), d = d[k]
        }
        return h
    }, nth: function (g, f, k, j) {
        f = f || 1;
        var h = 0;
        for (; g; g = g[k]) {
            if (g.nodeType === 1 && ++h === f) {
                break
            }
        }
        return g
    }, sibling: function (e, d) {
        var f = [];
        for (; e; e = e.nextSibling) {
            e.nodeType === 1 && e !== d && f.push(e)
        }
        return f
    }});
    var b6 = / jQuery\d+="(?:\d+|null)"/g, b4 = /^\s+/, b2 = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig, b0 = /<([\w:]+)/, a8 = /<tbody/i, a6 = /<|&#?\w+;/, a4 = /<(?:script|object|embed|option|style)/i, a2 = /checked\s*(?:[^=]|=\s*.checked.)/i, a0 = {option: [1, "<select multiple='multiple'>", "</select>"], legend: [1, "<fieldset>", "</fieldset>"], thead: [1, "<table>", "</table>"], tr: [2, "<table><tbody>", "</tbody></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"], area: [1, "<map>", "</map>"], _default: [0, "", ""]};
    a0.optgroup = a0.option, a0.tbody = a0.tfoot = a0.colgroup = a0.caption = a0.thead, a0.th = a0.td, aL.support.htmlSerialize || (a0._default = [1, "div<div>", "</div>"]), aL.fn.extend({text: function (b) {
        if (aL.isFunction(b)) {
            return this.each(function (a) {
                var d = aL(this);
                d.text(b.call(this, a, d.text()))
            })
        }
        if (typeof b !== "object" && b !== aP) {
            return this.empty().append((this[0] && this[0].ownerDocument || aN).createTextNode(b))
        }
        return aL.text(this)
    }, wrapAll: function (d) {
        if (aL.isFunction(d)) {
            return this.each(function (a) {
                aL(this).wrapAll(d.call(this, a))
            })
        }
        if (this[0]) {
            var c = aL(d, this[0].ownerDocument).eq(0).clone(!0);
            this[0].parentNode && c.insertBefore(this[0]), c.map(function () {
                var b = this;
                while (b.firstChild && b.firstChild.nodeType === 1) {
                    b = b.firstChild
                }
                return b
            }).append(this)
        }
        return this
    }, wrapInner: function (b) {
        if (aL.isFunction(b)) {
            return this.each(function (a) {
                aL(this).wrapInner(b.call(this, a))
            })
        }
        return this.each(function () {
            var a = aL(this), d = a.contents();
            d.length ? d.wrapAll(b) : a.append(b)
        })
    }, wrap: function (b) {
        return this.each(function () {
            aL(this).wrapAll(b)
        })
    }, unwrap: function () {
        return this.parent().each(function () {
            aL.nodeName(this, "body") || aL(this).replaceWith(this.childNodes)
        }).end()
    }, append: function () {
        return this.domManip(arguments, !0, function (b) {
            this.nodeType === 1 && this.appendChild(b)
        })
    }, prepend: function () {
        return this.domManip(arguments, !0, function (b) {
            this.nodeType === 1 && this.insertBefore(b, this.firstChild)
        })
    }, before: function () {
        if (this[0] && this[0].parentNode) {
            return this.domManip(arguments, !1, function (c) {
                this.parentNode.insertBefore(c, this)
            })
        }
        if (arguments.length) {
            var b = aL(arguments[0]);
            b.push.apply(b, this.toArray());
            return this.pushStack(b, "before", arguments)
        }
    }, after: function () {
        if (this[0] && this[0].parentNode) {
            return this.domManip(arguments, !1, function (c) {
                this.parentNode.insertBefore(c, this.nextSibling)
            })
        }
        if (arguments.length) {
            var b = this.pushStack(this, "after", arguments);
            b.push.apply(b, aL(arguments[0]).toArray());
            return b
        }
    }, remove: function (f, d) {
        for (var h = 0, g; (g = this[h]) != null; h++) {
            if (!f || aL.filter(f, [g]).length) {
                !d && g.nodeType === 1 && (aL.cleanData(g.getElementsByTagName("*")), aL.cleanData([g])), g.parentNode && g.parentNode.removeChild(g)
            }
        }
        return this
    }, empty: function () {
        for (var d = 0, c; (c = this[d]) != null; d++) {
            c.nodeType === 1 && aL.cleanData(c.getElementsByTagName("*"));
            while (c.firstChild) {
                c.removeChild(c.firstChild)
            }
        }
        return this
    }, clone: function (d, c) {
        d = d == null ? !1 : d, c = c == null ? d : c;
        return this.map(function () {
            return aL.clone(this, d, c)
        })
    }, html: function (b) {
        if (b === aP) {
            return this[0] && this[0].nodeType === 1 ? this[0].innerHTML.replace(b6, "") : null
        }
        if (typeof b !== "string" || a4.test(b) || !aL.support.leadingWhitespace && b4.test(b) || a0[(b0.exec(b) || ["", ""])[1].toLowerCase()]) {
            aL.isFunction(b) ? this.each(function (a) {
                var e = aL(this);
                e.html(b.call(this, a, e.html()))
            }) : this.empty().append(b)
        } else {
            b = b.replace(b2, "<$1></$2>");
            try {
                for (var h = 0, g = this.length; h < g; h++) {
                    this[h].nodeType === 1 && (aL.cleanData(this[h].getElementsByTagName("*")), this[h].innerHTML = b)
                }
            } catch (d) {
                this.empty().append(b)
            }
        }
        return this
    }, replaceWith: function (b) {
        if (this[0] && this[0].parentNode) {
            if (aL.isFunction(b)) {
                return this.each(function (a) {
                    var f = aL(this), d = f.html();
                    f.replaceWith(b.call(this, a, d))
                })
            }
            typeof b !== "string" && (b = aL(b).detach());
            return this.each(function () {
                var a = this.nextSibling, d = this.parentNode;
                aL(this).remove(), a ? aL(a).before(b) : aL(d).append(b)
            })
        }
        return this.length ? this.pushStack(aL(aL.isFunction(b) ? b() : b), "replaceWith", b) : this
    }, detach: function (b) {
        return this.remove(b, !0)
    }, domManip: function (x, w, v) {
        var u, t, s, r, q = x[0], p = [];
        if (!aL.support.checkClone && arguments.length === 3 && typeof q === "string" && a2.test(q)) {
            return this.each(function () {
                aL(this).domManip(x, w, v, !0)
            })
        }
        if (aL.isFunction(q)) {
            return this.each(function (c) {
                var a = aL(this);
                x[0] = q.call(this, c, w ? a.html() : aP), a.domManip(x, w, v)
            })
        }
        if (this[0]) {
            r = q && q.parentNode, aL.support.parentNode && r && r.nodeType === 11 && r.childNodes.length === this.length ? u = {fragment: r} : u = aL.buildFragment(x, this, p), s = u.fragment, s.childNodes.length === 1 ? t = s = s.firstChild : t = s.firstChild;
            if (t) {
                w = w && aL.nodeName(t, "tr");
                for (var o = 0, d = this.length, b = d - 1; o < d; o++) {
                    v.call(w ? c1(this[o], t) : this[o], u.cacheable || d > 1 && o < b ? aL.clone(s, !0, !0) : s)
                }
            }
            p.length && aL.each(p, b7)
        }
        return this
    }}), aL.buildFragment = function (d, c, n) {
        var m, l, k, j = c && c[0] ? c[0].ownerDocument || c[0] : aN;
        d.length === 1 && typeof d[0] === "string" && d[0].length < 512 && j === aN && d[0].charAt(0) === "<" && !a4.test(d[0]) && (aL.support.checkClone || !a2.test(d[0])) && (l = !0, k = aL.fragments[d[0]], k && (k !== 1 && (m = k))), m || (m = j.createDocumentFragment(), aL.clean(d, j, m, n)), l && (aL.fragments[d[0]] = k ? m : 1);
        return{fragment: m, cacheable: l}
    }, aL.fragments = {}, aL.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (d, c) {
        aL.fn[d] = function (o) {
            var n = [], m = aL(o), l = this.length === 1 && this[0].parentNode;
            if (l && l.nodeType === 11 && l.childNodes.length === 1 && m.length === 1) {
                m[c](this[0]);
                return this
            }
            for (var k = 0, b = m.length; k < b; k++) {
                var a = (k > 0 ? this.clone(!0) : this).get();
                aL(m[k])[c](a), n = n.concat(a)
            }
            return this.pushStack(n, d, m.selector)
        }
    }), aL.extend({clone: function (j, d, o) {
        var n = j.cloneNode(!0), m, l, k;
        if ((!aL.support.noCloneEvent || !aL.support.noCloneChecked) && (j.nodeType === 1 || j.nodeType === 11) && !aL.isXMLDoc(j)) {
            ck(j, n), m = b9(j), l = b9(n);
            for (k = 0; m[k]; ++k) {
                ck(m[k], l[k])
            }
        }
        if (d) {
            aT(j, n);
            if (o) {
                m = b9(j), l = b9(n);
                for (k = 0; m[k]; ++k) {
                    aT(m[k], l[k])
                }
            }
        }
        return n
    }, clean: function (B, A, z, y) {
        A = A || aN, typeof A.createElement === "undefined" && (A = A.ownerDocument || A[0] && A[0].ownerDocument || aN);
        var x = [];
        for (var w = 0, v; (v = B[w]) != null; w++) {
            typeof v === "number" && (v += "");
            if (!v) {
                continue
            }
            if (typeof v !== "string" || a6.test(v)) {
                if (typeof v === "string") {
                    v = v.replace(b2, "<$1></$2>");
                    var u = (b0.exec(v) || ["", ""])[1].toLowerCase(), t = a0[u] || a0._default, s = t[0], r = A.createElement("div");
                    r.innerHTML = t[1] + v + t[2];
                    while (s--) {
                        r = r.lastChild
                    }
                    if (!aL.support.tbody) {
                        var q = a8.test(v), d = u === "table" && !q ? r.firstChild && r.firstChild.childNodes : t[1] === "<table>" && !q ? r.childNodes : [];
                        for (var c = d.length - 1; c >= 0; --c) {
                            aL.nodeName(d[c], "tbody") && !d[c].childNodes.length && d[c].parentNode.removeChild(d[c])
                        }
                    }
                    !aL.support.leadingWhitespace && b4.test(v) && r.insertBefore(A.createTextNode(b4.exec(v)[0]), r.firstChild), v = r.childNodes
                }
            } else {
                v = A.createTextNode(v)
            }
            v.nodeType ? x.push(v) : x = aL.merge(x, v)
        }
        if (z) {
            for (w = 0; x[w]; w++) {
                !y || !aL.nodeName(x[w], "script") || x[w].type && x[w].type.toLowerCase() !== "text/javascript" ? (x[w].nodeType === 1 && x.splice.apply(x, [w + 1, 0].concat(aL.makeArray(x[w].getElementsByTagName("script")))), z.appendChild(x[w])) : y.push(x[w].parentNode ? x[w].parentNode.removeChild(x[w]) : x[w])
            }
        }
        return x
    }, cleanData: function (t) {
        var s, r, q = aL.cache, p = aL.expando, o = aL.event.special, n = aL.support.deleteExpando;
        for (var m = 0, l; (l = t[m]) != null; m++) {
            if (l.nodeName && aL.noData[l.nodeName.toLowerCase()]) {
                continue
            }
            r = l[aL.expando];
            if (r) {
                s = q[r] && q[r][p];
                if (s && s.events) {
                    for (var d in s.events) {
                        o[d] ? aL.event.remove(l, d) : aL.removeEvent(l, d, s.handle)
                    }
                    s.handle && (s.handle.elem = null)
                }
                n ? delete l[aL.expando] : l.removeAttribute && l.removeAttribute(aL.expando), delete q[r]
            }
        }
    }});
    var b5 = /alpha\([^)]*\)/i, b3 = /opacity=([^)]*)/, b1 = /-([a-z])/ig, a9 = /([A-Z]|^ms)/g, a7 = /^-?\d+(?:px)?$/i, a5 = /^-?\d/, a3 = {position: "absolute", visibility: "hidden", display: "block"}, a1 = ["Left", "Right"], aZ = ["Top", "Bottom"], aY, aX, aW, aV = function (d, c) {
        return c.toUpperCase()
    };
    aL.fn.css = function (b, d) {
        if (arguments.length === 2 && d === aP) {
            return this
        }
        return aL.access(this, b, d, !0, function (f, h, g) {
            return g !== aP ? aL.style(f, h, g) : aL.css(f, h)
        })
    }, aL.extend({cssHooks: {opacity: {get: function (e, d) {
        if (d) {
            var f = aY(e, "opacity", "opacity");
            return f === "" ? "1" : f
        }
        return e.style.opacity
    }}}, cssNumber: {zIndex: !0, fontWeight: !0, opacity: !0, zoom: !0, lineHeight: !0}, cssProps: {"float": aL.support.cssFloat ? "cssFloat" : "styleFloat"}, style: function (r, q, p, o) {
        if (r && r.nodeType !== 3 && r.nodeType !== 8 && r.style) {
            var n, m = aL.camelCase(q), l = r.style, d = aL.cssHooks[m];
            q = aL.cssProps[m] || m;
            if (p === aP) {
                if (d && "get" in d && (n = d.get(r, !1, o)) !== aP) {
                    return n
                }
                return l[q]
            }
            if (typeof p === "number" && isNaN(p) || p == null) {
                return
            }
            typeof p === "number" && !aL.cssNumber[m] && (p += "px");
            if (!d || !("set" in d) || (p = d.set(r, p)) !== aP) {
                try {
                    l[q] = p
                } catch (b) {
                }
            }
        }
    }, css: function (b, m, l) {
        var k, j = aL.camelCase(m), d = aL.cssHooks[j];
        m = aL.cssProps[j] || j;
        if (d && "get" in d && (k = d.get(b, !0, l)) !== aP) {
            return k
        }
        if (aY) {
            return aY(b, m, j)
        }
    }, swap: function (g, f, k) {
        var j = {};
        for (var h in f) {
            j[h] = g.style[h], g.style[h] = f[h]
        }
        k.call(g);
        for (h in f) {
            g.style[h] = j[h]
        }
    }, camelCase: function (b) {
        return b.replace(b1, aV)
    }}), aL.curCSS = aL.css, aL.each(["height", "width"], function (d, c) {
        aL.cssHooks[c] = {get: function (b, j, h) {
            var g;
            if (j) {
                b.offsetWidth !== 0 ? g = aU(b, c, h) : aL.swap(b, a3, function () {
                    g = aU(b, c, h)
                });
                if (g <= 0) {
                    g = aY(b, c, c), g === "0px" && aW && (g = aW(b, c, c));
                    if (g != null) {
                        return g === "" || g === "auto" ? "0px" : g
                    }
                }
                if (g < 0 || g == null) {
                    g = b.style[c];
                    return g === "" || g === "auto" ? "0px" : g
                }
                return typeof g === "string" ? g : g + "px"
            }
        }, set: function (f, e) {
            if (!a7.test(e)) {
                return e
            }
            e = parseFloat(e);
            if (e >= 0) {
                return e + "px"
            }
        }}
    }), aL.support.opacity || (aL.cssHooks.opacity = {get: function (d, c) {
        return b3.test((c && d.currentStyle ? d.currentStyle.filter : d.style.filter) || "") ? parseFloat(RegExp.$1) / 100 + "" : c ? "1" : ""
    }, set: function (g, d) {
        var k = g.style;
        k.zoom = 1;
        var j = aL.isNaN(d) ? "" : "alpha(opacity=" + d * 100 + ")", h = k.filter || "";
        k.filter = b5.test(h) ? h.replace(b5, j) : k.filter + " " + j
    }}), aL(function () {
        aL.support.reliableMarginRight || (aL.cssHooks.marginRight = {get: function (e, d) {
            var f;
            aL.swap(e, {display: "inline-block"}, function () {
                d ? f = aY(e, "margin-right", "marginRight") : f = e.style.marginRight
            });
            return f
        }})
    }), aN.defaultView && aN.defaultView.getComputedStyle && (aX = function (b, m, l) {
        var k, j, d;
        l = l.replace(a9, "-$1").toLowerCase();
        if (!(j = b.ownerDocument.defaultView)) {
            return aP
        }
        if (d = j.getComputedStyle(b, null)) {
            k = d.getPropertyValue(l), k === "" && !aL.contains(b.ownerDocument.documentElement, b) && (k = aL.style(b, l))
        }
        return k
    }), aN.documentElement.currentStyle && (aW = function (h, g) {
        var m, l = h.currentStyle && h.currentStyle[g], k = h.runtimeStyle && h.runtimeStyle[g], j = h.style;
        !a7.test(l) && a5.test(l) && (m = j.left, k && (h.runtimeStyle.left = h.currentStyle.left), j.left = g === "fontSize" ? "1em" : l || 0, l = j.pixelLeft + "px", j.left = m, k && (h.runtimeStyle.left = k));
        return l === "" ? "auto" : l
    }), aY = aX || aW, aL.expr && aL.expr.filters && (aL.expr.filters.hidden = function (e) {
        var d = e.offsetWidth, f = e.offsetHeight;
        return d === 0 && f === 0 || !aL.support.reliableHiddenOffsets && (e.style.display || aL.css(e, "display")) === "none"
    }, aL.expr.filters.visible = function (b) {
        return !aL.expr.filters.hidden(b)
    });
    var aS = /%20/g, aQ = /\[\]$/, aO = /\r?\n/g, aM = /#.*$/, aK = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg, aI = /^(?:color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i, aG = /^(?:about|app|app\-storage|.+\-extension|file|widget):$/, aE = /^(?:GET|HEAD)$/, aC = /^\/\//, c0 = /\?/, cZ = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, cY = /^(?:select|textarea)/i, cX = /\s+/, cW = /([?&])_=[^&]*/, cV = /(^|\-)([a-z])/g, cU = function (e, d, f) {
        return d + f.toUpperCase()
    }, cT = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/, cS = aL.fn.load, cR = {}, cQ = {}, cP, cO;
    try {
        cP = aN.location.href
    } catch (cN) {
        cP = aN.createElement("a"), cP.href = "", cP = cP.href
    }
    cO = cT.exec(cP.toLowerCase()) || [], aL.fn.extend({load: function (b, n, m) {
        if (typeof b !== "string" && cS) {
            return cS.apply(this, arguments)
        }
        if (!this.length) {
            return this
        }
        var l = b.indexOf(" ");
        if (l >= 0) {
            var k = b.slice(l, b.length);
            b = b.slice(0, l)
        }
        var j = "GET";
        n && (aL.isFunction(n) ? (m = n, n = aP) : typeof n === "object" && (n = aL.param(n, aL.ajaxSettings.traditional), j = "POST"));
        var d = this;
        aL.ajax({url: b, type: j, dataType: "html", data: n, complete: function (f, e, g) {
            g = f.responseText, f.isResolved() && (f.done(function (c) {
                g = c
            }), d.html(k ? aL("<div>").append(g.replace(cZ, "")).find(k) : g)), m && d.each(m, [g, e, f])
        }});
        return this
    }, serialize: function () {
        return aL.param(this.serializeArray())
    }, serializeArray: function () {
        return this.map(function () {
            return this.elements ? aL.makeArray(this.elements) : this
        }).filter(function () {
            return this.name && !this.disabled && (this.checked || cY.test(this.nodeName) || aI.test(this.type))
        }).map(function (e, d) {
            var f = aL(this).val();
            return f == null ? null : aL.isArray(f) ? aL.map(f, function (b, g) {
                return{name: d.name, value: b.replace(aO, "\r\n")}
            }) : {name: d.name, value: f.replace(aO, "\r\n")}
        }).get()
    }}), aL.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function (d, c) {
        aL.fn[c] = function (b) {
            return this.bind(c, b)
        }
    }), aL.each(["get", "post"], function (b, d) {
        aL[d] = function (c, k, j, h) {
            aL.isFunction(k) && (h = h || j, j = k, k = aP);
            return aL.ajax({type: d, url: c, data: k, success: j, dataType: h})
        }
    }), aL.extend({getScript: function (b, d) {
        return aL.get(b, aP, d, "script")
    }, getJSON: function (e, d, f) {
        return aL.get(e, d, f, "json")
    }, ajaxSetup: function (e, d) {
        d ? aL.extend(!0, e, aL.ajaxSettings, d) : (d = e, e = aL.extend(!0, aL.ajaxSettings, d));
        for (var f in {context: 1, url: 1}) {
            f in d ? e[f] = d[f] : f in aL.ajaxSettings && (e[f] = aL.ajaxSettings[f])
        }
        return e
    }, ajaxSettings: {url: cP, isLocal: aG.test(cO[1]), global: !0, type: "GET", contentType: "application/x-www-form-urlencoded", processData: !0, async: !0, accepts: {xml: "application/xml, text/xml", html: "text/html", text: "text/plain", json: "application/json, text/javascript", "*": "*/*"}, contents: {xml: /xml/, html: /html/, json: /json/}, responseFields: {xml: "responseXML", text: "responseText"}, converters: {"* text": aR.String, "text html": !0, "text json": aL.parseJSON, "text xml": aL.parseXML}}, ajaxPrefilter: cM(cR), ajaxTransport: cM(cQ), ajax: function (T, S) {
        function A(o, j, g, f) {
            if (E !== 2) {
                E = 2, G && clearTimeout(G), H = aP, J = f || "", B.readyState = o ? 4 : 0;
                var e, s, r, p = g ? cJ(R, B, g) : aP, m, k;
                if (o >= 200 && o < 300 || o === 304) {
                    if (R.ifModified) {
                        if (m = B.getResponseHeader("Last-Modified")) {
                            aL.lastModified[L] = m
                        }
                        if (k = B.getResponseHeader("Etag")) {
                            aL.etag[L] = k
                        }
                    }
                    if (o === 304) {
                        j = "notmodified", e = !0
                    } else {
                        try {
                            s = cI(R, p), j = "success", e = !0
                        } catch (h) {
                            j = "parsererror", r = h
                        }
                    }
                } else {
                    r = j;
                    if (!j || o) {
                        j = "error", o < 0 && (o = 0)
                    }
                }
                B.status = o, B.statusText = j, e ? O.resolveWith(Q, [s, j, B]) : O.rejectWith(Q, [B, j, r]), B.statusCode(M), M = aP, D && P.trigger("ajax" + (e ? "Success" : "Error"), [B, R, e ? s : r]), N.resolveWith(Q, [B, j]), D && (P.trigger("ajaxComplete", [B, R]), --aL.active || aL.event.trigger("ajaxStop"))
            }
        }

        typeof T === "object" && (S = T, T = aP), S = S || {};
        var R = aL.ajaxSetup({}, S), Q = R.context || R, P = Q !== R && (Q.nodeType || Q instanceof aL) ? aL(Q) : aL.event, O = aL.Deferred(), N = aL._Deferred(), M = R.statusCode || {}, L, K = {}, J, I, H, G, F, E = 0, D, C, B = {readyState: 0, setRequestHeader: function (e, c) {
            E || (K[e.toLowerCase().replace(cV, cU)] = c);
            return this
        }, getAllResponseHeaders: function () {
            return E === 2 ? J : null
        }, getResponseHeader: function (e) {
            var f;
            if (E === 2) {
                if (!I) {
                    I = {};
                    while (f = aK.exec(J)) {
                        I[f[1].toLowerCase()] = f[2]
                    }
                }
                f = I[e.toLowerCase()]
            }
            return f === aP ? null : f
        }, overrideMimeType: function (c) {
            E || (R.mimeType = c);
            return this
        }, abort: function (c) {
            c = c || "abort", H && H.abort(c), A(0, c);
            return this
        }};
        O.promise(B), B.success = B.done, B.error = B.fail, B.complete = N.done, B.statusCode = function (e) {
            if (e) {
                var c;
                if (E < 2) {
                    for (c in e) {
                        M[c] = [M[c], e[c]]
                    }
                } else {
                    c = e[B.status], B.then(c, c)
                }
            }
            return this
        }, R.url = ((T || R.url) + "").replace(aM, "").replace(aC, cO[1] + "//"), R.dataTypes = aL.trim(R.dataType || "*").toLowerCase().split(cX), R.crossDomain == null && (F = cT.exec(R.url.toLowerCase()), R.crossDomain = F && (F[1] != cO[1] || F[2] != cO[2] || (F[3] || (F[1] === "http:" ? 80 : 443)) != (cO[3] || (cO[1] === "http:" ? 80 : 443)))), R.data && R.processData && typeof R.data !== "string" && (R.data = aL.param(R.data, R.traditional)), cL(cR, R, S, B);
        if (E === 2) {
            return !1
        }
        D = R.global, R.type = R.type.toUpperCase(), R.hasContent = !aE.test(R.type), D && aL.active++ === 0 && aL.event.trigger("ajaxStart");
        if (!R.hasContent) {
            R.data && (R.url += (c0.test(R.url) ? "&" : "?") + R.data), L = R.url;
            if (R.cache === !1) {
                var z = aL.now(), d = R.url.replace(cW, "$1_=" + z);
                R.url = d + (d === R.url ? (c0.test(R.url) ? "&" : "?") + "_=" + z : "")
            }
        }
        if (R.data && R.hasContent && R.contentType !== !1 || S.contentType) {
            K["Content-Type"] = R.contentType
        }
        R.ifModified && (L = L || R.url, aL.lastModified[L] && (K["If-Modified-Since"] = aL.lastModified[L]), aL.etag[L] && (K["If-None-Match"] = aL.etag[L])), K.Accept = R.dataTypes[0] && R.accepts[R.dataTypes[0]] ? R.accepts[R.dataTypes[0]] + (R.dataTypes[0] !== "*" ? ", */*; q=0.01" : "") : R.accepts["*"];
        for (C in R.headers) {
            B.setRequestHeader(C, R.headers[C])
        }
        if (R.beforeSend && (R.beforeSend.call(Q, B, R) === !1 || E === 2)) {
            B.abort();
            return !1
        }
        for (C in {success: 1, error: 1, complete: 1}) {
            B[C](R[C])
        }
        H = cL(cQ, R, S, B);
        if (H) {
            B.readyState = 1, D && P.trigger("ajaxSend", [B, R]), R.async && R.timeout > 0 && (G = setTimeout(function () {
                B.abort("timeout")
            }, R.timeout));
            try {
                E = 1, H.send(K, A)
            } catch (b) {
                status < 2 ? A(-1, b) : aL.error(b)
            }
        } else {
            A(-1, "No Transport")
        }
        return B
    }, param: function (b, k) {
        var j = [], h = function (e, c) {
            c = aL.isFunction(c) ? c() : c, j[j.length] = encodeURIComponent(e) + "=" + encodeURIComponent(c)
        };
        k === aP && (k = aL.ajaxSettings.traditional);
        if (aL.isArray(b) || b.jquery && !aL.isPlainObject(b)) {
            aL.each(b, function () {
                h(this.name, this.value)
            })
        } else {
            for (var d in b) {
                cK(d, b[d], k, h)
            }
        }
        return j.join("&").replace(aS, "+")
    }}), aL.extend({active: 0, lastModified: {}, etag: {}});
    var cF = aL.now(), cD = /(\=)\?(&|$)|\?\?/i;
    aL.ajaxSetup({jsonp: "callback", jsonpCallback: function () {
        return aL.expando + "_" + cF++
    }}), aL.ajaxPrefilter("json jsonp", function (v, u, t) {
        var s = typeof v.data === "string";
        if (v.dataTypes[0] === "jsonp" || u.jsonpCallback || u.jsonp != null || v.jsonp !== !1 && (cD.test(v.url) || s && cD.test(v.data))) {
            var r, q = v.jsonpCallback = aL.isFunction(v.jsonpCallback) ? v.jsonpCallback() : v.jsonpCallback, p = aR[q], o = v.url, n = v.data, d = "$1" + q + "$2", a = function () {
                aR[q] = p, r && aL.isFunction(p) && aR[q](r[0])
            };
            v.jsonp !== !1 && (o = o.replace(cD, d), v.url === o && (s && (n = n.replace(cD, d)), v.data === n && (o += (/\?/.test(o) ? "&" : "?") + v.jsonp + "=" + q))), v.url = o, v.data = n, aR[q] = function (b) {
                r = [b]
            }, t.then(a, a), v.converters["script json"] = function () {
                r || aL.error(q + " was not called");
                return r[0]
            }, v.dataTypes[0] = "json";
            return"script"
        }
    }), aL.ajaxSetup({accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"}, contents: {script: /javascript|ecmascript/}, converters: {"text script": function (b) {
        aL.globalEval(b);
        return b
    }}}), aL.ajaxPrefilter("script", function (b) {
        b.cache === aP && (b.cache = !1), b.crossDomain && (b.type = "GET", b.global = !1)
    }), aL.ajaxTransport("script", function (b) {
        if (b.crossDomain) {
            var f, c = aN.head || aN.getElementsByTagName("head")[0] || aN.documentElement;
            return{send: function (d, a) {
                f = aN.createElement("script"), f.async = "async", b.scriptCharset && (f.charset = b.scriptCharset), f.src = b.url, f.onload = f.onreadystatechange = function (e, g) {
                    if (!f.readyState || /loaded|complete/.test(f.readyState)) {
                        f.onload = f.onreadystatechange = null, c && f.parentNode && c.removeChild(f), f = aP, g || a(200, "success")
                    }
                }, c.insertBefore(f, c.firstChild)
            }, abort: function () {
                f && f.onload(0, 1)
            }}
        }
    });
    var cB = aL.now(), cz, cx;
    aL.ajaxSettings.xhr = aR.ActiveXObject ? function () {
        return !this.isLocal && cu() || c2()
    } : cu, cx = aL.ajaxSettings.xhr(), aL.support.ajax = !!cx, aL.support.cors = cx && "withCredentials" in cx, cx = aP, aL.support.ajax && aL.ajaxTransport(function (b) {
        if (!b.crossDomain || aL.support.cors) {
            var d;
            return{send: function (n, m) {
                var l = b.xhr(), k, c;
                b.username ? l.open(b.type, b.url, b.async, b.username, b.password) : l.open(b.type, b.url, b.async);
                if (b.xhrFields) {
                    for (c in b.xhrFields) {
                        l[c] = b.xhrFields[c]
                    }
                }
                b.mimeType && l.overrideMimeType && l.overrideMimeType(b.mimeType), !b.crossDomain && !n["X-Requested-With"] && (n["X-Requested-With"] = "XMLHttpRequest");
                try {
                    for (c in n) {
                        l.setRequestHeader(c, n[c])
                    }
                } catch (a) {
                }
                l.send(b.hasContent && b.data || null), d = function (v, u) {
                    var t, s, r, q, h;
                    try {
                        if (d && (u || l.readyState === 4)) {
                            d = aP, k && (l.onreadystatechange = aL.noop, delete cz[k]);
                            if (u) {
                                l.readyState !== 4 && l.abort()
                            } else {
                                t = l.status, r = l.getAllResponseHeaders(), q = {}, h = l.responseXML, h && h.documentElement && (q.xml = h), q.text = l.responseText;
                                try {
                                    s = l.statusText
                                } catch (g) {
                                    s = ""
                                }
                                t || !b.isLocal || b.crossDomain ? t === 1223 && (t = 204) : t = q.text ? 200 : 404
                            }
                        }
                    } catch (f) {
                        u || m(-1, f)
                    }
                    q && m(t, s, q, r)
                }, b.async && l.readyState !== 4 ? (cz || (cz = {}, cw()), k = cB++, l.onreadystatechange = cz[k] = d) : d()
            }, abort: function () {
                d && d(0, 1)
            }}
        }
    });
    var cn = {}, av = /^(?:toggle|show|hide)$/, at = /^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i, aq, ao = [
        ["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"],
        ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"],
        ["opacity"]
    ];
    aL.fn.extend({show: function (j, d, o) {
        var n, m;
        if (j || j === 0) {
            return this.animate(an("show", 3), j, d, o)
        }
        for (var l = 0, k = this.length; l < k; l++) {
            n = this[l], m = n.style.display, !aL._data(n, "olddisplay") && m === "none" && (m = n.style.display = ""), m === "" && aL.css(n, "display") === "none" && aL._data(n, "olddisplay", al(n.nodeName))
        }
        for (l = 0; l < k; l++) {
            n = this[l], m = n.style.display;
            if (m === "" || m === "none") {
                n.style.display = aL._data(n, "olddisplay") || ""
            }
        }
        return this
    }, hide: function (h, d, m) {
        if (h || h === 0) {
            return this.animate(an("hide", 3), h, d, m)
        }
        for (var l = 0, k = this.length; l < k; l++) {
            var j = aL.css(this[l], "display");
            j !== "none" && !aL._data(this[l], "olddisplay") && aL._data(this[l], "olddisplay", j)
        }
        for (l = 0; l < k; l++) {
            this[l].style.display = "none"
        }
        return this
    }, _toggle: aL.fn.toggle, toggle: function (f, d, h) {
        var g = typeof f === "boolean";
        aL.isFunction(f) && aL.isFunction(d) ? this._toggle.apply(this, arguments) : f == null || g ? this.each(function () {
            var a = g ? f : aL(this).is(":hidden");
            aL(this)[a ? "show" : "hide"]()
        }) : this.animate(an("toggle", 3), f, d, h);
        return this
    }, fadeTo: function (f, e, h, g) {
        return this.filter(":hidden").css("opacity", 0).show().end().animate({opacity: e}, f, h, g)
    }, animate: function (g, d, k, j) {
        var h = aL.speed(d, k, j);
        if (aL.isEmptyObject(g)) {
            return this.each(h.complete)
        }
        return this[h.queue === !1 ? "each" : "queue"](function () {
            var a = aL.extend({}, h), p, o = this.nodeType === 1, n = o && aL(this).is(":hidden"), m = this;
            for (p in g) {
                var l = aL.camelCase(p);
                p !== l && (g[l] = g[p], delete g[p], p = l);
                if (g[p] === "hide" && n || g[p] === "show" && !n) {
                    return a.complete.call(this)
                }
                if (o && (p === "height" || p === "width")) {
                    a.overflow = [this.style.overflow, this.style.overflowX, this.style.overflowY];
                    if (aL.css(this, "display") === "inline" && aL.css(this, "float") === "none") {
                        if (aL.support.inlineBlockNeedsLayout) {
                            var f = al(this.nodeName);
                            f === "inline" ? this.style.display = "inline-block" : (this.style.display = "inline", this.style.zoom = 1)
                        } else {
                            this.style.display = "inline-block"
                        }
                    }
                }
                aL.isArray(g[p]) && ((a.specialEasing = a.specialEasing || {})[p] = g[p][1], g[p] = g[p][0])
            }
            a.overflow != null && (this.style.overflow = "hidden"), a.curAnim = aL.extend({}, g), aL.each(g, function (v, u) {
                var t = new aL.fx(m, a, v);
                if (av.test(u)) {
                    t[u === "toggle" ? n ? "show" : "hide" : u](g)
                } else {
                    var s = at.exec(u), r = t.cur();
                    if (s) {
                        var q = parseFloat(s[2]), b = s[3] || (aL.cssNumber[v] ? "" : "px");
                        b !== "px" && (aL.style(m, v, (q || 1) + b), r = (q || 1) / t.cur() * r, aL.style(m, v, r + b)), s[1] && (q = (s[1] === "-=" ? -1 : 1) * q + r), t.custom(r, q, b)
                    } else {
                        t.custom(r, u, "")
                    }
                }
            });
            return !0
        })
    }, stop: function (e, d) {
        var f = aL.timers;
        e && this.queue([]), this.each(function () {
            for (var b = f.length - 1; b >= 0; b--) {
                f[b].elem === this && (d && f[b](!0), f.splice(b, 1))
            }
        }), d || this.dequeue();
        return this
    }}), aL.each({slideDown: an("show", 1), slideUp: an("hide", 1), slideToggle: an("toggle", 1), fadeIn: {opacity: "show"}, fadeOut: {opacity: "hide"}, fadeToggle: {opacity: "toggle"}}, function (d, c) {
        aL.fn[d] = function (b, f, e) {
            return this.animate(c, b, f, e)
        }
    }), aL.extend({speed: function (f, d, h) {
        var g = f && typeof f === "object" ? aL.extend({}, f) : {complete: h || !h && d || aL.isFunction(f) && f, duration: f, easing: h && d || d && !aL.isFunction(d) && d};
        g.duration = aL.fx.off ? 0 : typeof g.duration === "number" ? g.duration : g.duration in aL.fx.speeds ? aL.fx.speeds[g.duration] : aL.fx.speeds._default, g.old = g.complete, g.complete = function () {
            g.queue !== !1 && aL(this).dequeue(), aL.isFunction(g.old) && g.old.call(this)
        };
        return g
    }, easing: {linear: function (f, e, h, g) {
        return h + g * f
    }, swing: function (f, e, h, g) {
        return(-Math.cos(f * Math.PI) / 2 + 0.5) * g + h
    }}, timers: [], fx: function (e, d, f) {
        this.options = d, this.elem = e, this.prop = f, d.orig || (d.orig = {})
    }}), aL.fx.prototype = {update: function () {
        this.options.step && this.options.step.call(this.elem, this.now, this), (aL.fx.step[this.prop] || aL.fx.step._default)(this)
    }, cur: function () {
        if (this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null)) {
            return this.elem[this.prop]
        }
        var d, c = aL.css(this.elem, this.prop);
        return isNaN(d = parseFloat(c)) ? !c || c === "auto" ? 0 : c : d
    }, custom: function (h, d, m) {
        function j(b) {
            return l.step(b)
        }

        var l = this, k = aL.fx;
        this.startTime = aL.now(), this.start = h, this.end = d, this.unit = m || this.unit || (aL.cssNumber[this.prop] ? "" : "px"), this.now = this.start, this.pos = this.state = 0, j.elem = this.elem, j() && aL.timers.push(j) && !aq && (aq = setInterval(k.tick, k.interval))
    }, show: function () {
        this.options.orig[this.prop] = aL.style(this.elem, this.prop), this.options.show = !0, this.custom(this.prop === "width" || this.prop === "height" ? 1 : 0, this.cur()), aL(this.elem).show()
    }, hide: function () {
        this.options.orig[this.prop] = aL.style(this.elem, this.prop), this.options.hide = !0, this.custom(this.cur(), 0)
    }, step: function (t) {
        var s = aL.now(), r = !0;
        if (t || s >= this.options.duration + this.startTime) {
            this.now = this.end, this.pos = this.state = 1, this.update(), this.options.curAnim[this.prop] = !0;
            for (var q in this.options.curAnim) {
                this.options.curAnim[q] !== !0 && (r = !1)
            }
            if (r) {
                if (this.options.overflow != null && !aL.support.shrinkWrapBlocks) {
                    var p = this.elem, o = this.options;
                    aL.each(["", "X", "Y"], function (e, c) {
                        p.style["overflow" + c] = o.overflow[e]
                    })
                }
                this.options.hide && aL(this.elem).hide();
                if (this.options.hide || this.options.show) {
                    for (var n in this.options.curAnim) {
                        aL.style(this.elem, n, this.options.orig[n])
                    }
                }
                this.options.complete.call(this.elem)
            }
            return !1
        }
        var m = s - this.startTime;
        this.state = m / this.options.duration;
        var l = this.options.specialEasing && this.options.specialEasing[this.prop], d = this.options.easing || (aL.easing.swing ? "swing" : "linear");
        this.pos = aL.easing[l || d](this.state, m, 0, 1, this.options.duration), this.now = this.start + (this.end - this.start) * this.pos, this.update();
        return !0
    }}, aL.extend(aL.fx, {tick: function () {
        var d = aL.timers;
        for (var c = 0; c < d.length; c++) {
            d[c]() || d.splice(c--, 1)
        }
        d.length || aL.fx.stop()
    }, interval: 13, stop: function () {
        clearInterval(aq), aq = null
    }, speeds: {slow: 600, fast: 200, _default: 400}, step: {opacity: function (b) {
        aL.style(b.elem, "opacity", b.now)
    }, _default: function (b) {
        b.elem.style && b.elem.style[b.prop] != null ? b.elem.style[b.prop] = (b.prop === "width" || b.prop === "height" ? Math.max(0, b.now) : b.now) + b.unit : b.elem[b.prop] = b.now
    }}}), aL.expr && aL.expr.filters && (aL.expr.filters.animated = function (b) {
        return aL.grep(aL.timers,function (a) {
            return b === a.elem
        }).length
    });
    var ai = /^t(?:able|d|h)$/i, ag = /^(?:body|html)$/i;
    "getBoundingClientRect" in aN.documentElement ? aL.fn.offset = function (B) {
        var A = this[0], z;
        if (B) {
            return this.each(function (a) {
                aL.offset.setOffset(this, B, a)
            })
        }
        if (!A || !A.ownerDocument) {
            return null
        }
        if (A === A.ownerDocument.body) {
            return aL.offset.bodyOffset(A)
        }
        try {
            z = A.getBoundingClientRect()
        } catch (y) {
        }
        var x = A.ownerDocument, w = x.documentElement;
        if (!z || !aL.contains(w, A)) {
            return z ? {top: z.top, left: z.left} : {top: 0, left: 0}
        }
        var v = x.body, u = af(x), t = w.clientTop || v.clientTop || 0, s = w.clientLeft || v.clientLeft || 0, r = u.pageYOffset || aL.support.boxModel && w.scrollTop || v.scrollTop, q = u.pageXOffset || aL.support.boxModel && w.scrollLeft || v.scrollLeft, p = z.top + r - t, d = z.left + q - s;
        return{top: p, left: d}
    } : aL.fn.offset = function (x) {
        var w = this[0];
        if (x) {
            return this.each(function (a) {
                aL.offset.setOffset(this, x, a)
            })
        }
        if (!w || !w.ownerDocument) {
            return null
        }
        if (w === w.ownerDocument.body) {
            return aL.offset.bodyOffset(w)
        }
        aL.offset.initialize();
        var v, u = w.offsetParent, t = w, s = w.ownerDocument, r = s.documentElement, q = s.body, p = s.defaultView, o = p ? p.getComputedStyle(w, null) : w.currentStyle, n = w.offsetTop, d = w.offsetLeft;
        while ((w = w.parentNode) && w !== q && w !== r) {
            if (aL.offset.supportsFixedPosition && o.position === "fixed") {
                break
            }
            v = p ? p.getComputedStyle(w, null) : w.currentStyle, n -= w.scrollTop, d -= w.scrollLeft, w === u && (n += w.offsetTop, d += w.offsetLeft, aL.offset.doesNotAddBorder && (!aL.offset.doesAddBorderForTableAndCells || !ai.test(w.nodeName)) && (n += parseFloat(v.borderTopWidth) || 0, d += parseFloat(v.borderLeftWidth) || 0), t = u, u = w.offsetParent), aL.offset.subtractsBorderForOverflowNotVisible && v.overflow !== "visible" && (n += parseFloat(v.borderTopWidth) || 0, d += parseFloat(v.borderLeftWidth) || 0), o = v
        }
        if (o.position === "relative" || o.position === "static") {
            n += q.offsetTop, d += q.offsetLeft
        }
        aL.offset.supportsFixedPosition && o.position === "fixed" && (n += Math.max(r.scrollTop, q.scrollTop), d += Math.max(r.scrollLeft, q.scrollLeft));
        return{top: n, left: d}
    }, aL.offset = {initialize: function () {
        var d = aN.body, c = aN.createElement("div"), p, o, n, m, l = parseFloat(aL.css(d, "marginTop")) || 0, k = "<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";
        aL.extend(c.style, {position: "absolute", top: 0, left: 0, margin: 0, border: 0, width: "1px", height: "1px", visibility: "hidden"}), c.innerHTML = k, d.insertBefore(c, d.firstChild), p = c.firstChild, o = p.firstChild, m = p.nextSibling.firstChild.firstChild, this.doesNotAddBorder = o.offsetTop !== 5, this.doesAddBorderForTableAndCells = m.offsetTop === 5, o.style.position = "fixed", o.style.top = "20px", this.supportsFixedPosition = o.offsetTop === 20 || o.offsetTop === 15, o.style.position = o.style.top = "", p.style.overflow = "hidden", p.style.position = "relative", this.subtractsBorderForOverflowNotVisible = o.offsetTop === -5, this.doesNotIncludeMarginInBodyOffset = d.offsetTop !== l, d.removeChild(c), aL.offset.initialize = aL.noop
    }, bodyOffset: function (e) {
        var d = e.offsetTop, f = e.offsetLeft;
        aL.offset.initialize(), aL.offset.doesNotIncludeMarginInBodyOffset && (d += parseFloat(aL.css(e, "marginTop")) || 0, f += parseFloat(aL.css(e, "marginLeft")) || 0);
        return{top: d, left: f}
    }, setOffset: function (z, y, x) {
        var w = aL.css(z, "position");
        w === "static" && (z.style.position = "relative");
        var v = aL(z), u = v.offset(), t = aL.css(z, "top"), s = aL.css(z, "left"), r = (w === "absolute" || w === "fixed") && aL.inArray("auto", [t, s]) > -1, q = {}, p = {}, o, d;
        r && (p = v.position()), o = r ? p.top : parseInt(t, 10) || 0, d = r ? p.left : parseInt(s, 10) || 0, aL.isFunction(y) && (y = y.call(z, x, u)), y.top != null && (q.top = y.top - u.top + o), y.left != null && (q.left = y.left - u.left + d), "using" in y ? y.using.call(z, q) : v.css(q)
    }}, aL.fn.extend({position: function () {
        if (!this[0]) {
            return null
        }
        var f = this[0], d = this.offsetParent(), h = this.offset(), g = ag.test(d[0].nodeName) ? {top: 0, left: 0} : d.offset();
        h.top -= parseFloat(aL.css(f, "marginTop")) || 0, h.left -= parseFloat(aL.css(f, "marginLeft")) || 0, g.top += parseFloat(aL.css(d[0], "borderTopWidth")) || 0, g.left += parseFloat(aL.css(d[0], "borderLeftWidth")) || 0;
        return{top: h.top - g.top, left: h.left - g.left}
    }, offsetParent: function () {
        return this.map(function () {
            var b = this.offsetParent || aN.body;
            while (b && (!ag.test(b.nodeName) && aL.css(b, "position") === "static")) {
                b = b.offsetParent
            }
            return b
        })
    }}), aL.each(["Left", "Top"], function (b, f) {
        var d = "scroll" + f;
        aL.fn[d] = function (h) {
            var e = this[0], a;
            if (!e) {
                return null
            }
            if (h !== aP) {
                return this.each(function () {
                    a = af(this), a ? a.scrollTo(b ? aL(a).scrollLeft() : h, b ? h : aL(a).scrollTop()) : this[d] = h
                })
            }
            a = af(e);
            return a ? "pageXOffset" in a ? a[b ? "pageYOffset" : "pageXOffset"] : aL.support.boxModel && a.document.documentElement[d] || a.document.body[d] : e[d]
        }
    }), aL.each(["Height", "Width"], function (b, f) {
        var d = f.toLowerCase();
        aL.fn["inner" + f] = function () {
            return this[0] ? parseFloat(aL.css(this[0], d, "padding")) : null
        }, aL.fn["outer" + f] = function (c) {
            return this[0] ? parseFloat(aL.css(this[0], d, c ? "margin" : "border")) : null
        }, aL.fn[d] = function (c) {
            var l = this[0];
            if (!l) {
                return c == null ? null : this
            }
            if (aL.isFunction(c)) {
                return this.each(function (a) {
                    var g = aL(this);
                    g[d](c.call(this, a, g[d]()))
                })
            }
            if (aL.isWindow(l)) {
                var k = l.document.documentElement["client" + f];
                return l.document.compatMode === "CSS1Compat" && k || l.document.body["client" + f] || k
            }
            if (l.nodeType === 9) {
                return Math.max(l.documentElement["client" + f], l.body["scroll" + f], l.documentElement["scroll" + f], l.body["offset" + f], l.documentElement["offset" + f])
            }
            if (c === aP) {
                var j = aL.css(l, d), e = parseFloat(j);
                return aL.isNaN(e) ? j : e
            }
            return this.css(d, typeof c === "string" ? c : c + "px")
        }
    }), aR.jQuery = aR.$ = aL
})(window);
jQuery.cookie = function (b, j, m) {
    if (typeof j != "undefined") {
        m = m || {};
        if (j === null) {
            j = "";
            m.expires = -1
        }
        var e = "";
        if (m.expires && (typeof m.expires == "number" || m.expires.toUTCString)) {
            var f;
            if (typeof m.expires == "number") {
                f = new Date();
                f.setTime(f.getTime() + (m.expires * 24 * 60 * 60 * 1000))
            } else {
                f = m.expires
            }
            e = "; expires=" + f.toUTCString()
        }
        var l = m.path ? "; path=" + (m.path) : "";
        var g = m.domain ? "; domain=" + (m.domain) : "";
        var a = m.secure ? "; secure" : "";
        document.cookie = [b, "=", encodeURIComponent(j), e, l, g, a].join("")
    } else {
        var d = null;
        if (document.cookie && document.cookie != "") {
            var k = document.cookie.split(";");
            for (var h = 0; h < k.length; h++) {
                var c = jQuery.trim(k[h]);
                if (c.substring(0, b.length + 1) == (b + "=")) {
                    d = decodeURIComponent(c.substring(b.length + 1));
                    break
                }
            }
        }
        return d
    }
};
(function (b) {
    b.hotkeys = {version: "0.8", specialKeys: {8: "backspace", 9: "tab", 13: "return", 16: "shift", 17: "ctrl", 18: "alt", 19: "pause", 20: "capslock", 27: "esc", 32: "space", 33: "pageup", 34: "pagedown", 35: "end", 36: "home", 37: "left", 38: "up", 39: "right", 40: "down", 45: "insert", 46: "del", 96: "0", 97: "1", 98: "2", 99: "3", 100: "4", 101: "5", 102: "6", 103: "7", 104: "8", 105: "9", 106: "*", 107: "+", 109: "-", 110: ".", 111: "/", 112: "f1", 113: "f2", 114: "f3", 115: "f4", 116: "f5", 117: "f6", 118: "f7", 119: "f8", 120: "f9", 121: "f10", 122: "f11", 123: "f12", 144: "numlock", 145: "scroll", 191: "/", 224: "meta"}, shiftNums: {"`": "~", "1": "!", "2": "@", "3": "#", "4": "$", "5": "%", "6": "^", "7": "&", "8": "*", "9": "(", "0": ")", "-": "_", "=": "+", ";": ": ", "'": '"', ",": "<", ".": ">", "/": "?", "\\": "|"}};
    function a(d) {
        if (typeof d.data !== "string") {
            return
        }
        var c = d.handler, e = d.data.toLowerCase().split(" ");
        d.handler = function (n) {
            if (this !== n.target && (/textarea|select/i.test(n.target.nodeName) || n.target.type === "text")) {
                return
            }
            var h = n.type !== "keypress" && b.hotkeys.specialKeys[n.which], o = String.fromCharCode(n.which).toLowerCase(), k, m = "", g = {};
            if (n.altKey && h !== "alt") {
                m += "alt+"
            }
            if (n.ctrlKey && h !== "ctrl") {
                m += "ctrl+"
            }
            if (n.metaKey && !n.ctrlKey && h !== "meta") {
                m += "meta+"
            }
            if (n.shiftKey && h !== "shift") {
                m += "shift+"
            }
            if (h) {
                g[m + h] = true
            } else {
                g[m + o] = true;
                g[m + b.hotkeys.shiftNums[o]] = true;
                if (m === "shift+") {
                    g[b.hotkeys.shiftNums[o]] = true
                }
            }
            for (var j = 0, f = e.length; j < f; j++) {
                if (g[e[j]]) {
                    return c.apply(this, arguments)
                }
            }
        }
    }

    b.each(["keydown", "keyup", "keypress"], function () {
        b.event.special[this] = {add: a}
    })
})(jQuery);
(function (a) {
    a.fn.bgiframe = (a.browser.msie && /msie 6\.0/i.test(navigator.userAgent) ? function (d) {
        d = a.extend({top: "auto", left: "auto", width: "auto", height: "auto", opacity: true, src: "javascript:false;"}, d);
        var c = '<iframe class="bgiframe"frameborder="0"tabindex="-1"src="' + d.src + '"style="display:block;position:absolute;z-index:-1;' + (d.opacity !== false ? "filter:Alpha(Opacity='0');" : "") + "top:" + (d.top == "auto" ? "expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+'px')" : b(d.top)) + ";left:" + (d.left == "auto" ? "expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+'px')" : b(d.left)) + ";width:" + (d.width == "auto" ? "expression(this.parentNode.offsetWidth+'px')" : b(d.width)) + ";height:" + (d.height == "auto" ? "expression(this.parentNode.offsetHeight+'px')" : b(d.height)) + ';"/>';
        return this.each(function () {
            if (a(this).children("iframe.bgiframe").length === 0) {
                this.insertBefore(document.createElement(c), this.firstChild)
            }
        })
    } : function () {
        return this
    });
    a.fn.bgIframe = a.fn.bgiframe;
    function b(c) {
        return c && c.constructor === Number ? c + "px" : c
    }
})(jQuery);
function loadFixedCSS() {
    cssFile = "";
    if ($.browser.msie) {
        cssFile = $.browser.version == "6.0" ? config.themeRoot + "/browser/ie.6.css" : config.themeRoot + "browser/ie.css"
    } else {
        if ($.browser.mozilla) {
            cssFile = config.themeRoot + "/browser/firefox.css"
        } else {
            if ($.browser.opera) {
                cssFile = config.themeRoot + "/browser/opera.css"
            } else {
                if ($.browser.safari) {
                    cssFile = config.themeRoot + "/browser/safari.css"
                } else {
                    if ($.browser.chrome) {
                        cssFile = config.themeRoot + "/browser/chrome.css"
                    }
                }
            }
        }
    }
    if (cssFile != "") {
        $("<link>").appendTo($("head")).attr({type: "text/css", rel: "stylesheet"}).attr("href", cssFile)
    }
}
function createLink(d, b, e, a, c) {
    if (!a) {
        a = config.defaultView
    }
    if (!c) {
        c = false
    }
    if (e) {
        e = e.split("&");
        for (i = 0; i < e.length; i++) {
            e[i] = e[i].split("=")
        }
    }
    if (config.requestType == "PATH_INFO") {
        link = config.webRoot + d + config.requestFix + b;
        if (e) {
            if (config.pathType == "full") {
                for (i = 0; i < e.length; i++) {
                    link += config.requestFix + e[i][0] + config.requestFix + e[i][1]
                }
            } else {
                for (i = 0; i < e.length; i++) {
                    link += config.requestFix + e[i][1]
                }
            }
        }
        link += "." + a
    } else {
        link = config.router + "?" + config.moduleVar + "=" + d + "&" + config.methodVar + "=" + b + "&" + config.viewVar + "=" + a;
        if (e) {
            for (i = 0; i < e.length; i++) {
                link += "&" + e[i][0] + "=" + e[i][1]
            }
        }
    }
    if (f == "yes" || c) {
        var f = config.requestType == "PATH_INFO" ? "?onlybody=yes" : "&onlybody=yes";
        link = link + f
    }
    return link
}
function shortcut() {
    objectType = $("#searchType").attr("value");
    objectValue = $("#searchQuery").attr("value");
    if (objectType && objectValue) {
        location.href = createLink(objectType, "view", "id=" + objectValue)
    }
}
function setProductSwitcher() {
    productMode = $.cookie("productMode");
    if (!productMode) {
        productMode = "noclosed"
    }
    if (productMode == "all") {
        $("#productID").append($("<option value='noclosed' id='switcher'>" + config.lblHideClosed + "</option>"))
    } else {
        $("#productID").append($("<option value='all' id='switcher'>" + config.lblShowAll + "</option>"))
    }
}
function switchProduct(c, b, d, a) {
    if (isNaN(c)) {
        $.cookie("productMode", c, {expires: config.cookieLife, path: config.webRoot});
        c = 0
    }
    if (b == "product" || b == "roadmap" || b == "bug" || b == "testcase" || b == "testtask" || b == "story") {
        if (b == "product" && d == "project") {
            link = createLink(b, d, "status=all&productID=" + c)
        } else {
            link = createLink(b, d, "productID=" + c)
        }
    } else {
        if (b == "productplan" || b == "release") {
            if (d != "browse" && d != "create") {
                d = "browse"
            }
            link = createLink(b, d, "productID=" + c)
        } else {
            if (b == "tree") {
                link = createLink(b, d, "productID=" + c + "&type=" + a)
            }
        }
    }
    location.href = link
}
function switchDocLib(c, b, d, a) {
    if (b == "doc") {
        if (d != "view" && d != "edit") {
            link = createLink(b, d, "rootID=" + c)
        } else {
            link = createLink("doc", "browse")
        }
    } else {
        if (b == "tree") {
            link = createLink(b, d, "rootID=" + c + "&type=" + a)
        }
    }
    location.href = link
}
function saveProduct() {
    if ($("#productID")) {
        $.cookie("lastProduct", $("#productID").val(), {expires: config.cookieLife, path: config.webRoot})
    }
}
function setProjectSwitcher() {
    projectMode = $.cookie("projectMode");
    if (!projectMode) {
        projectMode = "noclosed"
    }
    if (projectMode == "all") {
        $("#projectID").append($("<option value='noclosed' id='switcher'>" + config.lblHideClosed + "</option>"))
    } else {
        $("#projectID").append($("<option value='all' id='switcher'>" + config.lblShowAll + "</option>"))
    }
}
function switchProject(b, c, d, a) {
    if (isNaN(b)) {
        $.cookie("projectMode", b, {expires: config.cookieLife, path: config.webRoot});
        b = 0
    }
    if (c == "task" && (d == "view" || d == "edit" || d == "batchedit")) {
        c = "project";
        d = "task"
    }
    if (c == "build" && d == "edit") {
        c = "project";
        d = "build"
    }
    if (c == "project" && d == "create") {
        return
    }
    link = createLink(c, d, "projectID=" + b);
    if (a != "") {
        link = createLink(c, d, "projectID=" + b + "&type=" + a)
    }
    location.href = link
}
function saveProject() {
    if ($("#projectID")) {
        $.cookie("lastProject", $("#projectID").val(), {expires: config.cookieLife, path: config.webRoot})
    }
}
function setPing() {
    $("#hiddenwin").attr("src", createLink("misc", "ping"))
}
function setRequiredFields() {
    if (!config.requiredFields) {
        return false
    }
    requiredFields = config.requiredFields.split(",");
    for (i = 0; i < requiredFields.length; i++) {
        $("#" + requiredFields[i]).after('<span class="star"> * </span>')
    }
}
function setHelpLink() {
    if (!$.cookie("help")) {
        $.cookie("help", "off", {expires: config.cookieLife, path: config.webRoot})
    }
    className = $.cookie("help") == "off" ? "hidden" : "";
    $("form input[id], form select[id], form textarea[id]").each(function () {
        if ($(this).attr("type") == "hidden" || $(this).attr("type") == "file") {
            return
        }
        currentFieldName = $(this).attr("name") ? $(this).attr("name") : $(this).attr("id");
        if (currentFieldName == "submit" || currentFieldName == "reset") {
            return
        }
        if (currentFieldName.indexOf("[") > 0) {
            currentFieldName = currentFieldName.substr(0, currentFieldName.indexOf("["))
        }
        currentFieldName = currentFieldName.toLowerCase();
        helpLink = createLink("help", "field", "module=" + config.currentModule + "&method=" + config.currentMethod + "&field=" + currentFieldName);
        $(this).after(' <a class="helplink ' + className + '" href=' + helpLink + ' target="_blank">?</a> ')
    });
    if ($("a.helplink").size()) {
        $("a.helplink").colorbox({width: 600, height: 240, iframe: true, transition: "none", scrolling: false})
    }
}
function setPlaceholder() {
    if (typeof(holders) != "undefined") {
        for (var a in holders) {
            $("#" + a).attr("placeholder", holders[a])
        }
    }
}
function toggleHelpLink() {
    $(".helplink").toggle();
    if ($.cookie("help") == "off") {
        return $.cookie("help", "on", {expires: config.cookieLife, path: config.webRoot})
    }
    if ($.cookie("help") == "on") {
        return $.cookie("help", "off", {expires: config.cookieLife, path: config.webRoot})
    }
}
function selectLang(a) {
    $.cookie("lang", a, {expires: config.cookieLife, path: config.webRoot});
    location.href = removeAnchor(location.href)
}
function selectTheme(a) {
    $.cookie("theme", a, {expires: config.cookieLife, path: config.webRoot});
    location.href = removeAnchor(location.href)
}
function removeAnchor(a) {
    pos = a.indexOf("#");
    if (pos > 0) {
        return a.substring(0, pos)
    }
    return a
}
function saveWindowSize() {
    width = $(window).width();
    height = $(window).height();
    $.cookie("windowWidth", width);
    $.cookie("windowHeight", height)
}
function setOuterBox() {
    var c = window.screen.width;
    var b = $(window).height();
    var f = $("#header").height();
    var a = $("#modulemenu").parent().parent().height();
    var d = $("#footer").height() + 15;
    var e = b - f - d - a - 37;
    if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
        e = b - f - d - 98
    }
    if ($.browser.msie && ($.browser.version == "6.0")) {
        $(".outer").css("height", e)
    }
    $(".outer").css("min-height", e);
    if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
        c -= 49;
        $("#wrap").width(c)
    }
}
function setAbout() {
    if ($("a.about").size()) {
        $("a.about").colorbox({width: 900, height: 330, iframe: true, transition: "none", scrolling: false})
    }
}
function setDebugWin(a) {
    if ($.browser.msie && $(".debugwin").size() == 1) {
        var b = $(".debugwin")[0].contentWindow.document;
        $("body", b).append("<style>body{background:" + a + "}</style>")
    }
}
function setForm() {
    var a = false;
    $("form").submit(function () {
        submitObj = $(this).find(":submit");
        if ($(submitObj).size() == 1) {
            submitLabel = $(submitObj).attr("value");
            $(submitObj).attr("disabled", "disabled");
            $(submitObj).attr("value", config.submitting);
            $(submitObj).addClass("button-d");
            a = true
        }
    });
    $("body").click(function () {
        if (a) {
            $(submitObj).removeAttr("disabled");
            $(submitObj).attr("value", submitLabel);
            $(submitObj).removeClass("button-d")
        }
        a = false
    })
}
function setImageSize(b, a) {
    if (!a) {
        bodyWidth = $("body").width();
        a = bodyWidth - 450
    }
    $(".content img").each(function () {
        if ($(this).width() > a) {
            $(this).attr("width", a)
        }
    });
    $(b).wrap('<a href="' + $(b).attr("src") + '" target="_blank"></a>')
}
function setSubversionLink() {
    if ($(".svnlink").size()) {
        $(".svnlink").colorbox({width: 960, height: 600, iframe: true, transition: "elastic", speed: 350, scrolling: true})
    }
}
function setExport() {
    if ($(".export").size()) {
        $(".export").colorbox({width: 600, height: 200, iframe: true, transition: "none", scrolling: true})
    }
}
function setMailto(a, b) {
    if (!b) {
        return
    }
    link = createLink("user", "ajaxGetContactUsers", "listID=" + b);
    $.get(link, function (c) {
        $("#" + a).val(c)
    })
}
function setComment() {
    $("#commentBox").toggle();
    $(".ke-container").css("width", "100%");
    setTimeout(function () {
        $("#commentBox textarea").focus()
    }, 50)
}
function autoCheck() {
    $(".tablesorter tr :checkbox").click(function () {
        if ($(this).attr("checked")) {
            $(this).attr("checked", false)
        } else {
            $(this).attr("checked", true)
        }
        return
    });
    $(".tablesorter tr").click(function () {
        if (document.activeElement.type != "select-one" && document.activeElement.type != "text") {
            if ($(this).find(":checkbox").attr("checked")) {
                $(this).find(":checkbox").attr("checked", false)
            } else {
                $(this).find(":checkbox").attr("checked", true)
            }
        }
    })
}
function toggleSearch() {
    $("#bysearchTab").toggle(function () {
        if (browseType == "bymodule") {
            $("#treebox").addClass("hidden");
            $(".divider").addClass("hidden");
            $("#bymoduleTab").removeClass("active")
        } else {
            $("#" + browseType + "Tab").removeClass("active")
        }
        $("#bysearchTab").addClass("active");
        ajaxGetSearchForm();
        $("#querybox").removeClass("hidden")
    }, function () {
        if (browseType == "bymodule") {
            $("#treebox").removeClass("hidden");
            $(".divider").removeClass("hidden");
            $("#bymoduleTab").addClass("active")
        } else {
            $("#" + browseType + "Tab").addClass("active")
        }
        $("#bysearchTab").removeClass("active");
        $("#querybox").addClass("hidden")
    })
}
function ajaxGetSearchForm() {
    if ($("#querybox").html() == "") {
        $.get(createLink("search", "buildForm"), function (a) {
            $("#querybox").html(a)
        })
    }
}
function hideClearDataLink() {
    if (typeof showDemoUsers == "undefined" || !showDemoUsers) {
        $("#submenuclearData").addClass("hidden")
    }
}
function addItem(d, b) {
    ItemList = document.getElementById(d);
    Target = document.getElementById(b);
    for (var a = 0; a < ItemList.length; a++) {
        var c = ItemList.options[a];
        if (c.selected) {
            flag = true;
            for (var f = 0; f < Target.length; f++) {
                var e = Target.options[f];
                if (e.value == c.value) {
                    flag = false
                }
            }
            if (flag) {
                Target.options[Target.options.length] = new Option(c.text, c.value, 0, 0)
            }
        }
    }
}
function delItem(c) {
    ItemList = document.getElementById(c);
    for (var a = ItemList.length - 1; a >= 0; a--) {
        var b = ItemList.options[a];
        if (b.selected) {
            ItemList.options[a] = null
        }
    }
}
function upItem(c) {
    ItemList = document.getElementById(c);
    for (var a = 1; a < ItemList.length; a++) {
        var b = ItemList.options[a];
        if (b.selected) {
            tmpUpValue = ItemList.options[a - 1].value;
            tmpUpText = ItemList.options[a - 1].text;
            ItemList.options[a - 1].value = b.value;
            ItemList.options[a - 1].text = b.text;
            ItemList.options[a].value = tmpUpValue;
            ItemList.options[a].text = tmpUpText;
            ItemList.options[a - 1].selected = true;
            ItemList.options[a].selected = false;
            break
        }
    }
}
function downItem(c) {
    ItemList = document.getElementById(c);
    for (var a = 0; a < ItemList.length; a++) {
        var b = ItemList.options[a];
        if (b.selected) {
            tmpUpValue = ItemList.options[a + 1].value;
            tmpUpText = ItemList.options[a + 1].text;
            ItemList.options[a + 1].value = b.value;
            ItemList.options[a + 1].text = b.text;
            ItemList.options[a].value = tmpUpValue;
            ItemList.options[a].text = tmpUpText;
            ItemList.options[a + 1].selected = true;
            ItemList.options[a].selected = false;
            break
        }
    }
}
function selectItem(c) {
    ItemList = document.getElementById(c);
    for (var a = ItemList.length - 1; a >= 0; a--) {
        var b = ItemList.options[a];
        b.selected = true
    }
}
needPing = true;
$(document).ready(function () {
    loadFixedCSS();
    setForm();
    saveWindowSize();
    setDebugWin("white");
    setOuterBox();
    setRequiredFields();
    setPlaceholder();
    setProductSwitcher();
    setProjectSwitcher();
    saveProduct();
    saveProject();
    setAbout();
    setExport();
    setSubversionLink();
    autoCheck();
    toggleSearch();
    hideClearDataLink();
    $(window).resize(function () {
        saveWindowSize()
    });
    if (needPing) {
        setTimeout("setPing()", 1000 * 60)
    }
    $(".export").bind("click", function () {
        var a = "";
        $(":checkbox").each(function () {
            if ($(this).attr("checked")) {
                var b = parseInt($(this).val());
                if (b != 0) {
                    a = a + b + ","
                }
            }
        });
        if (a != "") {
            a = a.substring(0, a.length - 1)
        }
        $.cookie("checkedItem", a, {expires: config.cookieLife, path: config.webRoot})
    })
});
$(document).bind("keydown", "Ctrl+g", function (a) {
    $("#searchQuery").attr("value", "");
    $("#searchQuery").focus();
    a.stopPropagation();
    a.preventDefault();
    return false
});
$(document).bind("keydown", "left", function (a) {
    preLink = ($("#pre").attr("href"));
    if (typeof(preLink) != "undefined") {
        location.href = preLink
    }
});
$(document).bind("keydown", "right", function (a) {
    nextLink = ($("#next").attr("href"));
    if (typeof(nextLink) != "undefined") {
        location.href = nextLink
    }
});
(function (a2, a0, aZ) {
    function ab(h, f, b) {
        var a = a0.createElement(h);
        return f && (a.id = aW + f), b && (a.style.cssText = b), a2(a)
    }

    function aa(e) {
        var d = aD.length, f = (aj + e) % d;
        return f < 0 ? d + f : f
    }

    function aB(d, c) {
        return Math.round((/%/.test(d) ? (c === "x" ? aC.width() : aC.height()) / 100 : 1) * parseInt(d, 10))
    }

    function a5(b) {
        return ap.photo || /\.(gif|png|jpe?g|bmp|ico)((#|\?).*)?$/i.test(b)
    }

    function a6() {
        var a;
        ap = a2.extend({}, a2.data(ak, aX));
        for (a in ap) {
            a2.isFunction(ap[a]) && a.slice(0, 2) !== "on" && (ap[a] = ap[a].call(ak))
        }
        ap.rel = ap.rel || ak.rel || "nofollow", ap.href = ap.href || a2(ak).attr("href"), ap.title = ap.title || ak.title, typeof ap.href == "string" && (ap.href = a2.trim(ap.href))
    }

    function a4(a, d) {
        a2.event.trigger(a), d && d.call(ak)
    }

    function a3() {
        var h, f = aW + "Slideshow_", m = "click." + aW, l, k, j;
        ap.slideshow && aD[1] ? (l = function () {
            av.text(ap.slideshowStop).unbind(m).bind(aS,function () {
                if (aj < aD.length - 1 || ap.loop) {
                    h = setTimeout(ad.next, ap.slideshowSpeed)
                }
            }).bind(aT,function () {
                clearTimeout(h)
            }).one(m + " " + aR, k), aK.removeClass(f + "off").addClass(f + "on"), h = setTimeout(ad.next, ap.slideshowSpeed)
        }, k = function () {
            clearTimeout(h), av.text(ap.slideshowStart).unbind([aS, aT, aR, m].join(" ")).one(m, function () {
                ad.next(), l()
            }), aK.removeClass(f + "on").addClass(f + "off")
        }, ap.slideshowAuto ? l() : k()) : aK.removeClass(f + "off " + f + "on")
    }

    function a1(a) {
        if (!af) {
            ak = a, a6(), aD = a2(ak), aj = 0, ap.rel !== "nofollow" && (aD = a2("." + aV).filter(function () {
                var c = a2.data(this, aX).rel || this.rel;
                return c === ap.rel
            }), aj = aD.index(ak), aj === -1 && (aD = aD.add(ak), aj = aD.length - 1));
            if (!ah) {
                ah = ag = !0, aK.show();
                if (ap.returnFocus) {
                    try {
                        ak.blur(), a2(ak).one(aQ, function () {
                            try {
                                this.focus()
                            } catch (b) {
                            }
                        })
                    } catch (d) {
                    }
                }
                aL.css({opacity: +ap.opacity, cursor: ap.overlayClose ? "pointer" : "auto"}).show(), ap.w = aB(ap.initialWidth, "x"), ap.h = aB(ap.initialHeight, "y"), ad.position(), aN && aC.bind("resize." + aM + " scroll." + aM,function () {
                    aL.css({width: aC.width(), height: aC.height(), top: aC.scrollTop(), left: aC.scrollLeft()})
                }).trigger("resize." + aM), a4(aU, ap.onOpen), aq.add(ax).hide(), ar.html(ap.close).show()
            }
            ad.load(!0)
        }
    }

    var aY = {transition: "elastic", speed: 300, width: !1, initialWidth: "600", innerWidth: !1, maxWidth: !1, height: !1, initialHeight: "450", innerHeight: !1, maxHeight: !1, scalePhotos: !0, scrolling: !0, inline: !1, html: !1, iframe: !1, fastIframe: !0, photo: !1, href: !1, title: !1, rel: !1, opacity: 0.5, preloading: !0, current: "image {current} of {total}", previous: "previous", next: "next", close: "close", open: !1, returnFocus: !0, loop: !0, slideshow: !1, slideshowAuto: !0, slideshowSpeed: 2500, slideshowStart: "start slideshow", slideshowStop: "stop slideshow", onOpen: !1, onLoad: !1, onComplete: !1, onCleanup: !1, onClosed: !1, overlayClose: !0, escKey: !0, arrowKey: !0, top: !1, bottom: !1, left: !1, right: !1, fixed: !1, data: undefined}, aX = "colorbox", aW = "cbox", aV = aW + "Element", aU = aW + "_open", aT = aW + "_load", aS = aW + "_complete", aR = aW + "_cleanup", aQ = aW + "_closed", aP = aW + "_purge", aO = a2.browser.msie && !a2.support.opacity, aN = aO && a2.browser.version < 7, aM = aW + "_IE6", aL, aK, aJ, aI, aH, aG, aF, aE, aD, aC, aA, az, ay, ax, aw, av, au, at, ar, aq, ap, ao, an, am, al, ak, aj, ai, ah, ag, af, ae, ad, ac = "div";
    ad = a2.fn[aX] = a2[aX] = function (a, e) {
        var d = this;
        a = a || {}, ad.init();
        if (!d[0]) {
            if (d.selector) {
                return d
            }
            d = a2("<a/>"), a.open = !0
        }
        return e && (a.onComplete = e), d.each(function () {
            a2.data(this, aX, a2.extend({}, a2.data(this, aX) || aY, a)), a2(this).addClass(aV)
        }), (a2.isFunction(a.open) && a.open.call(d) || a.open) && a1(d[0]), d
    }, ad.init = function () {
        if (!aK) {
            if (!a2("body")[0]) {
                a2(ad.init);
                return
            }
            aC = a2(aZ), aK = ab(ac).attr({id: aX, "class": aO ? aW + (aN ? "IE6" : "IE") : ""}), aL = ab(ac, "Overlay", aN ? "position:absolute" : "").hide(), aJ = ab(ac, "Wrapper"), aI = ab(ac, "Content").append(aA = ab(ac, "LoadedContent", "width:0; height:0; overflow:hidden"), ay = ab(ac, "LoadingOverlay").add(ab(ac, "LoadingGraphic")), ax = ab(ac, "Title"), aw = ab(ac, "Current"), au = ab(ac, "Next"), at = ab(ac, "Previous"), av = ab(ac, "Slideshow").bind(aU, a3), ar = ab(ac, "Close")), aJ.append(ab(ac).append(ab(ac, "TopLeft"), aH = ab(ac, "TopCenter"), ab(ac, "TopRight")), ab(ac, !1, "clear:left").append(aG = ab(ac, "MiddleLeft"), aI, aF = ab(ac, "MiddleRight")), ab(ac, !1, "clear:left").append(ab(ac, "BottomLeft"), aE = ab(ac, "BottomCenter"), ab(ac, "BottomRight"))).find("div div").css({"float": "left"}), az = ab(ac, !1, "position:absolute; width:9999px; visibility:hidden; display:none"), a2("body").prepend(aL, aK.append(aJ, az)), ao = aH.height() + aE.height() + aI.outerHeight(!0) - aI.height(), an = aG.width() + aF.width() + aI.outerWidth(!0) - aI.width(), am = aA.outerHeight(!0), al = aA.outerWidth(!0), aK.css({"padding-right": an}).hide(), au.click(function () {
                ad.next()
            }), at.click(function () {
                ad.prev()
            }), ar.click(function () {
                ad.close()
            }), aq = au.add(at).add(aw).add(av), aL.click(function () {
                ap.overlayClose && ad.close()
            }), a2(a0).bind("keydown." + aW, function (d) {
                var c = d.keyCode;
                ah && ap.escKey && c === 27 && (d.preventDefault(), ad.close()), ah && ap.arrowKey && aD[1] && (c === 37 ? (d.preventDefault(), at.click()) : c === 39 && (d.preventDefault(), au.click()))
            })
        }
    }, ad.remove = function () {
        aK.add(aL).remove(), aK = null, a2("." + aV).removeData(aX).removeClass(aV)
    }, ad.position = function (h, f) {
        function j(b) {
            aH[0].style.width = aE[0].style.width = aI[0].style.width = b.style.width, ay[0].style.height = ay[1].style.height = aI[0].style.height = aG[0].style.height = aF[0].style.height = b.style.height
        }

        var m = 0, l = 0, k = aK.offset();
        aC.unbind("resize." + aW), aK.css({top: -99999, left: -99999}), ap.fixed && !aN ? aK.css({position: "fixed"}) : (m = aC.scrollTop(), l = aC.scrollLeft(), aK.css({position: "absolute"})), ap.right !== !1 ? l += Math.max(aC.width() - ap.w - al - an - aB(ap.right, "x"), 0) : ap.left !== !1 ? l += aB(ap.left, "x") : l += Math.round(Math.max(aC.width() - ap.w - al - an, 0) / 2), ap.bottom !== !1 ? m += Math.max(aC.height() - ap.h - am - ao - aB(ap.bottom, "y"), 0) : ap.top !== !1 ? m += aB(ap.top, "y") : m += Math.round(Math.max(aC.height() - ap.h - am - ao, 0) / 2), aK.css({top: k.top, left: k.left}), h = aK.width() === ap.w + al && aK.height() === ap.h + am ? 0 : h || 0, aJ[0].style.width = aJ[0].style.height = "9999px", aK.dequeue().animate({width: ap.w + al, height: ap.h + am, top: m, left: l}, {duration: h, complete: function () {
            j(this), ag = !1, aJ[0].style.width = ap.w + al + an + "px", aJ[0].style.height = ap.h + am + ao + "px", f && f(), setTimeout(function () {
                aC.bind("resize." + aW, ad.position)
            }, 1)
        }, step: function () {
            j(this)
        }})
    }, ad.resize = function (b) {
        ah && (b = b || {}, b.width && (ap.w = aB(b.width, "x") - al - an), b.innerWidth && (ap.w = aB(b.innerWidth, "x")), aA.css({width: ap.w}), b.height && (ap.h = aB(b.height, "y") - am - ao), b.innerHeight && (ap.h = aB(b.innerHeight, "y")), !b.innerHeight && !b.height && (aA.css({height: "auto"}), ap.h = aA.height()), aA.css({height: ap.h}), ad.position(ap.transition === "none" ? 0 : ap.speed))
    }, ad.prep = function (a) {
        function f() {
            return ap.w = ap.w || aA.width(), ap.w = ap.mw && ap.mw < ap.w ? ap.mw : ap.w, ap.w
        }

        function e() {
            return ap.h = ap.h || aA.height(), ap.h = ap.mh && ap.mh < ap.h ? ap.mh : ap.h, ap.h
        }

        if (!ah) {
            return
        }
        var k, j = ap.transition === "none" ? 0 : ap.speed;
        aA.remove(), aA = ab(ac, "LoadedContent").append(a), aA.hide().appendTo(az.show()).css({width: f(), overflow: ap.scrolling ? "auto" : "hidden"}).css({height: e()}).prependTo(aI), az.hide(), a2(ai).css({"float": "none"}), aN && a2("select").not(aK.find("select")).filter(function () {
            return this.style.visibility !== "hidden"
        }).css({visibility: "hidden"}).one(aR, function () {
            this.style.visibility = "inherit"
        }), k = function () {
            function d() {
                aO && aK[0].style.removeAttribute("filter")
            }

            var x, w, v = aD.length, u, t = "frameBorder", s = "allowTransparency", r, n, m;
            if (!ah) {
                return
            }
            r = function () {
                clearTimeout(ae), ay.hide(), a4(aS, ap.onComplete)
            }, aO && ai && aA.fadeIn(100), ax.html(ap.title).add(aA).show();
            if (v > 1) {
                typeof ap.current == "string" && aw.html(ap.current.replace("{current}", aj + 1).replace("{total}", v)).show(), au[ap.loop || aj < v - 1 ? "show" : "hide"]().html(ap.next), at[ap.loop || aj ? "show" : "hide"]().html(ap.previous), ap.slideshow && av.show();
                if (ap.preloading) {
                    x = [aa(-1), aa(1)];
                    while (w = aD[x.pop()]) {
                        n = a2.data(w, aX).href || w.href, a2.isFunction(n) && (n = n.call(w)), a5(n) && (m = new Image, m.src = n)
                    }
                }
            } else {
                aq.hide()
            }
            ap.iframe ? (u = ab("iframe")[0], t in u && (u[t] = 0), s in u && (u[s] = "true"), u.name = aW + +(new Date), ap.fastIframe ? r() : a2(u).one("load", r), u.src = ap.href, ap.scrolling || (u.scrolling = "no"), a2(u).addClass(aW + "Iframe").appendTo(aA).one(aP, function () {
                u.src = "//about:blank"
            })) : r(), ap.transition === "fade" ? aK.fadeTo(j, 1, d) : d()
        }, ap.transition === "fade" ? aK.fadeTo(j, 0, function () {
            ad.position(0, k)
        }) : ad.position(j, k)
    }, ad.load = function (a) {
        var h, g, f = ad.prep;
        ag = !0, ai = !1, ak = aD[aj], a || a6(), a4(aP), a4(aT, ap.onLoad), ap.h = ap.height ? aB(ap.height, "y") - am - ao : ap.innerHeight && aB(ap.innerHeight, "y"), ap.w = ap.width ? aB(ap.width, "x") - al - an : ap.innerWidth && aB(ap.innerWidth, "x"), ap.mw = ap.w, ap.mh = ap.h, ap.maxWidth && (ap.mw = aB(ap.maxWidth, "x") - al - an, ap.mw = ap.w && ap.w < ap.mw ? ap.w : ap.mw), ap.maxHeight && (ap.mh = aB(ap.maxHeight, "y") - am - ao, ap.mh = ap.h && ap.h < ap.mh ? ap.h : ap.mh), h = ap.href, ae = setTimeout(function () {
            ay.show()
        }, 100), ap.inline ? (ab(ac).hide().insertBefore(a2(h)[0]).one(aP, function () {
            a2(this).replaceWith(aA.children())
        }), f(a2(h))) : ap.iframe ? f(" ") : ap.html ? f(ap.html) : a5(h) ? (a2(ai = new Image).addClass(aW + "Photo").error(function () {
            ap.title = !1, f(ab(ac, "Error").text("This image could not be loaded"))
        }).load(function () {
            var b;
            ai.onload = null, ap.scalePhotos && (g = function () {
                ai.height -= ai.height * b, ai.width -= ai.width * b
            }, ap.mw && ai.width > ap.mw && (b = (ai.width - ap.mw) / ai.width, g()), ap.mh && ai.height > ap.mh && (b = (ai.height - ap.mh) / ai.height, g())), ap.h && (ai.style.marginTop = Math.max(ap.h - ai.height, 0) / 2 + "px"), aD[1] && (aj < aD.length - 1 || ap.loop) && (ai.style.cursor = "pointer", ai.onclick = function () {
                ad.next()
            }), aO && (ai.style.msInterpolationMode = "bicubic"), setTimeout(function () {
                f(ai)
            }, 1)
        }), setTimeout(function () {
            ai.src = h
        }, 1)) : h && az.load(h, ap.data, function (e, k, j) {
            f(k === "error" ? ab(ac, "Error").text("Request unsuccessful: " + j.statusText) : a2(this).contents())
        })
    }, ad.next = function () {
        !ag && aD[1] && (aj < aD.length - 1 || ap.loop) && (aj = aa(1), ad.load())
    }, ad.prev = function () {
        !ag && aD[1] && (aj || ap.loop) && (aj = aa(-1), ad.load())
    }, ad.close = function () {
        ah && !af && (af = !0, ah = !1, a4(aR, ap.onCleanup), aC.unbind("." + aW + " ." + aM), aL.fadeTo(200, 0), aK.stop().fadeTo(300, 0, function () {
            aK.add(aL).css({opacity: 1, cursor: "auto"}).hide(), a4(aP), aA.remove(), setTimeout(function () {
                af = !1, a4(aQ, ap.onClosed)
            }, 1)
        }))
    }, ad.element = function () {
        return a2(ak)
    }, ad.settings = aY, a2("." + aV, a0).live("click", function (b) {
        b.which > 1 || b.shiftKey || b.altKey || b.metaKey || (b.preventDefault(), a1(this))
    }), ad.init()
})(jQuery, document, this);
((function () {
    var b;
    b = function () {
        function c() {
            this.options_index = 0, this.parsed = []
        }

        return c.prototype.add_node = function (d) {
            return d.nodeName === "OPTGROUP" ? this.add_group(d) : this.add_option(d)
        }, c.prototype.add_group = function (j) {
            var h, o, n, m, l, k;
            h = this.parsed.length, this.parsed.push({array_index: h, group: !0, label: j.label, children: 0, disabled: j.disabled}), l = j.childNodes, k = [];
            for (n = 0, m = l.length; n < m; n++) {
                o = l[n], k.push(this.add_option(o, h, j.disabled))
            }
            return k
        }, c.prototype.add_option = function (e, d, f) {
            if (e.nodeName === "OPTION") {
                return e.text !== "" ? (d != null && (this.parsed[d].children += 1), this.parsed.push({array_index: this.parsed.length, options_index: this.options_index, value: e.value, text: e.text, html: e.innerHTML, selected: e.selected, disabled: f === !0 ? f : e.disabled, group_array_index: d, classes: e.className, style: e.style.cssText})) : this.parsed.push({array_index: this.parsed.length, options_index: this.options_index, empty: !0}), this.options_index += 1
            }
        }, c
    }(), b.select_to_array = function (a) {
        var m, l, k, j, h;
        l = new b, h = a.childNodes;
        for (k = 0, j = h.length; k < j; k++) {
            m = h[k], l.add_node(m)
        }
        return l.parsed
    }, this.SelectParser = b
})).call(this), function () {
    var d, c;
    c = this, d = function () {
        function b(f, e) {
            this.form_field = f, this.options = e != null ? e : {}, this.set_default_values(), this.is_multiple = this.form_field.multiple, this.set_default_text(), this.setup(), this.set_up_html(), this.register_observers(), this.finish_setup()
        }

        return b.prototype.set_default_values = function () {
            var e = this;
            return this.click_test_action = function (a) {
                return e.test_active_click(a)
            }, this.activate_action = function (a) {
                return e.activate_field(a)
            }, this.active_field = !1, this.mouse_on_container = !1, this.results_showing = !1, this.result_highlighted = null, this.result_single_selected = null, this.allow_single_deselect = this.options.allow_single_deselect != null && this.form_field.options[0] != null && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : !1, this.disable_search_threshold = this.options.disable_search_threshold || 0, this.search_contains = this.options.search_contains || !1, this.choices = 0, this.single_backstroke_delete = this.options.single_backstroke_delete || !1, this.max_selected_options = this.options.max_selected_options || Infinity
        }, b.prototype.set_default_text = function () {
            return this.form_field.getAttribute("data-placeholder") ? this.default_text = this.form_field.getAttribute("data-placeholder") : this.is_multiple ? this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || "Select Some Options" : this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || " ", this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || "No results match"
        }, b.prototype.mouse_enter = function () {
            return this.mouse_on_container = !0
        }, b.prototype.mouse_leave = function () {
            return this.mouse_on_container = !1
        }, b.prototype.input_focus = function (f) {
            var e = this;
            if (!this.active_field) {
                return setTimeout(function () {
                    return e.container_mousedown()
                }, 50)
            }
        }, b.prototype.input_blur = function (f) {
            var e = this;
            if (!this.mouse_on_container) {
                return this.active_field = !1, setTimeout(function () {
                    return e.blur_test()
                }, 100)
            }
        }, b.prototype.result_add_option = function (f) {
            var e, g;
            return f.disabled ? "" : (f.dom_id = this.container_id + "_o_" + f.array_index, e = f.selected && this.is_multiple ? [] : ["active-result"], f.selected && e.push("result-selected"), f.group_array_index != null && e.push("group-option"), f.classes !== "" && e.push(f.classes), g = f.style.cssText !== "" ? ' style="' + f.style + '"' : "", '<li id="' + f.dom_id + '" class="' + e.join(" ") + '"' + g + ">" + f.html + "</li>")
        }, b.prototype.results_update_field = function () {
            return this.is_multiple || this.results_reset_cleanup(), this.result_clear_highlight(), this.result_single_selected = null, this.results_build()
        }, b.prototype.results_toggle = function () {
            return this.results_showing ? this.results_hide() : this.results_show()
        }, b.prototype.results_search = function (e) {
            return this.results_showing ? this.winnow_results() : this.results_show()
        }, b.prototype.keyup_checker = function (f) {
            var e, g;
            e = (g = f.which) != null ? g : f.keyCode, this.search_field_scale();
            switch (e) {
                case 8:
                    if (this.is_multiple && this.backstroke_length < 1 && this.choices > 0) {
                        return this.keydown_backstroke()
                    }
                    if (!this.pending_backstroke) {
                        return this.result_clear_highlight(), this.results_search()
                    }
                    break;
                case 13:
                    f.preventDefault();
                    if (this.results_showing) {
                        return this.result_select(f)
                    }
                    break;
                case 27:
                    return this.results_showing && this.results_hide(), !0;
                case 9:
                case 38:
                case 40:
                case 16:
                case 91:
                case 17:
                    break;
                default:
                    return this.results_search()
            }
        }, b.prototype.generate_field_id = function () {
            var e;
            return e = this.generate_random_id(), this.form_field.id = e, e
        }, b.prototype.generate_random_char = function () {
            var f, e, g;
            return f = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", g = Math.floor(Math.random() * f.length), e = f.substring(g, g + 1)
        }, b
    }(), c.AbstractChosen = d
}.call(this), function () {
    var h, g, m, l, k = Object.prototype.hasOwnProperty, j = function (f, e) {
        function n() {
            this.constructor = f
        }

        for (var o in e) {
            k.call(e, o) && (f[o] = e[o])
        }
        return n.prototype = e.prototype, f.prototype = new n, f.__super__ = e.prototype, f
    };
    l = this, h = jQuery, h.fn.extend({chosen: function (a) {
        return !h.browser.msie || h.browser.version !== "6.0" && h.browser.version !== "7.0" ? this.each(function (c) {
            var b;
            b = h(this);
            if (!b.hasClass("chzn-done")) {
                return b.data("chosen", new g(this, a))
            }
        }) : this
    }}), g = function (a) {
        function c() {
            c.__super__.constructor.apply(this, arguments)
        }

        return j(c, a), c.prototype.setup = function () {
            return this.form_field_jq = h(this.form_field), this.current_value = this.form_field_jq.val(), this.is_rtl = this.form_field_jq.hasClass("chzn-rtl")
        }, c.prototype.finish_setup = function () {
            return this.form_field_jq.addClass("chzn-done")
        }, c.prototype.set_up_html = function () {
            var n, q, p, o;
            return this.container_id = this.form_field.id.length ? this.form_field.id.replace(/[^\w]/g, "_") : this.generate_field_id(), this.container_id += "_chzn", this.f_width = this.form_field_jq.outerWidth(), n = h("<div />", {id: this.container_id, "class": "chzn-container" + (this.is_rtl ? " chzn-rtl" : ""), style: "width: " + this.f_width + "px;"}), this.is_multiple ? n.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>') : n.html('<a href="javascript:void(0)" class="chzn-single chzn-default"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'), this.form_field_jq.hide().after(n), this.container = h("#" + this.container_id), this.container.addClass("chzn-container-" + (this.is_multiple ? "multi" : "single")), this.dropdown = this.container.find("div.chzn-drop").first(), q = this.container.height(), p = this.f_width - m(this.dropdown), this.dropdown.css({width: (p + 25) + "px", top: q + "px"}), this.search_field = this.container.find("input").first(), this.search_results = this.container.find("ul.chzn-results").first(), this.search_field_scale(), this.search_no_results = this.container.find("li.no-results").first(), this.is_multiple ? (this.search_choices = this.container.find("ul.chzn-choices").first(), this.search_container = this.container.find("li.search-field").first()) : (this.search_container = this.container.find("div.chzn-search").first(), this.selected_item = this.container.find(".chzn-single").first(), o = p - m(this.search_container) - m(this.search_field), this.search_field.css({width: (o + 25) + "px"})), this.results_build(), this.set_tab_index(), this.form_field_jq.trigger("liszt:ready", {chosen: this})
        }, c.prototype.register_observers = function () {
            var b = this;
            return this.container.mousedown(function (d) {
                return b.container_mousedown(d)
            }), this.container.mouseup(function (d) {
                return b.container_mouseup(d)
            }), this.container.mouseenter(function (d) {
                return b.mouse_enter(d)
            }), this.container.mouseleave(function (d) {
                return b.mouse_leave(d)
            }), this.search_results.mouseup(function (d) {
                return b.search_results_mouseup(d)
            }), this.search_results.mouseover(function (d) {
                return b.search_results_mouseover(d)
            }), this.search_results.mouseout(function (d) {
                return b.search_results_mouseout(d)
            }), this.form_field_jq.bind("liszt:updated", function (d) {
                return b.results_update_field(d)
            }), this.search_field.blur(function (d) {
                return b.input_blur(d)
            }), this.search_field.keyup(function (d) {
                return b.keyup_checker(d)
            }), this.search_field.keydown(function (d) {
                return b.keydown_checker(d)
            }), this.is_multiple ? (this.search_choices.click(function (d) {
                return b.choices_click(d)
            }), this.search_field.focus(function (d) {
                return b.input_focus(d)
            })) : this.container.click(function (d) {
                return d.preventDefault()
            })
        }, c.prototype.search_field_disabled = function () {
            this.is_disabled = this.form_field_jq[0].disabled;
            if (this.is_disabled) {
                return this.container.addClass("chzn-disabled"), this.search_field[0].disabled = !0, this.is_multiple || this.selected_item.unbind("focus", this.activate_action), this.close_field()
            }
            this.container.removeClass("chzn-disabled"), this.search_field[0].disabled = !1;
            if (!this.is_multiple) {
                return this.selected_item.bind("focus", this.activate_action)
            }
        }, c.prototype.container_mousedown = function (d) {
            var e;
            if (!this.is_disabled) {
                return e = d != null ? h(d.target).hasClass("search-choice-close") : !1, d && d.type === "mousedown" && !this.results_showing && d.stopPropagation(), !this.pending_destroy_click && !e ? (this.active_field ? !this.is_multiple && d && (h(d.target)[0] === this.selected_item[0] || h(d.target).parents("a.chzn-single").length) && (d.preventDefault(), this.results_toggle()) : (this.is_multiple && this.search_field.val(""), h(document).click(this.click_test_action), this.results_show()), this.activate_field()) : this.pending_destroy_click = !1
            }
        }, c.prototype.container_mouseup = function (b) {
            if (b.target.nodeName === "ABBR" && !this.is_disabled) {
                return this.results_reset(b)
            }
        }, c.prototype.blur_test = function (b) {
            if (!this.active_field && this.container.hasClass("chzn-container-active")) {
                return this.close_field()
            }
        }, c.prototype.close_field = function () {
            return h(document).unbind("click", this.click_test_action), this.is_multiple || (this.selected_item.attr("tabindex", this.search_field.attr("tabindex")), this.search_field.attr("tabindex", -1)), this.active_field = !1, this.results_hide(), this.container.removeClass("chzn-container-active"), this.winnow_results_clear(), this.clear_backstroke(), this.show_search_field_default(), this.search_field_scale()
        }, c.prototype.activate_field = function () {
            return !this.is_multiple && !this.active_field && (this.search_field.attr("tabindex", this.selected_item.attr("tabindex")), this.selected_item.attr("tabindex", -1)), this.container.addClass("chzn-container-active"), this.active_field = !0, this.search_field.val(this.search_field.val()), this.search_field.focus()
        }, c.prototype.test_active_click = function (d) {
            return h(d.target).parents("#" + this.container_id).length ? this.active_field = !0 : this.close_field()
        }, c.prototype.results_build = function () {
            var n, d, q, p, o;
            this.parsing = !0, this.results_data = l.SelectParser.select_to_array(this.form_field), this.is_multiple && this.choices > 0 ? (this.search_choices.find("li.search-choice").remove(), this.choices = 0) : this.is_multiple || (this.selected_item.addClass("chzn-default").find("span").text(this.default_text), this.form_field.options.length <= this.disable_search_threshold ? this.container.addClass("chzn-container-single-nosearch") : this.container.removeClass("chzn-container-single-nosearch")), n = "", o = this.results_data;
            for (q = 0, p = o.length; q < p; q++) {
                d = o[q], d.group ? n += this.result_add_group(d) : d.empty || (n += this.result_add_option(d), d.selected && this.is_multiple ? this.choice_build(d) : d.selected && !this.is_multiple && (this.selected_item.removeClass("chzn-default").find("span").text(d.text), this.allow_single_deselect && this.single_deselect_control_build()))
            }
            return this.search_field_disabled(), this.show_search_field_default(), this.search_field_scale(), this.search_results.html(n), this.parsing = !1
        }, c.prototype.result_add_group = function (d) {
            return d.disabled ? "" : (d.dom_id = this.container_id + "_g_" + d.array_index, '<li id="' + d.dom_id + '" class="group-result">' + h("<div />").text(d.label).html() + "</li>")
        }, c.prototype.result_do_highlight = function (o) {
            var n, s, r, q, p;
            if (o.length) {
                this.result_clear_highlight(), this.result_highlight = o, this.result_highlight.addClass("highlighted"), r = parseInt(this.search_results.css("maxHeight"), 10), p = this.search_results.scrollTop(), q = r + p, s = this.result_highlight.position().top + this.search_results.scrollTop(), n = s + this.result_highlight.outerHeight();
                if (n >= q) {
                    return this.search_results.scrollTop(n - r > 0 ? n - r : 0)
                }
                if (s < p) {
                    return this.search_results.scrollTop(s)
                }
            }
        }, c.prototype.result_clear_highlight = function () {
            return this.result_highlight && this.result_highlight.removeClass("highlighted"), this.result_highlight = null
        }, c.prototype.results_show = function () {
            var b;
            if (!this.is_multiple) {
                this.selected_item.addClass("chzn-single-with-drop"), this.result_single_selected && this.result_do_highlight(this.result_single_selected)
            } else {
                if (this.max_selected_options <= this.choices) {
                    return this.form_field_jq.trigger("liszt:maxselected", {chosen: this}), !1
                }
            }
            return b = this.is_multiple ? this.container.height() : this.container.height() - 1, this.form_field_jq.trigger("liszt:showing_dropdown", {chosen: this}), this.dropdown.css({top: b + "px", left: 0}), this.results_showing = !0, this.search_field.focus(), this.search_field.val(this.search_field.val()), this.winnow_results()
        }, c.prototype.results_hide = function () {
            return this.is_multiple || this.selected_item.removeClass("chzn-single-with-drop"), this.result_clear_highlight(), this.form_field_jq.trigger("liszt:hiding_dropdown", {chosen: this}), this.dropdown.css({left: "-9000px"}), this.results_showing = !1
        }, c.prototype.set_tab_index = function (e) {
            var d;
            if (this.form_field_jq.attr("tabindex")) {
                return d = this.form_field_jq.attr("tabindex"), this.form_field_jq.attr("tabindex", -1), this.is_multiple ? this.search_field.attr("tabindex", d) : (this.selected_item.attr("tabindex", d), this.search_field.attr("tabindex", -1))
            }
        }, c.prototype.show_search_field_default = function () {
            return this.is_multiple && this.choices < 1 && !this.active_field ? (this.search_field.val(this.default_text), this.search_field.addClass("default")) : (this.search_field.val(""), this.search_field.removeClass("default"))
        }, c.prototype.search_results_mouseup = function (d) {
            var e;
            e = h(d.target).hasClass("active-result") ? h(d.target) : h(d.target).parents(".active-result").first();
            if (e.length) {
                return this.result_highlight = e, this.result_select(d)
            }
        }, c.prototype.search_results_mouseover = function (d) {
            var e;
            e = h(d.target).hasClass("active-result") ? h(d.target) : h(d.target).parents(".active-result").first();
            if (e) {
                return this.result_do_highlight(e)
            }
        }, c.prototype.search_results_mouseout = function (d) {
            if (h(d.target).hasClass("active-result")) {
                return this.result_clear_highlight()
            }
        }, c.prototype.choices_click = function (d) {
            d.preventDefault();
            if (this.active_field && !h(d.target).hasClass("search-choice") && !this.results_showing) {
                return this.results_show()
            }
        }, c.prototype.choice_build = function (f) {
            var p, o, n = this;
            return this.is_multiple && this.max_selected_options <= this.choices ? (this.form_field_jq.trigger("liszt:maxselected", {chosen: this}), !1) : (p = this.container_id + "_c_" + f.array_index, this.choices += 1, this.search_container.before('<li class="search-choice" id="' + p + '"><span>' + f.html + '</span><a href="javascript:void(0)" class="search-choice-close" rel="' + f.array_index + '"></a></li>'), o = h("#" + p).find("a").first(), o.click(function (b) {
                return n.choice_destroy_link_click(b)
            }))
        }, c.prototype.choice_destroy_link_click = function (d) {
            return d.preventDefault(), this.is_disabled ? d.stopPropagation : (this.pending_destroy_click = !0, this.choice_destroy(h(d.target)))
        }, c.prototype.choice_destroy = function (b) {
            return this.choices -= 1, this.show_search_field_default(), this.is_multiple && this.choices > 0 && this.search_field.val().length < 1 && this.results_hide(), this.result_deselect(b.attr("rel")), b.parents("li").first().remove()
        }, c.prototype.results_reset = function () {
            this.form_field.options[0].selected = !0, this.selected_item.find("span").text(this.default_text), this.is_multiple || this.selected_item.addClass("chzn-default"), this.show_search_field_default(), this.results_reset_cleanup(), this.form_field_jq.trigger("change");
            if (this.active_field) {
                return this.results_hide()
            }
        }, c.prototype.results_reset_cleanup = function () {
            return this.selected_item.find("abbr").remove()
        }, c.prototype.result_select = function (n) {
            var f, q, p, o;
            if (this.result_highlight) {
                return f = this.result_highlight, q = f.attr("id"), this.result_clear_highlight(), this.is_multiple ? this.result_deactivate(f) : (this.search_results.find(".result-selected").removeClass("result-selected"), this.result_single_selected = f, this.selected_item.removeClass("chzn-default")), f.addClass("result-selected"), o = q.substr(q.lastIndexOf("_") + 1), p = this.results_data[o], p.selected = !0, this.form_field.options[p.options_index].selected = !0, this.is_multiple ? this.choice_build(p) : (this.selected_item.find("span").first().text(p.text), this.allow_single_deselect && this.single_deselect_control_build()), (!n.metaKey || !this.is_multiple) && this.results_hide(), this.search_field.val(""), (this.is_multiple || this.form_field_jq.val() !== this.current_value) && this.form_field_jq.trigger("change", {selected: this.form_field.options[p.options_index].value}), this.current_value = this.form_field_jq.val(), this.search_field_scale()
            }
        }, c.prototype.result_activate = function (b) {
            return b.addClass("active-result")
        }, c.prototype.result_deactivate = function (b) {
            return b.removeClass("active-result")
        }, c.prototype.result_deselect = function (e) {
            var n, f;
            return f = this.results_data[e], f.selected = !1, this.form_field.options[f.options_index].selected = !1, n = h("#" + this.container_id + "_o_" + e), n.removeClass("result-selected").addClass("active-result").show(), this.result_clear_highlight(), this.winnow_results(), this.form_field_jq.trigger("change", {deselected: this.form_field.options[f.options_index].value}), this.search_field_scale()
        }, c.prototype.single_deselect_control_build = function () {
            if (this.allow_single_deselect && this.selected_item.find("abbr").length < 1) {
                return this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')
            }
        }, c.prototype.winnow_results = function () {
            var K, J, I, H, G, F, E, D, C, B, A, z, y, x, w, v, u, t;
            this.no_results_clear(), C = 0, B = this.search_field.val() === this.default_text ? "" : h("<div/>").text(h.trim(this.search_field.val())).html(), F = this.search_contains ? "" : "", G = new RegExp(F + B.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), "i"), y = new RegExp(B.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), "i"), t = this.results_data;
            for (x = 0, v = t.length; x < v; x++) {
                J = t[x];
                if (!J.disabled && !J.empty) {
                    if (J.group) {
                        h("#" + J.dom_id).css("display", "none")
                    } else {
                        if (!this.is_multiple || !J.selected) {
                            K = !1, D = J.dom_id, E = h("#" + D);
                            if (G.test(J.html)) {
                                K = !0, C += 1
                            } else {
                                if (J.html.indexOf(" ") >= 0 || J.html.indexOf("[") === 0) {
                                    H = J.html.replace(/\[|\]/g, "").split(" ");
                                    if (H.length) {
                                        for (w = 0, u = H.length; w < u; w++) {
                                            I = H[w], G.test(I) && (K = !0, C += 1)
                                        }
                                    }
                                }
                            }
                            K ? (B.length ? (A = J.html.search(y), z = J.html.substr(0, A + B.length) + "</em>" + J.html.substr(A + B.length), z = z.substr(0, A) + "<em>" + z.substr(A)) : z = J.html, E.html(z), this.result_activate(E), J.group_array_index != null && h("#" + this.results_data[J.group_array_index].dom_id).css("display", "list-item")) : (this.result_highlight && D === this.result_highlight.attr("id") && this.result_clear_highlight(), this.result_deactivate(E))
                        }
                    }
                }
            }
            return C < 1 && B.length ? this.no_results(B) : this.winnow_results_set_highlight()
        }, c.prototype.winnow_results_clear = function () {
            var n, r, q, p, o;
            this.search_field.val(""), r = this.search_results.find("li"), o = [];
            for (q = 0, p = r.length; q < p; q++) {
                n = r[q], n = h(n), n.hasClass("group-result") ? o.push(n.css("display", "auto")) : !this.is_multiple || !n.hasClass("result-selected") ? o.push(this.result_activate(n)) : o.push(void 0)
            }
            return o
        }, c.prototype.winnow_results_set_highlight = function () {
            var e, d;
            if (!this.result_highlight) {
                d = this.is_multiple ? [] : this.search_results.find(".result-selected.active-result"), e = d.length ? d.first() : this.search_results.find(".active-result").first();
                if (e != null) {
                    return this.result_do_highlight(e)
                }
            }
        }, c.prototype.no_results = function (d) {
            var e;
            return e = h('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>'), e.find("span").first().html(d), this.search_results.append(e)
        }, c.prototype.no_results_clear = function () {
            return this.search_results.find(".no-results").remove()
        }, c.prototype.keydown_arrow = function () {
            var d, e;
            this.result_highlight ? this.results_showing && (e = this.result_highlight.nextAll("li.active-result").first(), e && this.result_do_highlight(e)) : (d = this.search_results.find("li.active-result").first(), d && this.result_do_highlight(h(d)));
            if (!this.results_showing) {
                return this.results_show()
            }
        }, c.prototype.keyup_arrow = function () {
            var b;
            if (!this.results_showing && !this.is_multiple) {
                return this.results_show()
            }
            if (this.result_highlight) {
                return b = this.result_highlight.prevAll("li.active-result"), b.length ? this.result_do_highlight(b.first()) : (this.choices > 0 && this.results_hide(), this.result_clear_highlight())
            }
        }, c.prototype.keydown_backstroke = function () {
            return this.pending_backstroke ? (this.choice_destroy(this.pending_backstroke.find("a").first()), this.clear_backstroke()) : (this.pending_backstroke = this.search_container.siblings("li.search-choice").last(), this.single_backstroke_delete ? this.keydown_backstroke() : this.pending_backstroke.addClass("search-choice-focus"))
        }, c.prototype.clear_backstroke = function () {
            return this.pending_backstroke && this.pending_backstroke.removeClass("search-choice-focus"), this.pending_backstroke = null
        }, c.prototype.keydown_checker = function (e) {
            var d, f;
            d = (f = e.which) != null ? f : e.keyCode, this.search_field_scale(), d !== 8 && this.pending_backstroke && this.clear_backstroke();
            switch (d) {
                case 8:
                    this.backstroke_length = this.search_field.val().length;
                    break;
                case 9:
                    this.results_showing && !this.is_multiple && this.result_select(e), this.mouse_on_container = !1;
                    break;
                case 13:
                    e.preventDefault();
                    break;
                case 38:
                    e.preventDefault(), this.keyup_arrow();
                    break;
                case 40:
                    this.keydown_arrow()
            }
        }, c.prototype.search_field_scale = function () {
            var v, u, t, s, r, q, p, o, n;
            if (this.is_multiple) {
                t = 0, p = 0, r = "position:absolute; left: -1000px; top: -1000px; display:none;", q = ["font-size", "font-style", "font-weight", "font-family", "line-height", "text-transform", "letter-spacing"];
                for (o = 0, n = q.length; o < n; o++) {
                    s = q[o], r += s + ":" + this.search_field.css(s) + ";"
                }
                return u = h("<div />", {style: r}), u.text(this.search_field.val()), h("body").append(u), p = u.width() + 25, u.remove(), p > this.f_width - 10 && (p = this.f_width - 10), this.search_field.css({width: p + "px"}), v = this.container.height(), this.dropdown.css({top: v + "px"})
            }
        }, c.prototype.generate_random_id = function () {
            var d;
            d = "sel" + this.generate_random_char() + this.generate_random_char() + this.generate_random_char();
            while (h("#" + d).length > 0) {
                d += this.generate_random_char()
            }
            return d
        }, c
    }(AbstractChosen), m = function (d) {
        var c;
        return c = d.outerWidth() - d.width()
    }, l.get_side_border_padding = m
}.call(this);
(function (a) {
    a.extend(a.fn, {swapClass: function (e, d) {
        var c = this.filter("." + e);
        this.filter("." + d).removeClass(d).addClass(e);
        c.removeClass(e).addClass(d);
        return this
    }, replaceClass: function (d, c) {
        return this.filter("." + d).removeClass(d).addClass(c).end()
    }, hoverClass: function (c) {
        c = c || "hover";
        return this.hover(function () {
            a(this).addClass(c)
        }, function () {
            a(this).removeClass(c)
        })
    }, heightToggle: function (c, d) {
        c ? this.animate({height: "toggle"}, c, d) : this.each(function () {
            jQuery(this)[jQuery(this).css("display") == "none" ? "show" : "hide"]();
            if (d) {
                d.apply(this, arguments)
            }
        })
    }, heightHide: function (c, d) {
        if (c) {
            this.animate({height: "hide"}, c, d)
        } else {
            this.hide();
            if (d) {
                this.each(d)
            }
        }
    }, prepareBranches: function (c) {
        if (!c.prerendered) {
            this.filter(":last-child:not(ul)").addClass(b.last);
            this.filter((c.collapsed ? "" : "." + b.closed) + ":not(." + b.open + ")").find(">ul").hide()
        }
        return this.filter(":has(>ul)")
    }, applyClasses: function (c, d) {
        this.filter(":has(>ul):not(:has(>a))").find(">span").click(function (e) {
            d.apply(a(this).next())
        }).add(a("a", this)).hoverClass();
        if (!c.prerendered) {
            this.filter(":has(>ul:hidden)").addClass(b.expandable).replaceClass(b.last, b.lastExpandable);
            this.not(":has(>ul:hidden)").addClass(b.collapsable).replaceClass(b.last, b.lastCollapsable);
            this.prepend('<div class="' + b.hitarea + '"/>').find("div." + b.hitarea).each(function () {
                var e = "";
                a.each(a(this).parent().attr("class").split(" "), function () {
                    e += this + "-hitarea "
                });
                a(this).addClass(e)
            })
        }
        this.find("div." + b.hitarea).click(d)
    }, treeview: function (d) {
        d = a.extend({cookieId: "treeview"}, d);
        if (d.add) {
            return this.trigger("add", [d.add])
        }
        if (d.toggle) {
            var j = d.toggle;
            d.toggle = function () {
                return j.apply(a(this).parent()[0], arguments)
            }
        }
        function c(m, o) {
            function n(p) {
                return function () {
                    f.apply(a("div." + b.hitarea, m).filter(function () {
                        return p ? a(this).parent("." + p).length : true
                    }));
                    return false
                }
            }

            a("a:eq(0)", o).click(n(b.collapsable));
            a("a:eq(1)", o).click(n(b.expandable));
            a("a:eq(2)", o).click(n())
        }

        function f() {
            a(this).parent().find(">.hitarea").swapClass(b.collapsableHitarea, b.expandableHitarea).swapClass(b.lastCollapsableHitarea, b.lastExpandableHitarea).end().swapClass(b.collapsable, b.expandable).swapClass(b.lastCollapsable, b.lastExpandable).find(">ul").heightToggle(d.animated, d.toggle);
            if (d.unique) {
                a(this).parent().siblings().find(">.hitarea").replaceClass(b.collapsableHitarea, b.expandableHitarea).replaceClass(b.lastCollapsableHitarea, b.lastExpandableHitarea).end().replaceClass(b.collapsable, b.expandable).replaceClass(b.lastCollapsable, b.lastExpandable).find(">ul").heightHide(d.animated, d.toggle)
            }
        }

        function l() {
            function n(o) {
                return o ? 1 : 0
            }

            var m = [];
            k.each(function (o, p) {
                m[o] = a(p).is(":has(>ul:visible)") ? 1 : 0
            });
            a.cookie(d.cookieId, m.join(""))
        }

        function e() {
            var m = a.cookie(d.cookieId);
            if (m) {
                var n = m.split("");
                k.each(function (o, p) {
                    a(p).find(">ul")[parseInt(n[o]) ? "show" : "hide"]()
                })
            }
        }

        this.addClass("treeview");
        var k = this.find("li").prepareBranches(d);
        switch (d.persist) {
            case"cookie":
                var h = d.toggle;
                d.toggle = function () {
                    l();
                    if (h) {
                        h.apply(this, arguments)
                    }
                };
                e();
                break;
            case"location":
                var g = this.find("a").filter(function () {
                    return this.href.toLowerCase() == location.href.toLowerCase()
                });
                if (g.length) {
                    g.addClass("selected").parents("ul, li").add(g.next()).show()
                }
                break
        }
        k.applyClasses(d, f);
        if (d.control) {
            c(this, d.control);
            a(d.control).show()
        }
        return this.bind("add", function (n, m) {
            a(m).prev().removeClass(b.last).removeClass(b.lastCollapsable).removeClass(b.lastExpandable).find(">.hitarea").removeClass(b.lastCollapsableHitarea).removeClass(b.lastExpandableHitarea);
            a(m).find("li").andSelf().prepareBranches(d).applyClasses(d, f)
        })
    }});
    var b = a.fn.treeview.classes = {open: "open", closed: "closed", expandable: "expandable", expandableHitarea: "expandable-hitarea", lastExpandableHitarea: "lastExpandable-hitarea", collapsable: "collapsable", collapsableHitarea: "collapsable-hitarea", lastCollapsableHitarea: "lastCollapsable-hitarea", lastCollapsable: "lastCollapsable", lastExpandable: "lastExpandable", last: "last", hitarea: "hitarea"};
    a.fn.Treeview = a.fn.treeview
})(jQuery);
Date.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
Date.abbrDayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
Date.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
Date.abbrMonthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
Date.firstDayOfWeek = 1;
Date.format = "dd/mm/yyyy";
Date.fullYearStart = "20";
(function () {
    function b(c, d) {
        if (!Date.prototype[c]) {
            Date.prototype[c] = d
        }
    }

    b("isLeapYear", function () {
        var c = this.getFullYear();
        return(c % 4 == 0 && c % 100 != 0) || c % 400 == 0
    });
    b("isWeekend", function () {
        return this.getDay() == 0 || this.getDay() == 6
    });
    b("isWeekDay", function () {
        return !this.isWeekend()
    });
    b("getDaysInMonth", function () {
        return[31, (this.isLeapYear() ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][this.getMonth()]
    });
    b("getDayName", function (c) {
        return c ? Date.abbrDayNames[this.getDay()] : Date.dayNames[this.getDay()]
    });
    b("getMonthName", function (c) {
        return c ? Date.abbrMonthNames[this.getMonth()] : Date.monthNames[this.getMonth()]
    });
    b("getDayOfYear", function () {
        var c = new Date("1/1/" + this.getFullYear());
        return Math.floor((this.getTime() - c.getTime()) / 86400000)
    });
    b("getWeekOfYear", function () {
        return Math.ceil(this.getDayOfYear() / 7)
    });
    b("setDayOfYear", function (c) {
        this.setMonth(0);
        this.setDate(c);
        return this
    });
    b("addYears", function (c) {
        this.setFullYear(this.getFullYear() + c);
        return this
    });
    b("addMonths", function (d) {
        var c = this.getDate();
        this.setMonth(this.getMonth() + d);
        if (c > this.getDate()) {
            this.addDays(-this.getDate())
        }
        return this
    });
    b("addDays", function (c) {
        this.setTime(this.getTime() + (c * 86400000));
        return this
    });
    b("addHours", function (c) {
        this.setHours(this.getHours() + c);
        return this
    });
    b("addMinutes", function (c) {
        this.setMinutes(this.getMinutes() + c);
        return this
    });
    b("addSeconds", function (c) {
        this.setSeconds(this.getSeconds() + c);
        return this
    });
    b("zeroTime", function () {
        this.setMilliseconds(0);
        this.setSeconds(0);
        this.setMinutes(0);
        this.setHours(0);
        return this
    });
    b("asString", function (d) {
        var c = d || Date.format;
        return c.split("yyyy").join(this.getFullYear()).split("yy").join((this.getFullYear() + "").substring(2)).split("mmmm").join(this.getMonthName(false)).split("mmm").join(this.getMonthName(true)).split("mm").join(a(this.getMonth() + 1)).split("dd").join(a(this.getDate())).split("hh").join(a(this.getHours())).split("min").join(a(this.getMinutes())).split("ss").join(a(this.getSeconds()))
    });
    Date.fromString = function (o, n) {
        var j = n || Date.format;
        var m = new Date("01/01/1977");
        var k = 0;
        var c = j.indexOf("mmmm");
        if (c > -1) {
            for (var g = 0; g < Date.monthNames.length; g++) {
                var e = o.substr(c, Date.monthNames[g].length);
                if (Date.monthNames[g] == e) {
                    k = Date.monthNames[g].length - 4;
                    break
                }
            }
            m.setMonth(g)
        } else {
            c = j.indexOf("mmm");
            if (c > -1) {
                var e = o.substr(c, 3);
                for (var g = 0; g < Date.abbrMonthNames.length; g++) {
                    if (Date.abbrMonthNames[g] == e) {
                        break
                    }
                }
                m.setMonth(g)
            } else {
                m.setMonth(Number(o.substr(j.indexOf("mm"), 2)) - 1)
            }
        }
        var l = j.indexOf("yyyy");
        if (l > -1) {
            if (c < l) {
                l += k
            }
            m.setFullYear(Number(o.substr(l, 4)))
        } else {
            if (c < l) {
                l += k
            }
            m.setFullYear(Number(Date.fullYearStart + o.substr(j.indexOf("yy"), 2)))
        }
        var h = j.indexOf("dd");
        if (c < h) {
            h += k
        }
        m.setDate(Number(o.substr(h, 2)));
        if (isNaN(m.getTime())) {
            return false
        }
        return m
    };
    var a = function (c) {
        var d = "0" + c;
        return d.substring(d.length - 2)
    }
})();
(function (f) {
    function h(b) {
        this.ele = b, this.displayedMonth = null, this.displayedYear = null, this.startDate = null, this.endDate = null, this.showYearNavigation = null, this.closeOnSelect = null, this.displayClose = null, this.displayDynamic = null, this.rememberViewedMonth = null, this.selectMultiple = null, this.numSelectable = null, this.numSelected = null, this.verticalPosition = null, this.horizontalPosition = null, this.verticalOffset = null, this.horizontalOffset = null, this.button = null, this.renderCallback = [], this.selectedDates = {}, this.inline = null, this.context = "#dp-popup", this.settings = {}
    }

    function g(a) {
        return a._dpId ? f.event._dpCache[a._dpId] : !1
    }

    f.fn.extend({renderCalendar: function (P) {
        var O = function (b) {
            return document.createElement(b)
        };
        if (P = f.extend({}, f.fn.datePicker.defaults, P), P.showHeader != f.dpConst.SHOW_HEADER_NONE) {
            for (var N = f(O("tr")), M = Date.firstDayOfWeek; Date.firstDayOfWeek + 7 > M; M++) {
                var L = M % 7, K = Date.dayNames[L];
                N.append(jQuery(O("th")).attr({scope: "col", abbr: K, title: K, "class": 0 == L || 6 == L ? "weekend" : "weekday"}).html(P.showHeader == f.dpConst.SHOW_HEADER_SHORT ? K.substr(0, 1) : K))
            }
        }
        var J = f(O("table")).attr({cellspacing: 2}).addClass("jCalendar").append(P.showHeader != f.dpConst.SHOW_HEADER_NONE ? f(O("thead")).append(N) : O("thead")), I = f(O("tbody")), H = (new Date).zeroTime();
        H.setHours(12);
        var G = void 0 == P.month ? H.getMonth() : P.month, F = P.year || H.getFullYear(), E = new Date(F, G, 1, 12, 0, 0), D = Date.firstDayOfWeek - E.getDay() + 1;
        D > 1 && (D -= 7);
        var C = Math.ceil((-1 * D + 1 + E.getDaysInMonth()) / 7);
        E.addDays(D - 1);
        for (var B = function (b) {
            return function () {
                if (P.hoverClass) {
                    var c = f(this);
                    P.selectWeek ? b && !c.is(".disabled") && c.parent().addClass("activeWeekHover") : c.addClass(P.hoverClass)
                }
            }
        }, A = function () {
            if (P.hoverClass) {
                var b = f(this);
                b.removeClass(P.hoverClass), b.parent().removeClass("activeWeekHover")
            }
        }, z = 0; C > z++;) {
            for (var y = jQuery(O("tr")), x = P.dpController ? E > P.dpController.startDate : !1, M = 0; 7 > M; M++) {
                var w = E.getMonth() == G, a = f(O("td")).text(E.getDate() + "").addClass((w ? "current-month " : "other-month ") + (E.isWeekend() ? "weekend " : "weekday ") + (w && E.getTime() == H.getTime() ? "today " : "")).data("datePickerDate", E.asString()).hover(B(x), A);
                y.append(a), P.renderCallback && P.renderCallback(a, E, G, F), E = new Date(E.getFullYear(), E.getMonth(), E.getDate() + 1, 12, 0, 0)
            }
            I.append(y)
        }
        return J.append(I), this.each(function () {
            f(this).empty().append(J)
        })
    }, datePicker: function (a) {
        return f.event._dpCache || (f.event._dpCache = []), a = f.extend({}, f.fn.datePicker.defaults, a), this.each(function () {
            var k = f(this), j = !0;
            this._dpId || (this._dpId = f.guid++, f.event._dpCache[this._dpId] = new h(this), j = !1), a.inline && (a.createButton = !1, a.displayClose = !1, a.displayDynamic = !1, a.closeOnSelect = !1, k.empty());
            var c = f.event._dpCache[this._dpId];
            if (c.init(a), !j && a.createButton && (c.button = f('<a href="#" class="dp-choose-date" title="' + f.dpText.TEXT_CHOOSE_DATE + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>').bind("click", function () {
                return k.dpDisplay(this), this.blur(), !1
            }), k.after(c.button)), !j && k.is(":text")) {
                k.bind("dateSelected",function (l, d) {
                    this.value = d.asString()
                }).bind("change", function () {
                    if ("" == this.value) {
                        c.clearSelected()
                    } else {
                        var d = Date.fromString(this.value);
                        d && c.setSelected(d, !0, !0)
                    }
                }), a.clickInput && k.bind("click", function () {
                    k.trigger("change"), k.dpDisplay()
                });
                var b = Date.fromString(this.value);
                "" != this.value && b && c.setSelected(b, !0, !0)
            }
            k.addClass("dp-applied")
        })
    }, dpSetDisabled: function (b) {
        return e.call(this, "setDisabled", b)
    }, dpSetStartDate: function (b) {
        return e.call(this, "setStartDate", b)
    }, dpSetEndDate: function (b) {
        return e.call(this, "setEndDate", b)
    }, dpGetSelected: function () {
        var b = g(this[0]);
        return b ? b.getSelected() : null
    }, dpSetSelected: function (b, l, k, j) {
        return void 0 == l && (l = !0), void 0 == k && (k = !0), void 0 == j && (j = !0), e.call(this, "setSelected", Date.fromString(b), l, k, j)
    }, dpSetDisplayedMonth: function (b, d) {
        return e.call(this, "setDisplayedMonth", Number(b), Number(d), !0)
    }, dpDisplay: function (b) {
        return e.call(this, "display", b)
    }, dpSetRenderCallback: function (b) {
        return e.call(this, "setRenderCallback", b)
    }, dpSetPosition: function (b, d) {
        return e.call(this, "setPosition", b, d)
    }, dpSetOffset: function (b, d) {
        return e.call(this, "setOffset", b, d)
    }, dpClose: function () {
        return e.call(this, "_closeCalendar", !1, this[0])
    }, dpRerenderCalendar: function () {
        return e.call(this, "_rerenderCalendar")
    }, _dpDestroy: function () {
    }});
    var e = function (j, d, m, l, k) {
        return this.each(function () {
            var a = g(this);
            a && a[j](d, m, l, k)
        })
    };
    f.extend(h.prototype, {init: function (b) {
        this.setStartDate(b.startDate), this.setEndDate(b.endDate), this.setDisplayedMonth(Number(b.month), Number(b.year)), this.setRenderCallback(b.renderCallback), this.showYearNavigation = b.showYearNavigation, this.closeOnSelect = b.closeOnSelect, this.displayClose = b.displayClose, this.displayDynamic = b.displayDynamic, this.rememberViewedMonth = b.rememberViewedMonth, this.selectMultiple = b.selectMultiple, this.numSelectable = b.selectMultiple ? b.numSelectable : 1, this.numSelected = 0, this.verticalPosition = b.verticalPosition, this.horizontalPosition = b.horizontalPosition, this.hoverClass = b.hoverClass, this.setOffset(b.verticalOffset, b.horizontalOffset), this.inline = b.inline, this.settings = b, this.inline && (this.context = this.ele, this.display())
    }, setStartDate: function (b) {
        b && (this.startDate = b instanceof Date ? b : Date.fromString(b)), this.startDate || (this.startDate = (new Date).zeroTime()), this.setDisplayedMonth(this.displayedMonth, this.displayedYear)
    }, setEndDate: function (b) {
        b && (this.endDate = b instanceof Date ? b : Date.fromString(b)), this.endDate || (this.endDate = new Date("12/31/2999")), this.endDate.getTime() < this.startDate.getTime() && (this.endDate = this.startDate), this.setDisplayedMonth(this.displayedMonth, this.displayedYear)
    }, setPosition: function (d, c) {
        this.verticalPosition = d, this.horizontalPosition = c
    }, setOffset: function (d, c) {
        this.verticalOffset = parseInt(d) || 0, this.horizontalOffset = parseInt(c) || 0
    }, setDisabled: function (a) {
        $e = f(this.ele), $e[a ? "addClass" : "removeClass"]("dp-disabled"), this.button && ($but = f(this.button), $but[a ? "addClass" : "removeClass"]("dp-disabled"), $but.attr("title", a ? "" : f.dpText.TEXT_CHOOSE_DATE)), $e.is(":text") && $e.attr("disabled", a ? "disabled" : "")
    }, setDisplayedMonth: function (a, p, o) {
        if (void 0 != this.startDate && void 0 != this.endDate) {
            var n = new Date(this.startDate.getTime());
            n.setDate(1);
            var m = new Date(this.endDate.getTime());
            m.setDate(1);
            var l;
            !a && !p || isNaN(a) && isNaN(p) ? (l = (new Date).zeroTime(), l.setDate(1)) : l = isNaN(a) ? new Date(p, this.displayedMonth, 1) : isNaN(p) ? new Date(this.displayedYear, a, 1) : new Date(p, a, 1), l.getTime() < n.getTime() ? l = n : l.getTime() > m.getTime() && (l = m);
            var k = this.displayedMonth, j = this.displayedYear;
            this.displayedMonth = l.getMonth(), this.displayedYear = l.getFullYear(), !o || this.displayedMonth == k && this.displayedYear == j || (this._rerenderCalendar(), f(this.ele).trigger("dpMonthChanged", [this.displayedMonth, this.displayedYear]))
        }
    }, setSelected: function (a, p, o, n) {
        if (!(this.startDate > a || a.zeroTime() > this.endDate.zeroTime())) {
            var m = this.settings;
            if (!(m.selectWeek && (a = a.addDays(-(a.getDay() - Date.firstDayOfWeek + 7) % 7), this.startDate > a)) && p != this.isSelected(a)) {
                if (0 == this.selectMultiple) {
                    this.clearSelected()
                } else {
                    if (p && this.numSelected == this.numSelectable) {
                        return
                    }
                }
                !o || this.displayedMonth == a.getMonth() && this.displayedYear == a.getFullYear() || this.setDisplayedMonth(a.getMonth(), a.getFullYear(), !0), this.selectedDates[a.asString()] = p, this.numSelected += p ? 1 : -1;
                var k, l = "td." + (a.getMonth() == this.displayedMonth ? "current-month" : "other-month");
                if (f(l, this.context).each(function () {
                    f(this).data("datePickerDate") == a.asString() && (k = f(this), m.selectWeek && k.parent()[p ? "addClass" : "removeClass"]("selectedWeek"), k[p ? "addClass" : "removeClass"]("selected"))
                }), f("td", this.context).not(".selected")[this.selectMultiple && this.numSelected == this.numSelectable ? "addClass" : "removeClass"]("unselectable"), n) {
                    var m = this.isSelected(a);
                    $e = f(this.ele);
                    var j = Date.fromString(a.asString());
                    $e.trigger("dateSelected", [j, k, m]), $e.trigger("change")
                }
            }
        }
    }, isSelected: function (b) {
        return this.selectedDates[b.asString()]
    }, getSelected: function () {
        var d = [];
        for (var c in this.selectedDates) {
            1 == this.selectedDates[c] && d.push(Date.fromString(c))
        }
        return d
    }, clearSelected: function () {
        this.selectedDates = {}, this.numSelected = 0, f("td.selected", this.context).removeClass("selected").parent().removeClass("selectedWeek")
    }, display: function (w) {
        if (!f(this.ele).is(".dp-disabled")) {
            w = w || this.ele;
            var s, r, q, v = this, u = f(w), t = u.offset();
            if (v.inline) {
                s = f(this.ele), r = {id: "calendar-" + this.ele._dpId, "class": "dp-popup dp-popup-inline"}, f(".dp-popup", s).remove(), q = {}
            } else {
                s = f("body"), r = {id: "dp-popup", "class": "dp-popup"}, q = {top: t.top + v.verticalOffset, left: t.left + v.horizontalOffset};
                var p = function (c) {
                    for (var k = c.target, j = f("#dp-popup")[0]; ;) {
                        if (k == j) {
                            return !0
                        }
                        if (k == document) {
                            return v._closeCalendar(), !1
                        }
                        k = f(k).parent()[0]
                    }
                };
                this._checkMouse = p, v._closeCalendar(!0), f(document).bind("keydown.datepicker", function (b) {
                    27 == b.keyCode && v._closeCalendar()
                })
            }
            if (!v.rememberViewedMonth) {
                var o = this.getSelected()[0];
                o && (o = new Date(o), this.setDisplayedMonth(o.getMonth(), o.getFullYear(), !1))
            }
            s.append(f("<div></div>").attr(r).css(q).append(f("<h2></h2>"), f('<div class="dp-nav-prev"></div>').append(f('<a class="dp-nav-prev-year" href="#" title="' + f.dpText.TEXT_PREV_YEAR + '">&lt;&lt;</a>').bind("click", function () {
                return v._displayNewMonth.call(v, this, 0, -1)
            }), f('<a class="dp-nav-prev-month" href="#" title="' + f.dpText.TEXT_PREV_MONTH + '">&lt;</a>').bind("click", function () {
                return v._displayNewMonth.call(v, this, -1, 0)
            })), f('<div class="dp-nav-next"></div>').append(f('<a class="dp-nav-next-year" href="#" title="' + f.dpText.TEXT_NEXT_YEAR + '">&gt;&gt;</a>').bind("click", function () {
                return v._displayNewMonth.call(v, this, 0, 1)
            }), f('<a class="dp-nav-next-month" href="#" title="' + f.dpText.TEXT_NEXT_MONTH + '">&gt;</a>').bind("click", function () {
                return v._displayNewMonth.call(v, this, 1, 0)
            })), f('<div class="dp-calendar"></div>')).bgIframe());
            var n = this.inline ? f(".dp-popup", this.context) : f("#dp-popup");
            if (0 == this.showYearNavigation && f(".dp-nav-prev-year, .dp-nav-next-year", v.context).css("display", "none"), this.displayClose && n.append(f('<a href="#" id="dp-close">' + f.dpText.TEXT_CLOSE + "</a>").bind("click", function () {
                return v._closeCalendar(), !1
            })), this.displayDynamic) {
                var a = '<div id="dp-dynamic">' + f.dpText.TEXT_OR + '<select><option value="">' + f.dpText.TEXT_DATE + '</option><option value="lastWeek">' + f.dpText.TEXT_PREV_WEEK + '</option><option value="thisWeek">' + f.dpText.TEXT_THIS_WEEK + '</option><option value="yesterday">' + f.dpText.TEXT_YESTERDAY + '</option><option value="today">' + f.dpText.TEXT_TODAY + '</option><option value="lastMonth">' + f.dpText.TEXT_PREV_MONTH + '</option><option value="thisMonth">' + f.dpText.TEXT_THIS_MONTH + "</option></select></div>";
                n.append(f(a).change(function () {
                    "" == this.value ? v.clearSelected() : (selected = f(this).children("select").children("option:selected").val(), f(v.ele).val("$" + selected), f(v.ele).parent().prev("select").val("between")), v._closeCalendar()
                }))
            }
            v._renderCalendar(), f(this.ele).trigger("dpDisplayed", n), v.inline || (this.verticalPosition == f.dpConst.POS_BOTTOM && n.css("top", t.top + u.height() - n.height() + v.verticalOffset), this.horizontalPosition == f.dpConst.POS_RIGHT && n.css("left", t.left + u.width() - n.width() + v.horizontalOffset), f(document).bind("mousedown.datepicker", this._checkMouse))
        }
    }, setRenderCallback: function (b) {
        null != b && (b && "function" == typeof b && (b = [b]), this.renderCallback = this.renderCallback.concat(b))
    }, cellRender: function (a, k) {
        var j = this.dpController, d = new Date(k.getTime());
        a.bind("click", function () {
            var l = f(this);
            if (!l.is(".disabled") && (j.setSelected(d, !l.is(".selected") || !j.selectMultiple, !1, !0), j.closeOnSelect)) {
                if (j.settings.autoFocusNextInput) {
                    var o = j.ele, n = !1;
                    f(":input", o.form).each(function () {
                        return n ? (f(this).focus(), !1) : (this == o && (n = !0), void 0)
                    })
                } else {
                    try {
                        j.ele.focus()
                    } catch (m) {
                    }
                }
                j._closeCalendar()
            }
        }), j.isSelected(d) ? (a.addClass("selected"), j.settings.selectWeek && a.parent().addClass("selectedWeek")) : j.selectMultiple && j.numSelected == j.numSelectable && a.addClass("unselectable")
    }, _applyRenderCallbacks: function () {
        var a = this;
        f("td", this.context).each(function () {
            for (var b = 0; a.renderCallback.length > b; b++) {
                $td = f(this), a.renderCallback[b].apply(this, [$td, Date.fromString($td.data("datePickerDate")), a.displayedMonth, a.displayedYear])
            }
        })
    }, _displayNewMonth: function (a, k, j) {
        return f(a).is(".disabled") || this.setDisplayedMonth(this.displayedMonth + k, this.displayedYear + j, !0), a.blur(), !1
    }, _rerenderCalendar: function () {
        this._clearCalendar(), this._renderCalendar()
    }, _renderCalendar: function () {
        if (f("h2", this.context).html(new Date(this.displayedYear, this.displayedMonth, 1).asString(f.dpText.HEADER_FORMAT)), f(".dp-calendar", this.context).renderCalendar(f.extend({}, this.settings, {month: this.displayedMonth, year: this.displayedYear, renderCallback: this.cellRender, dpController: this, hoverClass: this.hoverClass})), this.displayedYear == this.startDate.getFullYear() && this.displayedMonth == this.startDate.getMonth()) {
            f(".dp-nav-prev-year", this.context).addClass("disabled"), f(".dp-nav-prev-month", this.context).addClass("disabled"), f(".dp-calendar td.other-month", this.context).each(function () {
                var c = f(this);
                Number(c.text()) > 20 && c.addClass("disabled")
            });
            var a = this.startDate.getDate();
            f(".dp-calendar td.current-month", this.context).each(function () {
                var b = f(this);
                a > Number(b.text()) && b.addClass("disabled")
            })
        } else {
            f(".dp-nav-prev-year", this.context).removeClass("disabled"), f(".dp-nav-prev-month", this.context).removeClass("disabled");
            var a = this.startDate.getDate();
            if (a > 20) {
                var l = this.startDate.getTime(), k = new Date(l);
                k.addMonths(1), this.displayedYear == k.getFullYear() && this.displayedMonth == k.getMonth() && f(".dp-calendar td.other-month", this.context).each(function () {
                    var c = f(this);
                    l > Date.fromString(c.data("datePickerDate")).getTime() && c.addClass("disabled")
                })
            }
        }
        if (this.displayedYear == this.endDate.getFullYear() && this.displayedMonth == this.endDate.getMonth()) {
            f(".dp-nav-next-year", this.context).addClass("disabled"), f(".dp-nav-next-month", this.context).addClass("disabled"), f(".dp-calendar td.other-month", this.context).each(function () {
                var c = f(this);
                14 > Number(c.text()) && c.addClass("disabled")
            });
            var a = this.endDate.getDate();
            f(".dp-calendar td.current-month", this.context).each(function () {
                var b = f(this);
                Number(b.text()) > a && b.addClass("disabled")
            })
        } else {
            f(".dp-nav-next-year", this.context).removeClass("disabled"), f(".dp-nav-next-month", this.context).removeClass("disabled");
            var a = this.endDate.getDate();
            if (13 > a) {
                var j = new Date(this.endDate.getTime());
                j.addMonths(-1), this.displayedYear == j.getFullYear() && this.displayedMonth == j.getMonth() && f(".dp-calendar td.other-month", this.context).each(function () {
                    var m = f(this), b = Number(m.text());
                    13 > b && b > a && m.addClass("disabled")
                })
            }
        }
        this._applyRenderCallbacks()
    }, _closeCalendar: function (a, d) {
        d && d != this.ele || (f(document).unbind("mousedown.datepicker"), f(document).unbind("keydown.datepicker"), this._clearCalendar(), f("#dp-popup a").unbind(), f("#dp-popup").empty().remove(), a || f(this.ele).trigger("dpClosed", [this.getSelected()]))
    }, _clearCalendar: function () {
        f(".dp-calendar td", this.context).unbind(), f(".dp-calendar", this.context).empty()
    }}), f.dpConst = {SHOW_HEADER_NONE: 0, SHOW_HEADER_SHORT: 1, SHOW_HEADER_LONG: 2, POS_TOP: 0, POS_BOTTOM: 1, POS_LEFT: 0, POS_RIGHT: 1, DP_INTERNAL_FOCUS: "dpInternalFocusTrigger"}, f.dpText = {TEXT_PREV_YEAR: "Previous year", TEXT_PREV_MONTH: "Previous month", TEXT_NEXT_YEAR: "Next year", TEXT_NEXT_MONTH: "Next month", TEXT_CLOSE: "Close", TEXT_CHOOSE_DATE: "Choose date", HEADER_FORMAT: "mmmm yyyy"}, f.dpVersion = "$Id$", f.fn.datePicker.defaults = {month: void 0, year: void 0, showHeader: f.dpConst.SHOW_HEADER_SHORT, startDate: void 0, endDate: void 0, inline: !1, renderCallback: null, createButton: !0, showYearNavigation: !0, closeOnSelect: !0, displayClose: !1, displayDynamic: !1, selectMultiple: !1, numSelectable: Number.MAX_VALUE, clickInput: !1, rememberViewedMonth: !0, selectWeek: !1, verticalPosition: f.dpConst.POS_TOP, horizontalPosition: f.dpConst.POS_LEFT, verticalOffset: 0, horizontalOffset: 0, hoverClass: "dp-hover", autoFocusNextInput: !1}, void 0 == f.fn.bgIframe && (f.fn.bgIframe = function () {
        return this
    }), f(window).bind("unload", function () {
        var a = f.event._dpCache || [];
        for (var d in a) {
            f(a[d].ele)._dpDestroy()
        }
    })
})(jQuery);
(function (a) {
    a.alerts = {verticalOffset: -75, horizontalOffset: 0, repositionOnResize: true, overlayOpacity: 0.01, overlayColor: "#FFF", draggable: true, okButton: " OK ", cancelButton: " Cancel ", dialogClass: null, alert: function (b, c, d) {
        if (c == null) {
            c = "Alert"
        }
        a.alerts._show(c, b, null, "alert", function (e) {
            if (d) {
                d(e)
            }
        })
    }, confirm: function (b, c, d) {
        if (c == null) {
            c = "Confirm"
        }
        a.alerts._show(c, b, null, "confirm", function (e) {
            if (d) {
                d(e)
            }
        })
    }, prompt: function (b, c, d, e) {
        if (d == null) {
            d = "Prompt"
        }
        a.alerts._show(d, b, c, "prompt", function (f) {
            if (e) {
                e(f)
            }
        })
    }, _show: function (g, f, c, b, j) {
        a.alerts._hide();
        a.alerts._overlay("show");
        a("BODY").append('<div id="popup_container"><h1 id="popup_title"></h1><div id="popup_content"><div id="popup_message"></div></div></div>');
        if (a.alerts.dialogClass) {
            a("#popup_container").addClass(a.alerts.dialogClass)
        }
        var h = (a.browser.msie && parseInt(a.browser.version) <= 6) ? "absolute" : "fixed";
        a("#popup_container").css({position: h, zIndex: 99999, padding: 0, margin: 0});
        a("#popup_title").text(g);
        a("#popup_content").addClass(b);
        a("#popup_message").text(f);
        a("#popup_message").html(a("#popup_message").text().replace(/\n/g, "<br />"));
        a("#popup_container").css({minWidth: a("#popup_container").outerWidth(), maxWidth: a("#popup_container").outerWidth()});
        a.alerts._reposition();
        a.alerts._maintainPosition(true);
        switch (b) {
            case"alert":
                a("#popup_message").after('<div id="popup_panel"><input type="button" value="' + a.alerts.okButton + '" id="popup_ok" /></div>');
                a("#popup_ok").click(function () {
                    a.alerts._hide();
                    j(true)
                });
                a("#popup_ok").focus().keypress(function (k) {
                    if (k.keyCode == 13 || k.keyCode == 27) {
                        a("#popup_ok").trigger("click")
                    }
                });
                break;
            case"confirm":
                a("#popup_message").after('<div id="popup_panel"><input type="button" value="' + a.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + a.alerts.cancelButton + '" id="popup_cancel" /></div>');
                a("#popup_ok").click(function () {
                    a.alerts._hide();
                    if (j) {
                        j(true)
                    }
                });
                a("#popup_cancel").click(function () {
                    a.alerts._hide();
                    if (j) {
                        j(false)
                    }
                });
                a("#popup_ok").focus();
                a("#popup_ok, #popup_cancel").keypress(function (k) {
                    if (k.keyCode == 13) {
                        a("#popup_ok").trigger("click")
                    }
                    if (k.keyCode == 27) {
                        a("#popup_cancel").trigger("click")
                    }
                });
                break;
            case"prompt":
                a("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="' + a.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + a.alerts.cancelButton + '" id="popup_cancel" /></div>');
                a("#popup_prompt").width(a("#popup_message").width());
                a("#popup_ok").click(function () {
                    var e = a("#popup_prompt").val();
                    a.alerts._hide();
                    if (j) {
                        j(e)
                    }
                });
                a("#popup_cancel").click(function () {
                    a.alerts._hide();
                    if (j) {
                        j(null)
                    }
                });
                a("#popup_prompt, #popup_ok, #popup_cancel").keypress(function (k) {
                    if (k.keyCode == 13) {
                        a("#popup_ok").trigger("click")
                    }
                    if (k.keyCode == 27) {
                        a("#popup_cancel").trigger("click")
                    }
                });
                if (c) {
                    a("#popup_prompt").val(c)
                }
                a("#popup_prompt").focus().select();
                break
        }
        if (a.alerts.draggable) {
            try {
                a("#popup_container").draggable({handle: a("#popup_title")});
                a("#popup_title").css({cursor: "move"})
            } catch (d) {
            }
        }
    }, _hide: function () {
        a("#popup_container").remove();
        a.alerts._overlay("hide");
        a.alerts._maintainPosition(false)
    }, _overlay: function (b) {
        switch (b) {
            case"show":
                a.alerts._overlay("hide");
                a("BODY").append('<div id="popup_overlay"></div>');
                a("#popup_overlay").css({position: "absolute", zIndex: 99998, top: "0px", left: "0px", width: "100%", height: a(document).height(), background: a.alerts.overlayColor, opacity: a.alerts.overlayOpacity});
                break;
            case"hide":
                a("#popup_overlay").remove();
                break
        }
    }, _reposition: function () {
        var c = ((a(window).height() / 2) - (a("#popup_container").outerHeight() / 2)) + a.alerts.verticalOffset;
        var b = ((a(window).width() / 2) - (a("#popup_container").outerWidth() / 2)) + a.alerts.horizontalOffset;
        if (c < 0) {
            c = 0
        }
        if (b < 0) {
            b = 0
        }
        if (a.browser.msie && parseInt(a.browser.version) <= 6) {
            c = c + a(window).scrollTop()
        }
        a("#popup_container").css({top: c + "px", left: b + "px"});
        a("#popup_overlay").height(a(document).height())
    }, _maintainPosition: function (b) {
        if (a.alerts.repositionOnResize) {
            switch (b) {
                case true:
                    a(window).bind("resize", a.alerts._reposition);
                    break;
                case false:
                    a(window).unbind("resize", a.alerts._reposition);
                    break
            }
        }
    }};
    jAlert = function (b, c, d) {
        a.alerts.alert(b, c, d)
    };
    jConfirm = function (b, c, d) {
        a.alerts.confirm(b, c, d)
    };
    jPrompt = function (b, c, d, e) {
        a.alerts.prompt(b, c, d, e)
    }
})(jQuery);
jQuery.fn.colorize = function (t) {
    options = {altColor: "#f9f9f9", bgColor: "#fff", hoverColor: "#d0dee3", hoverClass: "", hiliteColor: "#fce6a2", hiliteClass: "", oneClick: false, hover: "row", click: "row", banColumns: [], banRows: [], banDataClick: false, ignoreHeaders: true, nested: false};
    jQuery.extend(options, t);
    var s = {addHoverClass: function () {
        this.origColor = this.style.backgroundColor;
        this.style.backgroundColor = "";
        jQuery(this).addClass(options.hoverClass)
    }, addBgHover: function () {
        this.origColor = this.style.backgroundColor;
        this.style.backgroundColor = options.hoverColor
    }, removeHoverClass: function () {
        jQuery(this).removeClass(options.hoverClass);
        this.style.backgroundColor = this.origColor
    }, removeBgHover: function () {
        this.style.backgroundColor = this.origColor
    }, checkHover: function () {
        if (H(this)) {
            return
        }
        if (!this.onfire) {
            this.hover()
        }
    }, checkHoverOut: function () {
        if (!this.onfire) {
            this.removeHover()
        }
    }, highlight: function () {
        if (options.hiliteClass.length > 0 || options.hiliteColor != "none") {
            if (H(this)) {
                return
            }
            this.onfire = true;
            if (options.hiliteClass.length > 0) {
                this.style.backgroundColor = "";
                jQuery(this).addClass(options.hiliteClass).removeClass(options.hoverClass)
            } else {
                if (options.hiliteColor != "none") {
                    this.style.backgroundColor = options.hiliteColor;
                    if (options.hoverClass.length > 0) {
                        jQuery(this).removeClass(options.hoverClass)
                    }
                }
            }
        }
    }, stopHighlight: function () {
        this.onfire = false;
        this.style.backgroundColor = (this.origColor) ? this.origColor : "";
        jQuery(this).removeClass(options.hiliteClass).removeClass(options.hoverClass)
    }};

    function v(c, e, b) {
        var d = a(c, e);
        jQuery.each(d, function (g, f) {
            b.call(f)
        });
        function a(h, j) {
            var g = [];
            for (var f = 0; f < h.length; f++) {
                if (h[f].cellIndex == j) {
                    g.push(h[f])
                }
            }
            return g
        }
    }

    function D(b, c, a) {
        v(b, c.cellIndex, a)
    }

    var A = {toggleColumnClick: function (b) {
        var a = (!this.onfire) ? s.highlight : s.stopHighlight;
        D(b, this, a)
    }, toggleRowClick: function (a) {
        row = jQuery(this).parent().get(0);
        if (!row.onfire) {
            s.highlight.call(row)
        } else {
            s.stopHighlight.call(row)
        }
    }, oneClick: function (a) {
        if (a != null) {
            if (this.isRepeatClick()) {
                this.stopHilite();
                this.cancel()
            } else {
                this.stopHilite();
                this.hilite()
            }
        } else {
            this.hilite()
        }
    }, oneColumnClick: function (c) {
        var b = this.cellIndex;

        function a() {
            return(c.clicked == b)
        }

        x.handleClick(this, c, b, a)
    }, oneRowClick: function (d) {
        var a = jQuery(this).parent().get(0);
        var c = a.rowIndex;

        function b() {
            return(d.rowClicked == c)
        }

        w.handleClick(this, d, a.rowIndex, b)
    }, oneColumnRowClick: function (d) {
        var c = this.cellIndex;
        var b = jQuery(this).parent().get(0);

        function a() {
            return(d.clicked == c && d.rowClicked == b.rowIndex)
        }

        function e() {
            return(d.rowClicked == b.rowIndex && this.cellIndex == d.clicked)
        }

        x.handleClick(this, d, c, a);
        w.handleClick(this, d, b.rowIndex, e)
    }};
    var x = {init: function (c, b, a) {
        this.cell = c;
        this.cells = b;
        this.indx = a
    }, handleClick: function (d, c, a, b) {
        this.init(d, c, a);
        this.isRepeatClick = b;
        A.oneClick.call(this, c.clicked)
    }, stopHilite: function () {
        v(this.cells, this.cells.clicked, s.stopHighlight)
    }, hilite: function () {
        D(this.cells, this.cell, s.highlight);
        this.cells.clicked = this.indx
    }, cancel: function () {
        this.cells.clicked = null
    }};
    var w = {init: function (c, b, a) {
        this.cell = c;
        this.cells = b;
        this.indx = a
    }, handleClick: function (d, c, a, b) {
        this.init(d, c, a);
        this.isRepeatClick = b;
        A.oneClick.call(this, c.rowClicked)
    }, stopHilite: function () {
        s.stopHighlight.call(A.tbl.rows[this.cells.rowClicked])
    }, hilite: function () {
        var a = jQuery(this.cell).parent().get(0);
        if (options.hover == "column") {
            s.addBgHover.call(a)
        }
        s.highlight.call(a);
        this.cells.rowClicked = this.indx
    }, cancel: function () {
        this.cells.rowClicked = null
    }};

    function B() {
        return(this.nodeName == "TD")
    }

    function E() {
        return(jQuery.inArray(this.cellIndex, options.banColumns) != -1)
    }

    function H(b) {
        if (options.banRows.length > 0) {
            var a = jQuery(b).parent().get(0);
            return jQuery.inArray(a.rowIndex, options.banRows) != -1
        } else {
            return false
        }
    }

    function u() {
        this.hover = z.hover;
        this.removeHover = z.removeHover
    }

    function G(b, a) {
        u.call(b);
        b.onmouseover = function () {
            if (E.call(this)) {
                return
            }
            D(a, this, s.checkHover)
        };
        b.onmouseout = function () {
            if (E.call(this)) {
                return
            }
            D(a, this, s.checkHoverOut)
        }
    }

    function F(b, a) {
        row = jQuery(b).parent().get(0);
        u.call(row);
        row.onmouseover = s.checkHover;
        row.onmouseout = s.checkHoverOut
    }

    function J(b, a) {
        F(b, a);
        G(b, a)
    }

    var z = {setHover: function () {
        if (options.hoverClass.length > 0) {
            this.hover = s.addHoverClass;
            this.removeHover = s.removeHoverClass
        } else {
            this.hover = s.addBgHover;
            this.removeHover = s.removeBgHover
        }
    }, getRowClick: function () {
        if (options.oneClick) {
            return A.oneRowClick
        } else {
            return A.toggleRowClick
        }
    }, getColumnClick: function () {
        if (options.oneClick) {
            return A.oneColumnClick
        } else {
            return A.toggleColumnClick
        }
    }, getRowColClick: function () {
        return A.oneColumnRowClick
    }};
    var I = {clickFunc: C(), handleHoverEvents: y()};

    function y() {
        if (options.hover == "column") {
            return G
        } else {
            if (options.hover == "cross") {
                return J
            } else {
                return F
            }
        }
    }

    function C() {
        if (options.click == "column") {
            return z.getColumnClick()
        } else {
            if (options.click == "cross") {
                return z.getRowColClick()
            } else {
                return z.getRowClick()
            }
        }
    }

    return this.each(function () {
        if (options.altColor != "none") {
            var b, a;
            b = a = (options.ignoreHeaders) ? "tr:has(td)" : "tr";
            if (options.nested) {
                b += ":nth-child(odd)";
                a += ":nth-child(even)"
            } else {
                b += ":odd";
                a += ":even"
            }
            jQuery(this).find(a).css("background", options.bgColor);
            jQuery(this).find(b).css("background", options.altColor)
        }
        if (options.columns) {
            alert("The 'columns' option is deprecated.\nPlease use the 'click' and 'hover' options instead.")
        }
        if (jQuery(this).find("thead tr:last th").length > 0) {
            var c = jQuery(this).find("td, thead tr:last th")
        } else {
            var c = jQuery(this).find("td,th")
        }
        c.clicked = null;
        if (jQuery.inArray("last", options.banColumns) != -1) {
            if (this.rows.length > 0) {
                options.banColumns.push(this.rows[0].cells.length - 1)
            }
        }
        z.setHover();
        A.tbl = this;
        jQuery.each(c, function (d, e) {
            I.handleHoverEvents(this, c);
            $(this).bind("click", function (f) {
                if (E.call(this)) {
                    return
                }
                if (options.banDataClick && B.call(this)) {
                    return
                }
                I.clickFunc.call(this, c)
            })
        })
    })
};