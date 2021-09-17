function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

if (window.jQuery) {
  $(document).ready(function () {
    $('.add_more').click(function (e) {
      e.preventDefault();
      $(this).before("<br><input name='attachments[]' type='file'/>");
    });
    $('.fa-share-square').click(function (e) {
      $('#shareModal').show();
    });
    $('#share-close').click(function (e) {
      $('#shareModal').hide();
    });
    $('#favorite-close').click(function (e) {
      $('#favoriteModal').hide();
    });
    $('.fa-flag').click(function (e) {
      $('#flagModal').show();
    });
    $('#flag-close').click(function (e) {
      $('#flagModal').hide();
    });
    $('#survey-close').click(function (e) {
      $('#surveyModal').hide();
    });
    $('input[type="checkbox"]').click(function (e) {
      $('#side-submit').show();
    }); //for accordions in the resourcelist

    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;

        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          //adding 500 to just give extra max-height. 
          panel.style.maxHeight = panel.scrollHeight + 500 + "px";
        }
      });
    }

    $('#resource-subjects').trigger('click'); //Resources

    $(document).on('click', '#resource-information-section .pagination a', function (event) {
      event.preventDefault();
      $('li').removeClass('active');
      $(this).parent('li').addClass('active');
      var myurl = $(this).attr('href');
      getData(myurl);
    });
    $(document).on('click', '#side-form ul li', function (event) {
      var subject_area = $(this).data('type') == "subject" ? $(this).attr('value') : "";
      var level = $(this).data('type') == "level" ? $(this).attr('value') : "";
      var type = $(this).data('type') == "type" ? $(this).attr('value') : "";
      var myurl = $(this).data('link');
      $('.resource-list ul li').removeClass('active-header');
      $(this).addClass('active-header');
      $(".se-pre-con").show();
      $.ajax({
        url: myurl,
        data: {
          subject_area: subject_area,
          level: level,
          type: type
        },
        type: "get",
        datatype: "html"
      }).done(function (data) {
        $(".se-pre-con").fadeOut("slow");

        if (subject_area) {
          $('#subject-' + subject_area).toggle();
        }

        $("#resource-information-section").empty().html(data);
      }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
      });
    });
  });
}

function getData(url) {
  $(".se-pre-con").show();
  $.ajax({
    url: url,
    type: "get",
    datatype: "html"
  }).done(function (data) {
    $(".se-pre-con").fadeOut("slow");
    $("#resource-information-section").empty().html(data);
    $('html, body').animate({
      scrollTop: 0
    }, 0);
  }).fail(function (jqXHR, ajaxOptions, thrownError) {
    alert('No response from server');
  });
}

function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}

function favorite(elementId, baseUrl, resourceId, userId) {
  var csrf = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    type: "POST",
    url: baseUrl,
    data: {
      resourceId: resourceId,
      userId: userId,
      _token: csrf
    },
    // appears as $_GET['id'] @ your backend side
    success: function success(data) {
      console.log(data);
      var obj = JSON.parse(data); // data is ur summary

      if (obj == "added") {
        $('#' + elementId).addClass("active");
      } else if (obj == "deleted") {
        $('#' + elementId).removeClass("active");
      } else if (obj == "notloggedin") {
        $('#favoriteModal').show();
      }
    }
  });
}

function showHide(itself, elementId) {
  var theElement = document.getElementById(elementId);

  if (theElement.style.display === "none") {
    theElement.style.display = "block";
  } else {
    theElement.style.display = "none";
  }

  if (itself.className.indexOf("js-fa-plus") == -1) {
    itself.className += " js-fa-plus";
  } else {
    itself.className = itself.className.replace(" js-fa-plus", " fa-minus");
  }
}

function fnTest(check, cchild) {
  if ($(check).is(':checked')) {
    $(check).siblings('#'.cchild).find('.js-child').prop("checked", true);
  } else {
    $(check).siblings('#'.cchild).find('.js-child').prop("checked", false);
  }
}

function changeContent(DivContent, methodUrl, parameters) {
  $.ajax({
    type: "POST",
    url: methodUrl,
    data: "id=" + id,
    // appears as $_GET['id'] @ your backend side
    success: function success(data) {
      // data is ur summary
      $('#' + DivContent).html(data);
    }
  });
}

function populate(element, targetId, targetContent) {
  var selectedOption = element.options[element.selectedIndex].value;
  var targetLocation = document.getElementById(targetId);
  var textInput = document.getElementById('js-text-city');

  if (selectedOption == 256) {
    textInput.style.display = 'none';
    var i;

    for (i = 0; i < targetContent.length; i++) {
      var item = new Option(targetContent[i].name, targetContent[i].tnid);
      targetLocation.options.add(item);
    }

    targetLocation.style.display = 'block';
  } else {
    targetLocation.style.display = 'none';
    textInput.style.display = 'block';
  }
}

