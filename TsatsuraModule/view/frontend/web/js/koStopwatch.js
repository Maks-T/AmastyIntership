define(['uiComponent'], function (Component) {
    return Component.extend({
        defaults: {
            curTime: '00:00:00',
            sec: 0,
            min: 0,
            hrs: 0,
            timerId: null,
            delay: 1000
        },
        initObservable: function () {
            this._super();
            this.observe(['curTime']);

            return this;
        },
        initialize: function () {
            this._super();
            this.handleStart = this.handleStart.bind(this);
            this.handlePause = this.handlePause.bind(this);
            this.handleStop = this.handleStop.bind(this);
            this.tick = this.tick.bind(this);
            this.stopTimer = this.stopTimer.bind(this);
        },
        handleStart: function () {
            this.tick();
            this.curTime((this.hrs > 9 ? this.hrs : "0" + this.hrs)
                + ":" + (this.min > 9 ? this.min : "0" + this.min)
                + ":" + (this.sec > 9 ? this.sec : "0" + this.sec));
            this.timerId = setTimeout(this.handleStart, this.delay);
        },
        handlePause: function () {
           this.stopTimer();
        },
        handleStop: function () {
            this.stopTimer();
            this.curTime('00:00:00');
            this.sec = 0;
            this.min = 0;
            this.hrs = 0;
        },
        tick: function () {
            this.sec++;
            if (this.sec >= 60) {
                this.sec = 0;
                this.min++;
                if (this.min >= 60) {
                    this.min = 0;
                    this.hrs++;
                }
            }
        },
        stopTimer: function () {
            if (this.timerId) {
                clearTimeout(this.timerId);
                this.timerId = null;
            }
        }
    });
});