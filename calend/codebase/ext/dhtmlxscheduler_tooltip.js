/*
@license
dhtmlxScheduler v.5.0.0 Stardard

This software is covered by GPL license. You also can obtain Commercial or Enterprise license to use it in non-GPL project - please contact sales@dhtmlx.com. Usage without proper license is prohibited.

(c) Dinamenta, UAB.
*/
window.dhtmlXTooltip = scheduler.dhtmlXTooltip = window.dhtmlxTooltip = {}, dhtmlXTooltip.config = {
    className: "dhtmlXTooltip tooltip",
    timeout_to_display: 50,
    timeout_to_hide: 50,
    delta_x: 15,
    delta_y: -20
}, dhtmlXTooltip.tooltip = document.createElement("div"), dhtmlXTooltip.tooltip.className = dhtmlXTooltip.config.className, scheduler._waiAria.tooltipAttr(dhtmlXTooltip.tooltip), dhtmlXTooltip.show = function(e, t) {
    if (!this._mobile || scheduler.config.touch_tooltip) {
        var a = dhtmlXTooltip,
            r = this.tooltip,
            i = r.style;
        a.tooltip.className = a.config.className;
        var n = this.position(e),
            s = e.target || e.srcElement;
        if (!this.isTooltip(s)) {
            var d = n.x + (a.config.delta_x || 0),
                l = n.y - (a.config.delta_y || 0);
            i.visibility = "hidden", i.removeAttribute ? (i.removeAttribute("right"), i.removeAttribute("bottom")) : (i.removeProperty("right"), i.removeProperty("bottom")), i.left = "0", i.top = "0", this.tooltip.innerHTML = t, document.body.appendChild(this.tooltip);
            var o = this.tooltip.offsetWidth,
                _ = this.tooltip.offsetHeight;
            document.documentElement.clientWidth - d - o < 0 ? (i.removeAttribute ? i.removeAttribute("left") : i.removeProperty("left"),
                i.right = document.documentElement.clientWidth - d + 2 * (a.config.delta_x || 0) + "px") : 0 > d ? i.left = n.x + Math.abs(a.config.delta_x || 0) + "px" : i.left = d + "px", document.documentElement.clientHeight - l - _ < 0 ? (i.removeAttribute ? i.removeAttribute("top") : i.removeProperty("top"), i.bottom = document.documentElement.clientHeight - l - 2 * (a.config.delta_y || 0) + "px") : 0 > l ? i.top = n.y + Math.abs(a.config.delta_y || 0) + "px" : i.top = l + "px", scheduler._waiAria.tooltipVisibleAttr(this.tooltip), i.visibility = "visible", this.tooltip.onmouseleave = function(e) {
                e = e || window.event;
                for (var t = scheduler.dhtmlXTooltip, a = e.relatedTarget; a != scheduler._obj && a;) a = a.parentNode;
                a != scheduler._obj && t.delay(t.hide, t, [], t.config.timeout_to_hide)
            }, scheduler.callEvent("onTooltipDisplayed", [this.tooltip, this.tooltip.event_id])
        }
    }
}, dhtmlXTooltip._clearTimeout = function() {
    this.tooltip._timeout_id && window.clearTimeout(this.tooltip._timeout_id)
}, dhtmlXTooltip.hide = function() {
    if (this.tooltip.parentNode) {
        scheduler._waiAria.tooltipHiddenAttr(this.tooltip);
        var e = this.tooltip.event_id;
        this.tooltip.event_id = null, this.tooltip.onmouseleave = null, this.tooltip.parentNode.removeChild(this.tooltip), scheduler.callEvent("onAfterTooltip", [e])
    }
    this._clearTimeout()
}, dhtmlXTooltip.delay = function(e, t, a, r) {
    this._clearTimeout(), this.tooltip._timeout_id = setTimeout(function() {
        var r = e.apply(t, a);
        return e = t = a = null, r
    }, r || this.config.timeout_to_display)
}, dhtmlXTooltip.isTooltip = function(e) {
    for (var t = !1; e && !t;) t = e.className == this.tooltip.className, e = e.parentNode;
    return t
}, dhtmlXTooltip.position = function(e) {
    return e = e || window.event, {
        x: e.clientX,
        y: e.clientY
    }
}, scheduler.attachEvent("onMouseMove", function(e, t) {
    var a = window.event || t,
        r = a.target || a.srcElement,
        i = dhtmlXTooltip,
        n = i.isTooltip(r),
        s = i.isTooltipTarget && i.isTooltipTarget(r);
    if (e && scheduler.getState().editor_id != e || n || s) {
        var d;
        if (e || i.tooltip.event_id) {
            var l = scheduler.getEvent(e) || scheduler.getEvent(i.tooltip.event_id);
            if (!l) return;
            if (i.tooltip.event_id = l.id, d = scheduler.templates.tooltip_text(l.start_date, l.end_date, l), !d) return i.hide()
        }
        s && (d = "");
        var o;
        if (_isIE) {
            o = {
                pageX: void 0,
                pageY: void 0,
                clientX: void 0,
                clientY: void 0,
                target: void 0,
                srcElement: void 0
            };
            for (var _ in o) o[_] = a[_]
        }
        if (!scheduler.callEvent("onBeforeTooltip", [e]) || !d) return;
        i.delay(i.show, i, [o || a, d])
    } else i.delay(i.hide, i, [], i.config.timeout_to_hide)
}), scheduler.attachEvent("onBeforeDrag", function() {
    return dhtmlXTooltip.hide(), !0
}), scheduler.attachEvent("onEventDeleted", function() {
    return dhtmlXTooltip.hide(), !0
});
//# sourceMappingURL=../sources/ext/dhtmlxscheduler_tooltip.js.map