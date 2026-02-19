import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './main';

// Initialize AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
});
