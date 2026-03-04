@extends('layouts.main')

@section('content')

    @php
        $setting = \Illuminate\Support\Facades\DB::table('settings')->first();
    @endphp

    <!-- Main Content -->
    <main style="margin-top: 6.5rem">
        <div class="banner" style="background: url({{ asset('uploads/'.$setting->banner) }}); min-height: 480px;">
            <div class="client-banner-content">
                <h1 class="display-3 fw-semibold text-shadow">
                <a href="/" class="text-light">
                {{ $setting->title }}
                </a>
                </h1>
                <h2 class="text-light text-shadow mt-2">
                {{ $setting->sub_title }}
                </h2>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="thermometer">
                        <div class="thermometer-wrapper">
                            <div class="bulb">
                            <div class="bulb-inner"></div>
                            </div>
                            <div class="bar">
                            <div class="fill" id="fill"></div>
                            @php
                                $users = \Illuminate\Support\Facades\DB::table('users')->where('role', 'user')->get();
                                $donations = \Illuminate\Support\Facades\DB::table('donations')->get();
                            @endphp
                            <div class="label goal-label" id="goal-label">Goal: ${{ $users->sum('goal') }}</div>
                            <div class="label raised-label" id="raised-label">Raised: ${{ $donations->sum('amount') }}</div>
                            </div>
                            <div class="ticks">
                            <div class="tick">${{ $users->sum('goal') / 4 }}</div>
                            <div class="tick">${{ $users->sum('goal') / 2 }}</div>
                            <div class="tick">${{ $users->sum('goal') / 1.25 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="image">
                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Image" class="img-fluid">

                                <p style="font-size: 1.25rem; font-weight: 400;">

                                    Click the Register button to register your student, and to create your personal fundraising page.

                                </p>

                                <p style="font-size: 1.25rem; font-weight: 400;">
                                    To make a sponsorship donation or to search for and donate to a student's goal, click Donate in the menu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-4">
                    <div class="timer text-center mt-5">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="mx-3">
                                <h1 id="months" class="display-4">0</h1>
                                <p>Months</p>
                            </div>
                            <div class="mx-3">
                                <h1 id="days" class="display-4">0</h1>
                                <p>Days</p>
                            </div>
                            <div class="mx-3">
                                <h1 id="hours" class="display-4">0</h1>
                                <p>Hours</p>
                            </div>
                            <div class="mx-3">
                                <h1 id="minutes" class="display-4">0</h1>
                                <p>Minutes</p>
                            </div>
                            <div class="mx-3">
                                <h1 id="seconds" class="display-4">0</h1>
                                <p>Seconds</p>
                            </div>
                        </div>
                        <p style="font-size: .8em;">Remaining to {{ \Carbon\Carbon::parse($setting->date)->format('M d, Y') }} (00:00 PST) </p>
                    </div>
                </div>

                <div class="col-md-12 mt-4 mb-4">
                    <div class="text-section">
                        <h2 class="display-5 fw-normal text-center mb-3">
                            {{ $setting->title2 }}
                        </h2>
                        <div class="w-xl-75 lead break-all" style="width: 60%; margin: auto;">
                            <p style="text-align: center; font-weight: 400;">{!! $setting->description !!}</p>
                            <p style="text-align: center; font-weight: 400;">Thank you so much for all your support!</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="icons">
                        <div class="row gy-3 gy-md-4 row-cols-1 flex-column ">

                            <div class="col">
                                        <div class="row gy-3 justify-content-center text-center text-">

                                                    <div class="col-md-4 col-xl-2">
                                            <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                <i class="fa-solid fa-calendar-days fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                <h4 class="fs-1.5 fw-light mb-1">
                                                    When
                                                </h4>
                                                <p class="fs-.75 opacity-75 fw-light">
                                                    {{ \Carbon\Carbon::parse($setting->date)->format('M d, Y') }}
                                                                            </p>
                                            </div>
                                        </div>

                                                                        <div class="col-md-4 col-xl-3">
                                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                    <i class="fa-solid fa-signs-post fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                    <h4 class="fs-1.5 fw-light mb-1">
                                                        Where
                                                    </h4>
                                                    <p class="fs-.75 opacity-75 fw-light">
                                                                                            {{ $setting->location }}
                                                                                    </p>
                                                </div>
                                            </div>

                                                    <div class="col-md-4 col-xl-2">
                                            <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                                <i class="fa-solid fa-clock fa-fw fs-3 text-primary mb-3" aria-hidden="true"></i>
                                                <h4 class="fs-1.5 fw-light mb-1">
                                                    Time
                                                </h4>
                                                <p class="fs-.75 opacity-75 fw-light">
                                                                                    {{ $setting->time }} PST
                                                                            </p>
                                            </div>
                                        </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>

        <input type="hidden" id="time" value="{{ \Carbon\Carbon::parse($setting->date)->format('M d, Y') }}">

    </main>

    <input type="hidden" id="goal" value="{{ $users->sum('goal') }}">
    <input type="hidden" id="raised" value="{{ $donations->sum('amount') }}">


    <script>
        const goal = document.getElementById('goal').value;
        const raised = document.getElementById('raised').value;

        const fill = document.getElementById('fill');
        const bar = document.querySelector('.bar');

        function updateFill() {
        const barWidth = bar.clientWidth;
        const fillWidth = Math.min(raised / goal, 1) * barWidth;
        fill.style.width = `${fillWidth}px`;
        }

        updateFill();
        window.addEventListener('resize', updateFill);

        document.getElementById('goal-label').textContent = `Goal: $${goal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('raised-label').textContent = `Raised: $${raised.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    </script>

    <script>
        (() => {
            "use strict";

            // Utility functions grouped into a single object
            const Utils = {
                // Parse pixel values to numeric values
                parsePx: (value) => parseFloat(value.replace(/px/, "")),

                // Generate a random number between two values, optionally with a fixed precision
                getRandomInRange: (min, max, precision = 0) => {
                const multiplier = Math.pow(10, precision);
                const randomValue = Math.random() * (max - min) + min;
                return Math.floor(randomValue * multiplier) / multiplier;
                },

                // Pick a random item from an array
                getRandomItem: (array) => array[Math.floor(Math.random() * array.length)],

                // Scaling factor based on screen width
                getScaleFactor: () => Math.log(window.innerWidth) / Math.log(1920),

                // Debounce function to limit event firing frequency
                debounce: (func, delay) => {
                let timeout;
                return (...args) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func(...args), delay);
                };
                },
            };

            // Precomputed constants
            const DEG_TO_RAD = Math.PI / 180;

            // Centralized configuration for default values
            const defaultConfettiConfig = {
                confettiesNumber: 120,
                confettiRadius: 4,
                confettiColors: [
                "#2e4053", "#b7bcc4"
                ],
                emojies: [],
                svgIcon: null, // Example SVG link
            };

            // Confetti class representing individual confetti pieces
            class Confetti {
                constructor({ initialPosition, direction, radius, colors, emojis, svgIcon }) {
                const speedFactor = Utils.getRandomInRange(0.9, 1.7, 3) * Utils.getScaleFactor();
                this.speed = { x: speedFactor, y: speedFactor };
                this.finalSpeedX = Utils.getRandomInRange(0.2, 0.6, 3);
                this.rotationSpeed = emojis.length || svgIcon ? 0.01 : Utils.getRandomInRange(0.03, 0.07, 3) * Utils.getScaleFactor();
                this.dragCoefficient = Utils.getRandomInRange(0.0005, 0.0009, 6);
                this.radius = { x: radius, y: radius };
                this.initialRadius = radius;
                this.rotationAngle = direction === "left" ? Utils.getRandomInRange(0, 0.2, 3) : Utils.getRandomInRange(-0.2, 0, 3);
                this.emojiRotationAngle = Utils.getRandomInRange(0, 2 * Math.PI);
                this.radiusYDirection = "down";

                const angle = direction === "left" ? Utils.getRandomInRange(82, 15) * DEG_TO_RAD : Utils.getRandomInRange(-15, -82) * DEG_TO_RAD;
                this.absCos = Math.abs(Math.cos(angle));
                this.absSin = Math.abs(Math.sin(angle));

                const offset = Utils.getRandomInRange(-150, 0);
                const position = {
                    x: initialPosition.x + (direction === "left" ? -offset : offset) * this.absCos,
                    y: initialPosition.y - offset * this.absSin
                };

                this.position = { ...position };
                this.initialPosition = { ...position };
                this.color = emojis.length || svgIcon ? null : Utils.getRandomItem(colors);
                this.emoji = emojis.length ? Utils.getRandomItem(emojis) : null;
                this.svgIcon = null;

                // Preload SVG if provided
                if (svgIcon) {
                    this.svgImage = new Image();
                    this.svgImage.src = svgIcon;
                    this.svgImage.onload = () => {
                    this.svgIcon = this.svgImage; // Mark as ready once loaded
                    };
                }

                this.createdAt = Date.now();
                this.direction = direction;
                }

                draw(context) {
                const { x, y } = this.position;
                const { x: radiusX, y: radiusY } = this.radius;
                const scale = window.devicePixelRatio;

                if (this.svgIcon) {
                    context.save();
                    context.translate(scale * x, scale * y);
                    context.rotate(this.emojiRotationAngle);
                    context.drawImage(this.svgIcon, -radiusX, -radiusY, radiusX * 2, radiusY * 2);
                    context.restore();
                } else if (this.color) {
                    context.fillStyle = this.color;
                    context.beginPath();
                    context.ellipse(x * scale, y * scale, radiusX * scale, radiusY * scale, this.rotationAngle, 0, 2 * Math.PI);
                    context.fill();
                } else if (this.emoji) {
                    context.font = `${radiusX * scale}px serif`;
                    context.save();
                    context.translate(scale * x, scale * y);
                    context.rotate(this.emojiRotationAngle);
                    context.textAlign = "center";
                    context.fillText(this.emoji, 0, radiusY / 2); // Adjust vertical alignment
                    context.restore();
                }
                }

                updatePosition(deltaTime, currentTime) {
                const elapsed = currentTime - this.createdAt;

                if (this.speed.x > this.finalSpeedX) {
                    this.speed.x -= this.dragCoefficient * deltaTime;
                }

                this.position.x += this.speed.x * (this.direction === "left" ? -this.absCos : this.absCos) * deltaTime;
                this.position.y = this.initialPosition.y - this.speed.y * this.absSin * elapsed + 0.00125 * Math.pow(elapsed, 2) / 2;

                if (!this.emoji && !this.svgIcon) {
                    this.rotationSpeed -= 1e-5 * deltaTime;
                    this.rotationSpeed = Math.max(this.rotationSpeed, 0);

                    if (this.radiusYDirection === "down") {
                    this.radius.y -= deltaTime * this.rotationSpeed;
                    if (this.radius.y <= 0) {
                        this.radius.y = 0;
                        this.radiusYDirection = "up";
                    }
                    } else {
                    this.radius.y += deltaTime * this.rotationSpeed;
                    if (this.radius.y >= this.initialRadius) {
                        this.radius.y = this.initialRadius;
                        this.radiusYDirection = "down";
                    }
                    }
                }
                }

                isVisible(canvasHeight) {
                return this.position.y < canvasHeight + 100;
                }
            }

            class ConfettiManager {
                constructor() {
                this.canvas = document.createElement("canvas");
                this.canvas.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; pointer-events: none;";
                document.body.appendChild(this.canvas);
                this.context = this.canvas.getContext("2d");
                this.confetti = [];
                this.lastUpdated = Date.now();
                window.addEventListener("resize", Utils.debounce(() => this.resizeCanvas(), 200));
                this.resizeCanvas();
                requestAnimationFrame(() => this.loop());
                }

                resizeCanvas() {
                this.canvas.width = window.innerWidth * window.devicePixelRatio;
                this.canvas.height = window.innerHeight * window.devicePixelRatio;
                }

                addConfetti(config = {}) {
                const { confettiesNumber, confettiRadius, confettiColors, emojies, svgIcon } = {
                    ...defaultConfettiConfig,
                    ...config,
                };

                const baseY = (5 * window.innerHeight) / 7;
                for (let i = 0; i < confettiesNumber / 2; i++) {
                    this.confetti.push(new Confetti({
                    initialPosition: { x: 0, y: baseY },
                    direction: "right",
                    radius: confettiRadius,
                    colors: confettiColors,
                    emojis: emojies,
                    svgIcon,
                    }));
                    this.confetti.push(new Confetti({
                    initialPosition: { x: window.innerWidth, y: baseY },
                    direction: "left",
                    radius: confettiRadius,
                    colors: confettiColors,
                    emojis: emojies,
                    svgIcon,
                    }));
                }
                }

                resetAndStart(config = {}) {
                // Clear existing confetti
                this.confetti = [];
                // Add new confetti
                this.addConfetti(config);
                }

                loop() {
                const currentTime = Date.now();
                const deltaTime = currentTime - this.lastUpdated;
                this.lastUpdated = currentTime;

                this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

                this.confetti = this.confetti.filter((item) => {
                    item.updatePosition(deltaTime, currentTime);
                    item.draw(this.context);
                    return item.isVisible(this.canvas.height);
                });

                requestAnimationFrame(() => this.loop());
                }
            }

            // Trigger confetti 5 times
            function triggerConfettiMultipleTimes(times, delay) {
                let count = 0;
                const intervalId = setInterval(() => {
                    const manager = new ConfettiManager();
            // manager.addConfetti();
                    manager.addConfetti(); // Trigger confetti
                    count++;
                    if (count >= times) {
                        clearInterval(intervalId); // Stop after triggering 5 times
                    }
                }, delay);
            }

            triggerConfettiMultipleTimes(5, 500);



            const triggerButton = document.getElementById("show-again");
            if (triggerButton) {
                triggerButton.addEventListener("click", () => manager.addConfetti());
            }

            const resetInput = document.getElementById("reset");
            if (resetInput) {
                resetInput.addEventListener("input", () => manager.resetAndStart());
            }
            })();



    </script>

    <script>
        da = document.getElementById("time").value;
    // Set the target date for the countdown
    const targetDate = new Date(da).getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const timeLeft = targetDate - now;

        if (timeLeft <= 0) {
            document.getElementById("months").textContent = 0;
            document.getElementById("days").textContent = 0;
            document.getElementById("hours").textContent = 0;
            document.getElementById("minutes").textContent = 0;
            document.getElementById("seconds").textContent = 0;
            return;
        }

        // Calculate time components
        const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
        const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        // Update the HTML
        document.getElementById("months").textContent = months;
        document.getElementById("days").textContent = days;
        document.getElementById("hours").textContent = hours;
        document.getElementById("minutes").textContent = minutes;
        document.getElementById("seconds").textContent = seconds;
    }

    // Update the countdown every second
    setInterval(updateCountdown, 1000);
    </script>
@endsection


