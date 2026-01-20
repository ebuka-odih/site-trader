/**
 * Snow Effect - Vanilla JavaScript
 * Creates a beautiful falling snow animation effect for Blade templates
 */
(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSnow);
    } else {
        initSnow();
    }

    function initSnow() {
        // Check if canvas already exists
        let canvas = document.getElementById('snow-canvas');
        if (!canvas) {
            // Create canvas element
            canvas = document.createElement('canvas');
            canvas.id = 'snow-canvas';
            canvas.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; background: transparent;';
            document.body.appendChild(canvas);
        }

        const ctx = canvas.getContext('2d');
        let animationFrameId;
        let snowflakes = [];

        // Set canvas size
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Snowflake class
        function Snowflake() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1; // Size between 1-4
            this.speed = Math.random() * 2 + 0.5; // Speed between 0.5-2.5
            this.opacity = Math.random() * 0.5 + 0.3; // Opacity between 0.3-0.8
            this.wind = Math.random() * 0.5 - 0.25; // Horizontal drift
        }

        Snowflake.prototype.update = function() {
            this.y += this.speed;
            this.x += this.wind + Math.sin(this.y * 0.01) * 0.5; // Gentle swaying

            // Reset if off screen
            if (this.y > canvas.height) {
                this.y = -10;
                this.x = Math.random() * canvas.width;
            }
            if (this.x > canvas.width) {
                this.x = 0;
            } else if (this.x < 0) {
                this.x = canvas.width;
            }
        };

        Snowflake.prototype.draw = function() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255, 255, 255, ' + this.opacity + ')';
            ctx.fill();
        };

        // Create snowflakes
        function createSnowflakes() {
            const count = Math.floor((canvas.width * canvas.height) / 15000); // Adaptive count
            snowflakes = [];
            for (let i = 0; i < count; i++) {
                snowflakes.push(new Snowflake());
            }
        }
        createSnowflakes();

        // Animation loop
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            snowflakes.forEach(function(flake) {
                flake.update();
                flake.draw();
            });

            animationFrameId = requestAnimationFrame(animate);
        }
        animate();

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            window.removeEventListener('resize', resizeCanvas);
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
        });
    }
})();

