class Stopwatch {
    constructor(times, display, results) {
        this.running = false;
        this.display = display;
        this.results = results;
        this.laps = [];
        this.reset();
        this.print(this.times);
        this.times = times; // custom
    }

    reset() {
        this.times = [ 0, 0, 0 ];
    }

    start() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
    }

    lap() {
        let times = this.times;
        let li = document.createElement('li');
        li.innerText = this.format(times);
        this.results.appendChild(li);
    }

    stop() {
        this.running = false;
        this.time = null;
    }

    restart() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
        this.reset();
    }

    clear() {
        clearChildren(this.results);
    }

    step(timestamp) {
        if (!this.running) return;
        this.calculate(timestamp);
        this.time = timestamp;
        this.print();
        requestAnimationFrame(this.step.bind(this));
    }

    calculate(timestamp) {
        var diff = timestamp - this.time;

        // Minutes are 60 seconds
        this.times[2] += diff / 1000; // tick on sec by 1000 ms
        if (this.times[2] >= 60) {

            // min - add only < 59
            if (this.times[1] < 59) {
                this.times[1]++; // increment min by 1
            } else {
                this.times[0]++; // increment hr by 1
                this.times[1] = 0;
            }

            this.times[2] = 0;
        }
    }

    print() {
        this.display.innerText = this.format(this.times);
    }

    format(times) {
        return `\
            ${pad0(times[0], 2)}:\
            ${pad0(times[1], 2)}:\
            ${pad0(Math.floor(times[2]), 2)}`;
    }
}

function pad0(value, count) {
    var result = value.toString();
    for (; result.length < count; --count)
        result = '0' + result;
    return result;
}

function clearChildren(node) {
    while (node.lastChild)
        node.removeChild(node.lastChild);
}

// let stopwatch = new Stopwatch(
//     document.querySelector('.stopwatch'),
//     document.querySelector('.results'));


export default Stopwatch;