import './bootstrap';
import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

document.addEventListener('DOMContentLoaded', () => {
    const promoContainer = document.querySelector('.promoSwiper');
    if (promoContainer) {
        new Swiper(promoContainer, {
            modules: [Autoplay, Pagination],
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: promoContainer.querySelector('.swiper-pagination'),
                clickable: true,
            },
            effect: 'slide',
            speed: 600,
        });
    }

    const heroContainer = document.querySelector('.heroSwiper');
    if (heroContainer) {
        new Swiper(heroContainer, {
            modules: [Autoplay, Pagination, EffectFade],
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: heroContainer.querySelector('.swiper-pagination'),
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true,
            },
        });
    }
});
