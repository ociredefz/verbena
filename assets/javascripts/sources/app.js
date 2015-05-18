/**
 * VERBENA APP MODULE (C) 2015
 * This file is a part of verbena framework.
 */

// Root object (window, ..) 
// Factory is the module object that contains our module methods
(function (root, factory) { 

    // Export module with the related root object
    if (typeof define === 'function' && define.amd) {
        define([], factory());
    }
    else if (typeof exports === 'object') {
        module.exports = factory();
    }
    else {
        root.APP = factory();
    }

// 'this' pointer to the root object (window, ..) & initialize our module object
}(this, function () {

    var exports = {},

    _w          = window,
    _d          = window.document,

    _fade_stat  = false,
    _scrollbars = [], 
    _scrollbar  = 0,
    _mouse_y    = 0;


    /**
     * Loads essentials.
     *
     * @param  {void}
     * @return {void}
     */
    exports.load_essentials = function () {

        // Centers the motd box.
        _box_centered();

        // Handles toggle.
        _toggle_event();

        // Attachs scrollbar (body, spot).
        _attach_scrollbar('wrapper', true);
        _attach_scrollbar('index-list-container');

    };

    /**
     * Viewport iPhone scale.
     *
     * @param  {void}
     * @return {void}
     */
    exports.viewport = function () {

        var metas = document.getElementsByTagName('meta');

        if (navigator.userAgent.match(/iPhone/i)) {
            for (var i = 0; i < metas.length; i++) {
                if (metas[i].name == 'viewport') {
                    metas[i].content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0';
                }
            }

            _add_cross_event(_d, 'gesturestart', _manage_viewports);
        }

    };

    /**
     * Centers the motd box.
     *
     * @param  {void}
     * @return {void}
     */
    var _box_centered = function () {

        var window_height = _w.innerHeight,
            box_height = _d.getElementById('main').offsetHeight,
            header = _d.getElementById('header'), top_space;

        if (window_height < 800) {
            top_space = _d.getElementById('lite-container').innerHeight + 80;
        }
        else {
            top_space = (((window_height - box_height) / 2) - 50);
        }

        _d.getElementById('main').style.top = top_space + 'px';

    };

    /**
     * Handles toggle event.
     *
     * @param  {void}
     * @return {void}
     */
    var _toggle_event = function () {

        var index   = _d.getElementById('index'),
            lindex  = _d.getElementById('index-list'), 
            toggle  = _d.getElementById('toggle'),
            s_bars  = _d.getElementsByClassName('scrollbar-spot'),
            s_title = _d.getElementsByClassName('sub-title'),
            op_cl   = false;

        if (toggle === null) {
            return false;
        }

        _add_cross_event(toggle, 'click', function () {
            if (op_cl === false) {
                lindex.style.display = 'none';
                index.style.width = '50px';
                toggle.style.marginRight = '16px';

                Array.prototype.forEach.call(s_bars, function(el) {
                    el.style.display = 'none';
                });

                op_cl = true;
            }
            else {
                lindex.style.display = 'inherit';
                index.style.width = '300px';
                toggle.style.marginRight = '20px';

                Array.prototype.forEach.call(s_bars, function(el) {
                    el.style.display = 'inherit';
                });

                op_cl = false;
            }
        });

        Array.prototype.filter.call(s_title, function (element) {
            _add_cross_event(element, 'click', function () {
                var next = element.nextElementSibling;

                if (next.style.display === 'none') {
                    next.style.display = 'inherit';
                }
                else {
                    next.style.display = 'none';
                }

                _refresh();
            });
        });

    };


    /**
     * CUSTOM SCROLLBAR.
     */


    /**
     * Handles scrollbar events.
     *
     * @param  {string}   Element id.
     * @param  {boolean}  Body control variable.
     * @return {void}
     */
    var _attach_scrollbar = function(element_id, scroll_body) {

        var element     = _d.getElementById(element_id),
            element_clone,
            custom_class;

        if (element === null) {
            return false;
        }

        element_clone   = element.cloneNode(false),
        custom_class    = '';

        // Intializes base events.
        if (!_mouse_events()) {
            return false;
        }

        // Sets element style and creates an element wrapper.
        element_clone.style.overflow = 'hidden';
        element.parentNode.appendChild(element_clone);
        element_clone.appendChild(element);

        element.style.position = 'absolute';
        element.style.left  = element.style.top = '0px';
        element.style.width = element.style.height = '100%';

        // Manages some custom scrollbar styles.
        if (scroll_body === true) {
            custom_class = 'scrollbar-body';
            element.style.maxWidth = element.clientWidth + 32 + 'px';
            element.sw = 15;
        }
        else {
            custom_class = 'scrollbar-spot';
            element.className = 'content';
            element.id = 'index-clone';
            element.sw = 10;
        }

        // Adds scrollbar element to array.
        _scrollbars[_scrollbars.length++] = element;

        // Creates on-the-fly scrollbar elements.
        element.st = _generate_div(custom_class + ' scrollbar_background', element, element_clone);
        element.sb = _generate_div(custom_class + ' scrollbar_pointer', element, element_clone);
        element.sg = false;

        // Manages scrollbar drag event.
        element.sb.onmousedown = function (event) {

            if (!element.sg) {
                if (!event) {
                    event = _w.event;
                }

                _scrollbar = element;

                element.yZ = event.screenY;
                element.sZ = element.scrollTop;
                element.sg = true;
            }

            return false;

        };

        // Manages scrollbar target click event.
        element.st.onmousedown = function (event) {

            if (!event) {
                event = _w.event;
            }

            _scrollbar = element;
            _mouse_y = event.clientY + _d.body.scrollTop + _d.documentElement.scrollTop;

            for (var offset = element, y = 0; offset != null; offset = offset.offsetParent) {
                y += offset.offsetTop;
            }   

            element.scrollTop = (_mouse_y - y - (element.ratio * element.offsetHeight / 2) - element.sw) / element.ratio;
        
        };

        // Manages the onscroll event.
        element.scrollbar_onscroll = function () {

            if (scroll_body === true) {
                var index = _d.getElementById('index'),
                    scroll_offset = this.scrollTop;

                if (scroll_offset > _w.innerHeight && _fade_stat == false) {
                    _fade_stat = true;
                    _fade_effect(0, index, 50);
                    index.style.display = 'inherit';
                }
                else if (scroll_offset < _w.innerHeight && _fade_stat == true) {
                    _fade_stat = false;
                    _fade_effect(1, index, 50);
                }
            }

            this.ratio = (this.offsetHeight - 1) / this.scrollHeight;
            this.sb.style.top = Math.floor(this.scrollTop * this.ratio) + 'px';

        };

        // Refresh the scrollbars onscroll and
        // attach our custom scrollbar onscroll to
        // the element.
        setTimeout(function () {
            _refresh();
        }, 50);

        element.onscroll = element.scrollbar_onscroll;

    };

    /**
     * Initializes base events.
     *
     * @param  {void}
     * @return {boolean}
     */
    var _mouse_events = function () {

        if (!_w.addEventListener && !_w.attachEvent) { 
            return false; 
        }

        _add_cross_event(_d, 'mousemove', _onmousemove);
        _add_cross_event(_d, 'mouseup', _onmouseup);
        _add_cross_event(_w, 'resize', _refresh);

        return true;

    };

    /**
     * Creates on-the-fly elements.
     *
     * @param  {string}  Class name.
     * @param  {object}  Element object.
     * @param  {object}  Element object.
     * @return {object}  Element object.
     */
    var _generate_div = function(class_name, element, element_clone) {

        var ptr = _d.createElement('div');

        ptr.element = element;
        ptr.className = class_name;

        element_clone.appendChild(ptr);

        return ptr;

    };

    /**
     * Refresh the scrollbars data.
     *
     * @param  {void}
     * @return {void}
     */
    var _refresh = function () {

        for (var i = 0; i < _scrollbars.length; i++) {
            var ptr = _scrollbars[i];
            
            ptr.scrollbar_onscroll();
            ptr.sb.style.width = ptr.st.style.width = ptr.sw + 'px';
            ptr.sb.style.height = Math.ceil(Math.max(ptr.sw * .5, ptr.ratio * ptr.offsetHeight) + 1) + 'px';
        }

    };

    /**
     * Handles onscroll event.
     *
     * @param  {void}
     * @return {void}
     */
    var _onscroll = function () {

        var ratio = (_scrollbar.offsetHeight - 1) / _scrollbar.scrollHeight;
        _scrollbar.style.top = Math.floor(_scrollbar.scrollTop * ratio) + 'px';

    };

    /**
     * Handles onmousemove event.
     *
     * @param  {object}  Event object.
     * @return {void}
     */
    var _onmousemove = function (event) {

        if (!event) {
            event = _w.event;
        }

        _mouse_y = event.screenY;

        if (_scrollbar.sg) {
            _scrollbar.scrollTop = _scrollbar.sZ + (_mouse_y - _scrollbar.yZ) / _scrollbar.ratio;
        }

    };

    /**
     * Handles onmouseup event.
     *
     * @param  {object}  Event object.
     * @return {void}
     */
    var _onmouseup = function (event) {

        if (!event) {
            event = _w.event;
        }

        var target = (event.target) ? event.target : e.srcElement;

        if (_scrollbar && _d.releaseCapture) {
            _scrollbar.releaseCapture();
        }

        _scrollbar.sg = false;

    };


    /**
     * CORE FUNCTIONS.
     */


    /**
     * Add a cross-browser events listener.
     *
     * @param  {string}     Selector id name.
     * @param  {string}     Event type name.
     * @param  {function}   Callback function on custom event.
     * @return {void}
     */
    var _add_cross_event = function (ptr, event, funct) {

        if (typeof ptr.addEventListener !== 'undefined') {
            ptr.addEventListener(event, funct, false);
        }
        else if (typeof ptr.attachEvent !== 'undefined') {
            ptr.attachEvent('on' + event, funct);
        }
        else {
            throw 'Invalid event handler';
        }

    };

    /**
     * Fade in/out effect.
     *
     * @param  {int}     FX type: fadeIn = 0, fadeOut = 1
     * @param  {object}  Element object.
     * @param  {int}     Effect interval speed (default: 25).
     * @return {void}
     */
    var _fade_effect = function (inout, element, speed) {

        speed = speed || 25;

        var io_map = {
            0: 1.0,
            1: 0.0
        }, opacity = inout,
    
        timer = setInterval(function () {

            if (opacity >= io_map[inout] && !inout) {
                clearInterval(timer);
            }
            else if (opacity <= io_map[inout] && inout) {
                clearInterval(timer);
            }

            element.style.opacity = opacity;
            element.style.filter = 'alpha(opacity=' + opacity * 100 + ')';

            if (!inout) {
                opacity += 0.1;
            }
            else {
                opacity -= 0.1;
            }

        }, speed);

    };

    /**
     * Manage scales.
     *
     * @param  {void}
     * @return {void}
     */
    var _manage_viewports = function () {

        for (var i = 0; i < _metas.length; i++) {
            if (_metas[i].name == 'viewport') {
                _metas[i].content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
            }
        }

    };


    return exports;

}));


APP.load_essentials();
APP.viewport();
