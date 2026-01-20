import React, { useEffect, useRef } from 'react';

/**
 * SnowEffect Component
 * Creates a beautiful falling snow animation effect
 */
const SnowEffect = () => {
    const canvasRef = useRef(null);

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let animationFrameId;
        let snowflakes = [];

        // Set canvas size
        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Snowflake class
        class Snowflake {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 3 + 1; // Size between 1-4
                this.speed = Math.random() * 2 + 0.5; // Speed between 0.5-2.5
                this.opacity = Math.random() * 0.5 + 0.3; // Opacity between 0.3-0.8
                this.wind = Math.random() * 0.5 - 0.25; // Horizontal drift
            }

            update() {
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
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.fill();
            }
        }

        // Create snowflakes
        const createSnowflakes = () => {
            const count = Math.floor((canvas.width * canvas.height) / 15000); // Adaptive count
            snowflakes = [];
            for (let i = 0; i < count; i++) {
                snowflakes.push(new Snowflake());
            }
        };
        createSnowflakes();

        // Animation loop
        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            snowflakes.forEach((flake) => {
                flake.update();
                flake.draw();
            });

            animationFrameId = requestAnimationFrame(animate);
        };
        animate();

        // Cleanup
        return () => {
            window.removeEventListener('resize', resizeCanvas);
            cancelAnimationFrame(animationFrameId);
        };
    }, []);

    return (
        <canvas
            ref={canvasRef}
            className="fixed top-0 left-0 w-full h-full pointer-events-none"
            style={{ 
                background: 'transparent',
                zIndex: 0,
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%'
            }}
        />
    );
};

export default SnowEffect;

