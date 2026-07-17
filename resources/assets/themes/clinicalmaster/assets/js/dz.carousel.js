const ClinicMasterCarousel = function () {
  const handleTestimonialSwiper9 = () => {
    const sliderEl = document.querySelector(".testimonial-swiper9");

    if (sliderEl) {
      const testimonialSwiper9 = new Swiper(".testimonial-swiper9", {
        loop: true,
        spaceBetween: 25,
        slidesPerView: 1,
        autoplay: {
          delay: 3000,
        },
        centeredSlides: true,
        breakpoints: {
          1481: {
            slidesPerView: 4,
          },
          1200: {
            slidesPerView: 3,
          },
          991: {
            slidesPerView: 2.5,
          },
          767: {
            slidesPerView: 2,
          },
        },
      });
    }
  };

  const handleClientSwiper2 = () => {
    const swiperEl = document.querySelector(".client-swiper2");
    if (!swiperEl) return;

    new Swiper(".client-swiper2", {
      loop: true,
      slidesPerView: 4,
      spaceBetween: 30,
      autoplay: {
        delay: 3000,
      },
      breakpoints: {
        767: {
          slidesPerView: 4,
        },
        575: {
          slidesPerView: 3,
        },
        320: {
          slidesPerView: 2,
        },
      },
    });
  };

  const BlogSlideshowSwiper = () => {
    const swiperEl = document.querySelector(".blog-slideshow");
    if (!swiperEl) return;

    new Swiper(".blog-slideshow", {
      loop: true,
      spaceBetween: 0,
      slidesPerView: "auto",
      speed: 1500,
      autoplay: {
        delay: 2000,
      },
      pagination: {
        el: ".swiper-pagination-two",
        clickable: true,
      },
    });
  };

  if (
    document.querySelector(".galley-thumb-swiper") &&
    document.querySelector(".galley-swiper")
  ) {
    const swiperThumbs = new Swiper(".galley-thumb-swiper", {
      loop: false,
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });

    new Swiper(".galley-swiper", {
      loop: true,
      spaceBetween: 10,
      thumbs: {
        swiper: swiperThumbs,
      },
    });
  }

  const handleVerticalSwiper = () => {
    const blogVerticalSwiper = document.querySelector(".blog-vertical-swiper");

    if (blogVerticalSwiper) {
      const teamSwiperThumb = new Swiper(".blog-vertical-swiper-thumb", {
        direction: "vertical",
        slidesPerView: 3,
        mousewheel: false,
        spaceBetween: 10,
        autoplay: {
          delay: 3000,
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
            direction: "horizontal",
          },
          767: {
            slidesPerView: 2,
            direction: "vertical",
          },
          1191: {
            slidesPerView: 3,
          },
        },
      });

      new Swiper(".blog-vertical-swiper", {
        slidesPerView: 1,
        effect: "fade",
        grabCursor: true,
        thumbs: {
          swiper: teamSwiperThumb,
        },
        navigation: {
          nextEl: ".blog-swiper-next",
          prevEl: ".blog-swiper-prev",
        },
      });
    }
  };

  const handleTeamSwiper2 = () => {
    const imageEl = document.querySelector(".team-image-swiper");
    const contentEl = document.querySelector(".team-content-swiper");

    if (!imageEl || !contentEl) return;

    const imageSwiper = new Swiper(imageEl, {
      slidesPerView: 1.4,
      spaceBetween: 15,
      speed: 800,
      allowTouchMove: true,
      watchSlidesProgress: true,
      slideToClickedSlide: true,
      loopAdditionalSlides: 2,
      loopPreventsSliding: true,
      loop: true,
      breakpoints: {
        591: {
          slidesPerView: 1.9,
        },
        992: {
          slidesPerView: 2.05,
        },
      },
    });

    const contentSwiper = new Swiper(contentEl, {
      slidesPerView: 1,
      speed: 800,
      allowTouchMove: true,
      watchSlidesProgress: true,
      loop: true,
    });

    imageSwiper.controller.control = contentSwiper;
    contentSwiper.controller.control = imageSwiper;
  };

  const handleTeamSwiper3 = () => {
    const imageEl = document.querySelector(".team-image-swiper-2");
    const contentEl = document.querySelector(".team-content-swiper-2");

    if (!imageEl || !contentEl) return;

    const imageSwiper2 = new Swiper(imageEl, {
      slidesPerView: 1.4,
      spaceBetween: 15,
      speed: 800,
      allowTouchMove: true,
      watchSlidesProgress: true,
      loop: true,
      breakpoints: {
        591: {
          slidesPerView: 1.9,
        },
        992: {
          slidesPerView: 1.68,
        },
      },
    });

    const contentSwiper2 = new Swiper(contentEl, {
      slidesPerView: 1,
      speed: 800,
      allowTouchMove: true,
      watchSlidesProgress: true,
      loop: true,
    });

    imageSwiper2.controller.control = contentSwiper2;
    contentSwiper2.controller.control = imageSwiper2;
  };

  let heroSwiperInitialized = false;
  let mainSwiperInstance = null;
  let thumbsSwiperInstance = null;
  let heroEffectInstance = null;

  const handleHeroBannerSwiper = () => {
    const sliderEl = document.querySelector(".hero-banner-swiper");

    if (!sliderEl || heroSwiperInitialized) return;

    heroSwiperInitialized = true;

    const slides = sliderEl.querySelectorAll(".swiper-slide");
    const parentEl = document.querySelector(".hero-effect-container");

    if (!slides.length || !parentEl) return;

    heroEffectInstance = new hoverEffect({
      parent: parentEl,
      intensity: 0.6,
      image1: slides[0].dataset.img1,
      image2: slides[0].dataset.img2,
      displacementImage: "../assets/images/1.jpg",
      speedIn: 0.7,
      speedOut: 0.7,
      hover: false,
    });

    thumbsSwiperInstance = new Swiper(".hero-banner-swiper-thumbs", {
      slidesPerView: 4,
      spaceBetween: 30,
      freeMode: true,
      watchSlidesProgress: true,
      breakpoints: {
        1481: { slidesPerView: 4, spaceBetween: 30 },
        1200: { slidesPerView: 3, spaceBetween: 30 },
        1024: { slidesPerView: 2, spaceBetween: 15 },
        576: { slidesPerView: 2, spaceBetween: 15 },
        320: { slidesPerView: 1, spaceBetween: 15 },
      },
    });

    mainSwiperInstance = new Swiper(sliderEl, {
      effect: "fade",
      fadeEffect: { crossFade: false },
      navigation: {
        nextEl: ".hero-banner-button-next",
        prevEl: ".hero-banner-button-prev",
      },
      thumbs: { swiper: thumbsSwiperInstance },
    });

    const currentEl = document.querySelector(".hero-banner-slider__current");
    const totalEl = document.querySelector(".hero-banner-slider__total");

    const updatePagination = () => {
      const total = slides.length;
      const current = mainSwiperInstance.realIndex + 1;

      if (currentEl) {
        currentEl.textContent = current < 10 ? `0${current}` : current;
      }
      if (totalEl) {
        totalEl.textContent = total < 10 ? `0${total}` : total;
      }
    };

    mainSwiperInstance.on("slideChangeTransitionStart", () => {
      const i = mainSwiperInstance.activeIndex;
      heroEffectInstance?.forceTransition(
        slides[i].dataset.img1,
        slides[i].dataset.img2,
      );
    });

    mainSwiperInstance.on("slideChange", updatePagination);

    updatePagination();
  };

  const handleServiceSwiper4 = function () {
    const swiperContainer = document.querySelector(".service-swiper-4");

    if (swiperContainer) {
      const ServiceSwiper = new Swiper(".service-swiper-4", {
        loop: true,
        spaceBetween: 20,
        slidesPerView: 4,
        autoplay: {
          delay: 3000,
        },
        breakpoints: {
          1481: {
            slidesPerView: 3.7,
          },
          1200: {
            slidesPerView: 3,
          },
          1024: {
            slidesPerView: 2,
          },
          991: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 2,
          },
          576: {
            slidesPerView: 2,
          },
          320: {
            slidesPerView: 1,
          },
        },
      });
    }
  };

  return {
    load() {
      handleTestimonialSwiper9();
      handleClientSwiper2();
      BlogSlideshowSwiper();
      handleServiceSwiper4();
      handleVerticalSwiper();
      handleTeamSwiper2();
      handleTeamSwiper3();
      handleHeroBannerSwiper();
    },
  };
};

window.addEventListener("load", function () {
  ClinicMasterCarousel().load();
});
