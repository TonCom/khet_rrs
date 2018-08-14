/*
@license
dhtmlxScheduler v.5.0.0 Stardard

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
scheduler.date.add_agenda = function(e) {
    return scheduler.date.add(e, 1, "year")
}, scheduler.templates.agenda_time = function(e, t, i) {
    return i._timed ? this.day_date(i.start_date, i.end_date, i) + " " + this.event_date(e) : scheduler.templates.day_date(e) + " &ndash; " + scheduler.templates.day_date(t)
}, scheduler.templates.agenda_text = function(e, t, i) {
    return i.text
}, scheduler.templates.agenda_date = function() {
    return ""
}, scheduler.date.agenda_start = function() {
    return scheduler.date.date_part(scheduler._currentDate())
}, scheduler.attachEvent("onTemplatesReady", function() {
    function e(e) {
        if (e) {
            var t = scheduler.locale.labels,
                i = scheduler._waiAria.agendaHeadAttrString(),
                a = scheduler._waiAria.agendaHeadDateString(t.date),
                r = scheduler._waiAria.agendaHeadDescriptionString(t.description);
            scheduler._els.dhx_cal_header[0].innerHTML = "<div " + i + " class='dhx_agenda_line'><div " + a + ">" + t.date + "</div><span style='padding-left:25px' " + r + ">" + t.description + "</span></div>", scheduler._table_view = !0, scheduler.set_sizes()
        }
    }

    function t() {
        var e = (scheduler._date, scheduler.get_visible_events());
        e.sort(function(e, t) {
            return e.start_date > t.start_date ? 1 : -1
        });
        for (var t, i = scheduler._waiAria.agendaDataAttrString(), a = "<div class='dhx_agenda_area' " + i + ">", r = 0; r < e.length; r++) {
            var s = e[r],
                n = s.color ? "background:" + s.color + ";" : "",
                d = s.textColor ? "color:" + s.textColor + ";" : "",
                l = scheduler.templates.event_class(s.start_date, s.end_date, s);
            t = scheduler._waiAria.agendaEventAttrString(s);
            var o = scheduler._waiAria.agendaDetailsBtnString();
            a += "<div " + t + " class='dhx_agenda_line" + (l ? " " + l : "") + "' event_id='" + s.id + "' style='" + d + n + (s._text_style || "") + "'><div class='dhx_agenda_event_time'>" + scheduler.templates.agenda_time(s.start_date, s.end_date, s) + "</div>",
                a += "<div " + o + " class='dhx_event_icon icon_details'>&nbsp;</div>", a += "<span>" + scheduler.templates.agenda_text(s.start_date, s.end_date, s) + "</span></div>"
        }
        a += "<div class='dhx_v_border'></div></div>", scheduler._els.dhx_cal_data[0].innerHTML = a, scheduler._els.dhx_cal_data[0].childNodes[0].scrollTop = scheduler._agendaScrollTop || 0;
        var h = scheduler._els.dhx_cal_data[0].childNodes[0],
            _ = h.childNodes[h.childNodes.length - 1];
        _.style.height = h.offsetHeight < scheduler._els.dhx_cal_data[0].offsetHeight ? "100%" : h.offsetHeight + "px";
        var c = scheduler._els.dhx_cal_data[0].firstChild.childNodes;
        scheduler._els.dhx_cal_date[0].innerHTML = scheduler.templates.agenda_date(scheduler._min_date, scheduler._max_date, scheduler._mode), scheduler._rendered = [];
        for (var r = 0; r < c.length - 1; r++) scheduler._rendered[r] = c[r]
    }
    var i = scheduler.dblclick_dhx_cal_data;
    scheduler.dblclick_dhx_cal_data = function() {
        if ("agenda" == this._mode) !this.config.readonly && this.config.dblclick_create && this.addEventNow();
        else if (i) return i.apply(this, arguments)
    }, scheduler.attachEvent("onSchedulerResize", function() {
        return "agenda" == this._mode ? (this.agenda_view(!0), !1) : !0
    });
    var a = scheduler.render_data;
    scheduler.render_data = function(e) {
        return "agenda" != this._mode ? a.apply(this, arguments) : void t()
    };
    var r = scheduler.render_view_data;
    scheduler.render_view_data = function() {
        return "agenda" == this._mode && (scheduler._agendaScrollTop = scheduler._els.dhx_cal_data[0].childNodes[0].scrollTop, scheduler._els.dhx_cal_data[0].childNodes[0].scrollTop = 0), r.apply(this, arguments)
    }, scheduler.agenda_view = function(i) {
        scheduler._min_date = scheduler.config.agenda_start || scheduler.date.agenda_start(scheduler._date),
            scheduler._max_date = scheduler.config.agenda_end || scheduler.date.add_agenda(scheduler._min_date, 1), e(i), i ? (scheduler._cols = null, scheduler._colsS = null, scheduler._table_view = !0, t()) : scheduler._table_view = !1
    }
});
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_agenda_view.js.map