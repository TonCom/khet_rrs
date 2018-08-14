/*
@license
dhtmlxScheduler v.5.0.0 Stardard

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
! function() {
    scheduler.config.all_timed = "short", scheduler.config.all_timed_month = !1;
    var e = function(e) {
        return !((e.end_date - e.start_date) / 36e5 >= 24)
    };
    scheduler._safe_copy = function(e) {
        var t = null,
            i = scheduler._copy_event(e);
        return e.event_pid && (t = scheduler.getEvent(e.event_pid)), t && t.isPrototypeOf(e) && (delete i.event_length, delete i.event_pid, delete i.rec_pattern, delete i.rec_type), i
    };
    var t = scheduler._pre_render_events_line,
        i = scheduler._pre_render_events_table,
        a = function(e, a) {
            return this._table_view ? i.call(this, e, a) : t.call(this, e, a);
        };
    scheduler._pre_render_events_line = scheduler._pre_render_events_table = function(t, i) {
        function r(e) {
            var t = s(e.start_date);
            return +e.end_date > +t
        }

        function s(e) {
            var t = scheduler.date.add(e, 1, "day");
            return t = scheduler.date.date_part(t)
        }

        function n(e, t) {
            var i = scheduler.date.date_part(new Date(e));
            return i.setHours(t), i
        }
        if (!this.config.all_timed || this._table_view && "month" != this._mode || "month" == this._mode && !this.config.all_timed_month) return a.call(this, t, i);
        for (var d = 0; d < t.length; d++) {
            var l = t[d];
            if (!l._timed)
                if ("short" != this.config.all_timed || e(l)) {
                    var o = this._safe_copy(l);
                    o.start_date = new Date(o.start_date), r(l) ? (o.end_date = s(o.start_date), 24 != this.config.last_hour && (o.end_date = n(o.start_date, this.config.last_hour))) : o.end_date = new Date(l.end_date);
                    var h = !1;
                    o.start_date < this._max_date && o.end_date > this._min_date && o.start_date < o.end_date && (t[d] = o, h = !0);
                    var _ = this._safe_copy(l);
                    if (_.end_date = new Date(_.end_date), _.start_date < this._min_date ? _.start_date = n(this._min_date, this.config.first_hour) : _.start_date = n(s(l.start_date), this.config.first_hour),
                        _.start_date < this._max_date && _.start_date < _.end_date) {
                        if (!h) {
                            t[d--] = _;
                            continue
                        }
                        t.splice(d + 1, 0, _)
                    }
                } else "month" != this._mode && t.splice(d--, 1)
        }
        var c = "move" == this._drag_mode ? !1 : i;
        return a.call(this, t, c)
    };
    var r = scheduler.get_visible_events;
    scheduler.get_visible_events = function(e) {
        return this.config.all_timed && this.config.multi_day ? r.call(this, !1) : r.call(this, e)
    }, scheduler.attachEvent("onBeforeViewChange", function(e, t, i, a) {
        return scheduler._allow_dnd = "day" == i || "week" == i, !0
    }), scheduler._is_main_area_event = function(t) {
        return !!(t._timed || this.config.all_timed === !0 || "short" == this.config.all_timed && e(t))
    };
    var s = scheduler.updateEvent;
    scheduler.updateEvent = function(e) {
        var t, i, a = scheduler.getEvent(e);
        a && (t = scheduler.config.all_timed && !(scheduler.isOneDayEvent(scheduler._events[e]) || scheduler.getState().drag_id), t && (i = scheduler.config.update_render, scheduler.config.update_render = !0)), s.apply(scheduler, arguments), a && t && (scheduler.config.update_render = i)
    }
}();
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_all_timed.js.map