function split(val) {
  return val.split(/,\s*/);
}

function extractLast(term) {
  return split(term).pop();
}

function bringMeAttr(id, url) {
  $("#" + id) // don't navigate away from the field on tab when selecting an item
  .on("keydown", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  }).autocomplete({
    source: function source(request, response) {
      $.getJSON(url, {
        term: extractLast(request.term)
      }, response);
    },
    search: function search() {
      // custom minLength
      var term = extractLast(this.value);

      if (term.length < 2) {
        return false;
      }
    },
    focus: function focus() {
      // prevent value inserted on focus
      return false;
    },
    select: function select(event, ui) {
      var terms = split(this.value); // remove the current input

      terms.pop(); // add the selected item

      terms.push(ui.item.value); // add placeholder to get the comma-and-space at the end

      terms.push("");
      this.value = terms.join(", ");
      return false;
    }
  });
}
/*! lazysizes - v4.1.6 */


!function (a, b) {
  var c = b(a, a.document);
  a.lazySizes = c, "object" == (typeof module === "undefined" ? "undefined" : _typeof(module)) && module.exports && (module.exports = c);
}(window, function (a, b) {
  "use strict";

  if (b.getElementsByClassName) {
    var c,
        d,
        e = b.documentElement,
        f = a.Date,
        g = a.HTMLPictureElement,
        h = "addEventListener",
        i = "getAttribute",
        j = a[h],
        k = a.setTimeout,
        l = a.requestAnimationFrame || k,
        m = a.requestIdleCallback,
        n = /^picture$/i,
        o = ["load", "error", "lazyincluded", "_lazyloaded"],
        p = {},
        q = Array.prototype.forEach,
        r = function r(a, b) {
      return p[b] || (p[b] = new RegExp("(\\s|^)" + b + "(\\s|$)")), p[b].test(a[i]("class") || "") && p[b];
    },
        s = function s(a, b) {
      r(a, b) || a.setAttribute("class", (a[i]("class") || "").trim() + " " + b);
    },
        t = function t(a, b) {
      var c;
      (c = r(a, b)) && a.setAttribute("class", (a[i]("class") || "").replace(c, " "));
    },
        u = function u(a, b, c) {
      var d = c ? h : "removeEventListener";
      c && u(a, b), o.forEach(function (c) {
        a[d](c, b);
      });
    },
        v = function v(a, d, e, f, g) {
      var h = b.createEvent("Event");
      return e || (e = {}), e.instance = c, h.initEvent(d, !f, !g), h.detail = e, a.dispatchEvent(h), h;
    },
        w = function w(b, c) {
      var e;
      !g && (e = a.picturefill || d.pf) ? (c && c.src && !b[i]("srcset") && b.setAttribute("srcset", c.src), e({
        reevaluate: !0,
        elements: [b]
      })) : c && c.src && (b.src = c.src);
    },
        x = function x(a, b) {
      return (getComputedStyle(a, null) || {})[b];
    },
        y = function y(a, b, c) {
      for (c = c || a.offsetWidth; c < d.minSize && b && !a._lazysizesWidth;) {
        c = b.offsetWidth, b = b.parentNode;
      }

      return c;
    },
        z = function () {
      var a,
          c,
          d = [],
          e = [],
          f = d,
          g = function g() {
        var b = f;

        for (f = d.length ? e : d, a = !0, c = !1; b.length;) {
          b.shift()();
        }

        a = !1;
      },
          h = function h(d, e) {
        a && !e ? d.apply(this, arguments) : (f.push(d), c || (c = !0, (b.hidden ? k : l)(g)));
      };

      return h._lsFlush = g, h;
    }(),
        A = function A(a, b) {
      return b ? function () {
        z(a);
      } : function () {
        var b = this,
            c = arguments;
        z(function () {
          a.apply(b, c);
        });
      };
    },
        B = function B(a) {
      var b,
          c = 0,
          e = d.throttleDelay,
          g = d.ricTimeout,
          h = function h() {
        b = !1, c = f.now(), a();
      },
          i = m && g > 49 ? function () {
        m(h, {
          timeout: g
        }), g !== d.ricTimeout && (g = d.ricTimeout);
      } : A(function () {
        k(h);
      }, !0);

      return function (a) {
        var d;
        (a = !0 === a) && (g = 33), b || (b = !0, d = e - (f.now() - c), d < 0 && (d = 0), a || d < 9 ? i() : k(i, d));
      };
    },
        C = function C(a) {
      var b,
          c,
          d = 99,
          e = function e() {
        b = null, a();
      },
          g = function g() {
        var a = f.now() - c;
        a < d ? k(g, d - a) : (m || e)(e);
      };

      return function () {
        c = f.now(), b || (b = k(g, d));
      };
    };

    !function () {
      var b,
          c = {
        lazyClass: "lazyload",
        loadedClass: "lazyloaded",
        loadingClass: "lazyloading",
        preloadClass: "lazypreload",
        errorClass: "lazyerror",
        autosizesClass: "lazyautosizes",
        srcAttr: "data-src",
        srcsetAttr: "data-srcset",
        sizesAttr: "data-sizes",
        minSize: 40,
        customMedia: {},
        init: !0,
        expFactor: 1.5,
        hFac: .8,
        loadMode: 2,
        loadHidden: !0,
        ricTimeout: 0,
        throttleDelay: 125
      };
      d = a.lazySizesConfig || a.lazysizesConfig || {};

      for (b in c) {
        b in d || (d[b] = c[b]);
      }

      a.lazySizesConfig = d, k(function () {
        d.init && F();
      });
    }();

    var D = function () {
      var g,
          l,
          m,
          o,
          p,
          y,
          D,
          F,
          G,
          H,
          I,
          J,
          K = /^img$/i,
          L = /^iframe$/i,
          M = "onscroll" in a && !/(gle|ing)bot/.test(navigator.userAgent),
          N = 0,
          O = 0,
          P = 0,
          Q = -1,
          R = function R(a) {
        P--, a && a.target && u(a.target, R), (!a || P < 0 || !a.target) && (P = 0);
      },
          S = function S(a) {
        return null == J && (J = "hidden" == x(b.body, "visibility")), J || "hidden" != x(a.parentNode, "visibility") && "hidden" != x(a, "visibility");
      },
          T = function T(a, c) {
        var d,
            f = a,
            g = S(a);

        for (F -= c, I += c, G -= c, H += c; g && (f = f.offsetParent) && f != b.body && f != e;) {
          (g = (x(f, "opacity") || 1) > 0) && "visible" != x(f, "overflow") && (d = f.getBoundingClientRect(), g = H > d.left && G < d.right && I > d.top - 1 && F < d.bottom + 1);
        }

        return g;
      },
          U = function U() {
        var a,
            f,
            h,
            j,
            k,
            m,
            n,
            p,
            q,
            r,
            s,
            t,
            u = c.elements;

        if ((o = d.loadMode) && P < 8 && (a = u.length)) {
          for (f = 0, Q++, r = !d.expand || d.expand < 1 ? e.clientHeight > 500 && e.clientWidth > 500 ? 500 : 370 : d.expand, s = r * d.expFactor, t = d.hFac, J = null, O < s && P < 1 && Q > 2 && o > 2 && !b.hidden ? (O = s, Q = 0) : O = o > 1 && Q > 1 && P < 6 ? r : N; f < a; f++) {
            if (u[f] && !u[f]._lazyRace) if (M) {
              if ((p = u[f][i]("data-expand")) && (m = 1 * p) || (m = O), q !== m && (y = innerWidth + m * t, D = innerHeight + m, n = -1 * m, q = m), h = u[f].getBoundingClientRect(), (I = h.bottom) >= n && (F = h.top) <= D && (H = h.right) >= n * t && (G = h.left) <= y && (I || H || G || F) && (d.loadHidden || S(u[f])) && (l && P < 3 && !p && (o < 3 || Q < 4) || T(u[f], m))) {
                if (aa(u[f]), k = !0, P > 9) break;
              } else !k && l && !j && P < 4 && Q < 4 && o > 2 && (g[0] || d.preloadAfterLoad) && (g[0] || !p && (I || H || G || F || "auto" != u[f][i](d.sizesAttr))) && (j = g[0] || u[f]);
            } else aa(u[f]);
          }

          j && !k && aa(j);
        }
      },
          V = B(U),
          W = function W(a) {
        s(a.target, d.loadedClass), t(a.target, d.loadingClass), u(a.target, Y), v(a.target, "lazyloaded");
      },
          X = A(W),
          Y = function Y(a) {
        X({
          target: a.target
        });
      },
          Z = function Z(a, b) {
        try {
          a.contentWindow.location.replace(b);
        } catch (c) {
          a.src = b;
        }
      },
          $ = function $(a) {
        var b,
            c = a[i](d.srcsetAttr);
        (b = d.customMedia[a[i]("data-media") || a[i]("media")]) && a.setAttribute("media", b), c && a.setAttribute("srcset", c);
      },
          _ = A(function (a, b, c, e, f) {
        var g, h, j, l, o, p;
        (o = v(a, "lazybeforeunveil", b)).defaultPrevented || (e && (c ? s(a, d.autosizesClass) : a.setAttribute("sizes", e)), h = a[i](d.srcsetAttr), g = a[i](d.srcAttr), f && (j = a.parentNode, l = j && n.test(j.nodeName || "")), p = b.firesLoad || "src" in a && (h || g || l), o = {
          target: a
        }, p && (u(a, R, !0), clearTimeout(m), m = k(R, 2500), s(a, d.loadingClass), u(a, Y, !0)), l && q.call(j.getElementsByTagName("source"), $), h ? a.setAttribute("srcset", h) : g && !l && (L.test(a.nodeName) ? Z(a, g) : a.src = g), f && (h || l) && w(a, {
          src: g
        })), a._lazyRace && delete a._lazyRace, t(a, d.lazyClass), z(function () {
          (!p || a.complete && a.naturalWidth > 1) && (p ? R(o) : P--, W(o));
        }, !0);
      }),
          aa = function aa(a) {
        var b,
            c = K.test(a.nodeName),
            e = c && (a[i](d.sizesAttr) || a[i]("sizes")),
            f = "auto" == e;
        (!f && l || !c || !a[i]("src") && !a.srcset || a.complete || r(a, d.errorClass) || !r(a, d.lazyClass)) && (b = v(a, "lazyunveilread").detail, f && E.updateElem(a, !0, a.offsetWidth), a._lazyRace = !0, P++, _(a, b, f, e, c));
      },
          ba = function ba() {
        if (!l) {
          if (f.now() - p < 999) return void k(ba, 999);
          var a = C(function () {
            d.loadMode = 3, V();
          });
          l = !0, d.loadMode = 3, V(), j("scroll", function () {
            3 == d.loadMode && (d.loadMode = 2), a();
          }, !0);
        }
      };

      return {
        _: function _() {
          p = f.now(), c.elements = b.getElementsByClassName(d.lazyClass), g = b.getElementsByClassName(d.lazyClass + " " + d.preloadClass), j("scroll", V, !0), j("resize", V, !0), a.MutationObserver ? new MutationObserver(V).observe(e, {
            childList: !0,
            subtree: !0,
            attributes: !0
          }) : (e[h]("DOMNodeInserted", V, !0), e[h]("DOMAttrModified", V, !0), setInterval(V, 999)), j("hashchange", V, !0), ["focus", "mouseover", "click", "load", "transitionend", "animationend", "webkitAnimationEnd"].forEach(function (a) {
            b[h](a, V, !0);
          }), /d$|^c/.test(b.readyState) ? ba() : (j("load", ba), b[h]("DOMContentLoaded", V), k(ba, 2e4)), c.elements.length ? (U(), z._lsFlush()) : V();
        },
        checkElems: V,
        unveil: aa
      };
    }(),
        E = function () {
      var a,
          c = A(function (a, b, c, d) {
        var e, f, g;
        if (a._lazysizesWidth = d, d += "px", a.setAttribute("sizes", d), n.test(b.nodeName || "")) for (e = b.getElementsByTagName("source"), f = 0, g = e.length; f < g; f++) {
          e[f].setAttribute("sizes", d);
        }
        c.detail.dataAttr || w(a, c.detail);
      }),
          e = function e(a, b, d) {
        var e,
            f = a.parentNode;
        f && (d = y(a, f, d), e = v(a, "lazybeforesizes", {
          width: d,
          dataAttr: !!b
        }), e.defaultPrevented || (d = e.detail.width) && d !== a._lazysizesWidth && c(a, f, e, d));
      },
          f = function f() {
        var b,
            c = a.length;
        if (c) for (b = 0; b < c; b++) {
          e(a[b]);
        }
      },
          g = C(f);

      return {
        _: function _() {
          a = b.getElementsByClassName(d.autosizesClass), j("resize", g);
        },
        checkElems: g,
        updateElem: e
      };
    }(),
        F = function F() {
      F.i || (F.i = !0, E._(), D._());
    };

    return c = {
      cfg: d,
      autoSizer: E,
      loader: D,
      init: F,
      uP: w,
      aC: s,
      rC: t,
      hC: r,
      fire: v,
      gW: y,
      rAF: z
    };
  }
});
