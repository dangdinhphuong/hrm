<template>
    <Carousel v-bind="settings" :breakpoints="breakpoints"  :wrapAround="false" :transition="500" class="ms-3 mt-3  w-sm-100 w-60">
        <Slide v-for="(text, index) in data.listMenu" :key="index">
            <a-button type="secondary"  class="carousel__item fw-bold"   style="background-color: var(--color-light); border-color: var(--color-light); color: var(--color-medium);">
                {{ text.name }}
            </a-button>
        </Slide>
        <template #addons>
            <Navigation />
        </template>
    </Carousel>
</template>

<script>
import { defineComponent } from 'vue'
import { Carousel, Navigation, Slide } from 'vue3-carousel'

import 'vue3-carousel/dist/carousel.css'

export default defineComponent({
    name: 'SlickButton',
    props: {
        data: Object,
        size: {
            type: String,
            default: 'large'
        }
    },
    components: {
        Carousel,
        Slide,
        Navigation,
    },
    data: () => ({
        // carousel settings
        settings: {
            itemsToShow: 2,
        },
        breakpoints: {
            // 700px and up
            700: {
                itemsToShow: 4,
            },
            // 1024 and up
            1024: {
                itemsToShow: 4,
            },
            768: {
                itemsToShow: 3,
            },
            563: {
                itemsToShow: 4,
            },
        },
        slides: ['Slide 1', 'Slide 2', 'Slide 3', 'Slide 4', 'Slide 5', 'Slide 6', 'Slide 7', 'Slide 8', 'Slide 9', 'Slide 10'],
    }),
})
</script>

<style >
.carousel__slide {
    //padding-top: 4% !important;
}

.carousel{
    width: 50%;
}
.carousel__prev,
.carousel__next {
    box-sizing: content-box;
    background: transparent;
    background-color: var(--color-secondary)!important;
    border-radius: 5px;
    width: 6%;
    height: 65%;

}
.carousel__slide--active ~ .carousel__slide {
    transform: rotateY(20deg) scale(0.9);
}
.carousel__slide--active button {
    background-color: var(--button-add-color)!important;
    border-color: var(--button-add-color)!important;
    /*color: var(--color-medium)!important;*/

}

@media (max-width: 575px) {
    .carousel{
        margin-top: 100%;
    }
}
</style>
