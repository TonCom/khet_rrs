/*
@license
dhtmlxScheduler v.5.0.0 Stardard

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
scheduler.attachEvent("onTemplatesReady", function() {
    this.layers.sort(function(e, t) {
            return e.zIndex - t.zIndex
        }), scheduler._dp_init = function(e) {
            e._methods = ["_set_event_text_style", "", "changeEventId", "deleteEvent"], this.attachEvent("onEventAdded", function(t) {
                !this._loading && this.validId(t) && this.getEvent(t) && this.getEvent(t).layer == e.layer && e.setUpdated(t, !0, "inserted")
            }), this.attachEvent("onBeforeEventDelete", function(t) {
                if (this.getEvent(t) && this.getEvent(t).layer == e.layer) {
                    if (!this.validId(t)) return;
                    var a = e.getState(t);
                    return "inserted" == a || this._new_event ? (e.setUpdated(t, !1), !0) : "deleted" == a ? !1 : "true_deleted" == a ? !0 : (e.setUpdated(t, !0, "deleted"), !1)
                }
                return !0
            }), this.attachEvent("onEventChanged", function(t) {
                !this._loading && this.validId(t) && this.getEvent(t) && this.getEvent(t).layer == e.layer && e.setUpdated(t, !0, "updated")
            }), e._getRowData = function(e, t) {
                var a = this.obj.getEvent(e),
                    i = {};
                for (var r in a) 0 !== r.indexOf("_") && (a[r] && a[r].getUTCFullYear ? i[r] = this.obj.templates.xml_format(a[r]) : i[r] = a[r]);
                return i;
            }, e._clearUpdateFlag = function() {}, e.attachEvent("insertCallback", scheduler._update_callback), e.attachEvent("updateCallback", scheduler._update_callback), e.attachEvent("deleteCallback", function(e, t) {
                this.obj.setUserData(t, this.action_param, "true_deleted"), this.obj.deleteEvent(t)
            })
        },
        function() {
            var e = function(t) {
                if (null === t || "object" != typeof t) return t;
                var a = new t.constructor;
                for (var i in t) a[i] = e(t[i]);
                return a
            };
            scheduler._dataprocessors = [], scheduler._layers_zindex = {};
            for (var t = 0; t < scheduler.layers.length; t++) {
                if (scheduler.config["lightbox_" + scheduler.layers[t].name] = {}, scheduler.config["lightbox_" + scheduler.layers[t].name].sections = e(scheduler.config.lightbox.sections), scheduler._layers_zindex[scheduler.layers[t].name] = scheduler.config.inital_layer_zindex || 5 + 3 * t, scheduler.layers[t].url) {
                    var a = new dataProcessor(scheduler.layers[t].url);
                    a.layer = scheduler.layers[t].name, scheduler._dataprocessors.push(a), scheduler._dataprocessors[t].init(scheduler)
                }
                scheduler.layers[t].isDefault && (scheduler.defaultLayer = scheduler.layers[t].name);
            }
        }(), scheduler.showLayer = function(e) {
            this.toggleLayer(e, !0)
        }, scheduler.hideLayer = function(e) {
            this.toggleLayer(e, !1)
        }, scheduler.toggleLayer = function(e, t) {
            var a = this.getLayer(e);
            "undefined" != typeof t ? a.visible = !!t : a.visible = !a.visible, this.setCurrentView(this._date, this._mode)
        }, scheduler.getLayer = function(e) {
            var t, a;
            "string" == typeof e && (a = e), "object" == typeof e && (a = e.layer);
            for (var i = 0; i < scheduler.layers.length; i++) scheduler.layers[i].name == a && (t = scheduler.layers[i]);
            return t
        }, scheduler.attachEvent("onBeforeLightbox", function(e) {
            var t = this.getEvent(e);
            return this.config.lightbox.sections = this.config["lightbox_" + t.layer].sections, scheduler.resetLightbox(), !0
        }), scheduler.attachEvent("onClick", function(e, t) {
            var a = scheduler.getEvent(e);
            return !scheduler.getLayer(a.layer).noMenu
        }), scheduler.attachEvent("onEventCollision", function(e, t) {
            var a = this.getLayer(e);
            if (!a.checkCollision) return !1;
            for (var i = 0, r = 0; r < t.length; r++) t[r].layer == a.name && t[r].id != e.id && i++;
            return i >= scheduler.config.collision_limit
        }), scheduler.addEvent = function(e, t, a, i, r) {
            var n = e;
            1 != arguments.length && (n = r || {}, n.start_date = e, n.end_date = t, n.text = a, n.id = i, n.layer = this.defaultLayer), n.id = n.id || scheduler.uid(), n.text = n.text || "", "string" == typeof n.start_date && (n.start_date = this.templates.api_date(n.start_date)), "string" == typeof n.end_date && (n.end_date = this.templates.api_date(n.end_date)), n._timed = this.isOneDayEvent(n);
            var s = !this._events[n.id];
            this._events[n.id] = n, this.event_updated(n), this._loading || this.callEvent(s ? "onEventAdded" : "onEventChanged", [n.id, n])
        }, this._evs_layer = {};
    for (var e = 0; e < this.layers.length; e++) this._evs_layer[this.layers[e].name] = [];
    scheduler.addEventNow = function(e, t, a) {
        var i = {};
        "object" == typeof e && (i = e, e = null);
        var r = 6e4 * (this.config.event_duration || this.config.time_step);
        e || (e = Math.round(scheduler._currentDate().valueOf() / r) * r);
        var n = new Date(e);
        if (!t) {
            var s = this.config.first_hour;
            s > n.getHours() && (n.setHours(s), e = n.valueOf()), t = e + r
        }
        i.start_date = i.start_date || n, i.end_date = i.end_date || new Date(t), i.text = i.text || this.locale.labels.new_event, i.id = this._drag_id = this.uid(),
            i.layer = this.defaultLayer, this._drag_mode = "new-size", this._loading = !0, this.addEvent(i), this.callEvent("onEventCreated", [this._drag_id, a]), this._loading = !1, this._drag_event = {}, this._on_mouse_up(a)
    }, scheduler._t_render_view_data = function(e) {
        if (this.config.multi_day && !this._table_view) {
            for (var t = [], a = [], i = 0; i < e.length; i++) e[i]._timed ? t.push(e[i]) : a.push(e[i]);
            this._table_view = !0, this.render_data(a), this._table_view = !1, this.render_data(t)
        } else this.render_data(e)
    }, scheduler.render_view_data = function() {
        if (this._not_render) return void(this._render_wait = !0);
        this._render_wait = !1, this.clear_view(), this._evs_layer = {};
        for (var e = 0; e < this.layers.length; e++) this._evs_layer[this.layers[e].name] = [];
        for (var t = this.get_visible_events(), e = 0; e < t.length; e++) this._evs_layer[t[e].layer] && this._evs_layer[t[e].layer].push(t[e]);
        if ("month" == this._mode) {
            for (var a = [], e = 0; e < this.layers.length; e++) this.layers[e].visible && (a = a.concat(this._evs_layer[this.layers[e].name]));
            this._t_render_view_data(a)
        } else
            for (var e = 0; e < this.layers.length; e++)
                if (this.layers[e].visible) {
                    var i = this._evs_layer[this.layers[e].name];
                    this._t_render_view_data(i)
                }
    }, scheduler._render_v_bar = function(e, t, a, i, r, n, s, d, o) {
        var l = e.id; - 1 == s.indexOf("<div class=") && (s = scheduler.templates["event_header_" + e.layer] ? scheduler.templates["event_header_" + e.layer](e.start_date, e.end_date, e) : s), -1 == d.indexOf("<div class=") && (d = scheduler.templates["event_text_" + e.layer] ? scheduler.templates["event_text_" + e.layer](e.start_date, e.end_date, e) : d);
        var h = document.createElement("div"),
            c = "dhx_cal_event",
            _ = scheduler.templates["event_class_" + e.layer] ? scheduler.templates["event_class_" + e.layer](e.start_date, e.end_date, e) : scheduler.templates.event_class(e.start_date, e.end_date, e);
        _ && (c = c + " " + _);
        var u = scheduler._border_box_bvents(),
            v = i - 2,
            g = u ? v : i - 4,
            f = u ? v : i - 6,
            m = u ? v : i - (this._quirks ? 4 : 14),
            p = u ? v - 2 : i - 8,
            y = u ? r - this.xy.event_header_height : r - (this._quirks ? 20 : 30) + 1,
            b = '<div event_id="' + l + '" class="' + c + '" style="position:absolute; top:' + a + "px; left:" + t + "px; width:" + g + "px; height:" + r + "px;" + (n || "") + '">';
        return b += '<div class="dhx_header" style=" width:' + f + 'px;" >&nbsp;</div>', b += '<div class="dhx_title">' + s + "</div>", b += '<div class="dhx_body" style=" width:' + m + "px; height:" + y + 'px;">' + d + "</div>",
            b += '<div class="dhx_footer" style=" width:' + p + "px;" + (o ? " margin-top:-1px;" : "") + '" ></div></div>', h.innerHTML = b, h.style.zIndex = 100, h.firstChild
    }, scheduler.render_event_bar = function(e) {
        var t = this._els.dhx_cal_data[0],
            a = this._colsS[e._sday],
            i = this._colsS[e._eday];
        i == a && (i = this._colsS[e._eday + 1]);
        var r = this.xy.bar_height,
            n = this._colsS.heights[e._sweek] + (this._colsS.height ? this.xy.month_scale_height + 2 : 2) + e._sorder * r,
            s = document.createElement("div"),
            d = e._timed ? "dhx_cal_event_clear" : "dhx_cal_event_line",
            o = scheduler.templates["event_class_" + e.layer] ? scheduler.templates["event_class_" + e.layer](e.start_date, e.end_date, e) : scheduler.templates.event_class(e.start_date, e.end_date, e);
        o && (d = d + " " + o);
        var l = '<div event_id="' + e.id + '" class="' + d + '" style="position:absolute; top:' + n + "px; left:" + a + "px; width:" + (i - a - 15) + "px;" + (e._text_style || "") + '">';
        e._timed && (l += scheduler.templates["event_bar_date_" + e.layer] ? scheduler.templates["event_bar_date_" + e.layer](e.start_date, e.end_date, e) : scheduler.templates.event_bar_date(e.start_date, e.end_date, e)), l += scheduler.templates["event_bar_text_" + e.layer] ? scheduler.templates["event_bar_text_" + e.layer](e.start_date, e.end_date, e) : scheduler.templates.event_bar_text(e.start_date, e.end_date, e) + "</div>)",
            l += "</div>", s.innerHTML = l, this._rendered.push(s.firstChild), t.appendChild(s.firstChild)
    }, scheduler.render_event = function(e) {
        var t = scheduler.xy.menu_width;
        if (scheduler.getLayer(e.layer).noMenu && (t = 0), !(e._sday < 0)) {
            var a = scheduler.locate_holder(e._sday);
            if (a) {
                var i = 60 * e.start_date.getHours() + e.start_date.getMinutes(),
                    r = 60 * e.end_date.getHours() + e.end_date.getMinutes() || 60 * scheduler.config.last_hour,
                    n = Math.round((60 * i * 1e3 - 60 * this.config.first_hour * 60 * 1e3) * this.config.hour_size_px / 36e5) % (24 * this.config.hour_size_px) + 1,
                    s = Math.max(scheduler.xy.min_event_height, (r - i) * this.config.hour_size_px / 60) + 1,
                    d = Math.floor((a.clientWidth - t) / e._count),
                    o = e._sorder * d + 1;
                e._inner || (d *= e._count - e._sorder);
                var l = this._render_v_bar(e.id, t + o, n, d, s, e._text_style, scheduler.templates.event_header(e.start_date, e.end_date, e), scheduler.templates.event_text(e.start_date, e.end_date, e));
                if (this._rendered.push(l), a.appendChild(l), o = o + parseInt(a.style.left, 10) + t, n += this._dy_shift, l.style.zIndex = this._layers_zindex[e.layer], this._edit_id == e.id) {
                    l.style.zIndex = parseInt(l.style.zIndex) + 1;
                    var h = l.style.zIndex;
                    d = Math.max(d - 4, scheduler.xy.editor_width);
                    var l = document.createElement("div");
                    l.setAttribute("event_id", e.id), this.set_xy(l, d, s - 20, o, n + 14), l.className = "dhx_cal_editor", l.style.zIndex = h;
                    var c = document.createElement("div");
                    this.set_xy(c, d - 6, s - 26), c.style.cssText += ";margin:2px 2px 2px 2px;overflow:hidden;", c.style.zIndex = h, l.appendChild(c), this._els.dhx_cal_data[0].appendChild(l), this._rendered.push(l), c.innerHTML = "<textarea class='dhx_cal_editor'>" + e.text + "</textarea>", this._quirks7 && (c.firstChild.style.height = s - 12 + "px"), this._editor = c.firstChild, this._editor.onkeypress = function(e) {
                        if ((e || event).shiftKey) return !0;
                        var t = (e || event).keyCode;
                        t == scheduler.keys.edit_save && scheduler.editStop(!0), t == scheduler.keys.edit_cancel && scheduler.editStop(!1)
                    }, this._editor.onselectstart = function(e) {
                        return (e || event).cancelBubble = !0, !0
                    }, c.firstChild.focus(), this._els.dhx_cal_data[0].scrollLeft = 0, c.firstChild.select()
                }
                if (this._select_id == e.id) {
                    l.style.zIndex = parseInt(l.style.zIndex) + 1;
                    for (var _ = this.config["icons_" + (this._edit_id == e.id ? "edit" : "select")], u = "", v = 0; v < _.length; v++) u += "<div class='dhx_menu_icon " + _[v] + "' title='" + this.locale.labels[_[v]] + "'></div>";
                    var g = this._render_v_bar(e.id, o - t + 1, n, t, 20 * _.length + 26, "", "<div class='dhx_menu_head'></div>", u, !0);
                    g.style.left = o - t + 1, g.style.zIndex = l.style.zIndex, this._els.dhx_cal_data[0].appendChild(g), this._rendered.push(g)
                }
            }
        }
    }, scheduler.filter_agenda = function(e, t) {
        var a = scheduler.getLayer(t.layer);
        return a && a.visible
    }
});
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_layer.js.map