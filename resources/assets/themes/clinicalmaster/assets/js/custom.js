const ClinicMaster = (function () {
  "use strict";

  let pricingTabsInitialized = false;

  const handlePricingTabs = () => {
    const toggleTabs = document.querySelector(".toggle-tabs");
    if (!toggleTabs || pricingTabsInitialized) return;

    pricingTabsInitialized = true;

    const priceYear = document.querySelectorAll(".pricingtable-price.year");
    const priceMonth = document.querySelectorAll(".pricingtable-price.month");

    const setMode = (mode) => {
      const isMonthly = mode === "monthly";

      toggleTabs.classList.toggle("monthly", isMonthly);
      toggleTabs.classList.toggle("yearly", !isMonthly);

      priceMonth.forEach((el) => {
        el.style.display = isMonthly ? "block" : "none";
      });

      priceYear.forEach((el) => {
        el.style.display = isMonthly ? "none" : "block";
      });
    };

    setMode("monthly");

    toggleTabs.addEventListener("click", (e) => {
      const btn = e.target.closest(".monthly, .yearly");
      if (!btn) return;

      setMode(btn.classList.contains("monthly") ? "monthly" : "yearly");
    });
  };

  const handleSetCurrentYear = () => {
    const currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let elements = document.getElementsByClassName("current-year");

    for (const element of elements) {
      element.innerHTML = currentYear;
    }
  };

  const handledzNumber = () => {
    const dzNumber = document.querySelectorAll(".dz-number");

    dzNumber.forEach((element) => {
      element.addEventListener("input", () => {
        const inputVal = element.value;
        const numericVal = inputVal.replace(/\D/g, "");

        element.value =
          numericVal.length > 10 ? numericVal.slice(0, 10) : numericVal;
      });
    });
  };

  const handleBoxHover = () => {
    try {
      const wrappers = document.querySelectorAll(".box-hover-wrapper");
      if (!wrappers.length) return;

      wrappers.forEach((wrapper) => {
        wrapper.addEventListener("mouseover", (e) => {
          try {
            const card = e.target.closest(".box-hover");
            if (!card || !wrapper.contains(card)) return;

            wrapper
              .querySelectorAll(".box-hover.active")
              .forEach((c) => c.classList.remove("active"));
            card.classList.add("active");
          } catch (err) {
            console.error("handleBoxHover mouseover error:", err);
          }
        });
      });
    } catch (err) {
      console.error("handleBoxHover error:", err);
    }
  };

  const handleCounter = () => {
    const counters = document.querySelectorAll(".value");
    if (!counters.length) return;

    const DURATION = 1200;

    const runCounter = (counter) => {
      const target = Number(counter.dataset.value);
      if (isNaN(target)) return;

      const startTime = performance.now();

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / DURATION, 1);

        counter.innerText = Math.floor(progress * target);

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          counter.innerText = target;
        }
      };

      requestAnimationFrame(update);
    };

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            runCounter(entry.target);
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.4 },
    );

    counters.forEach((counter) => observer.observe(counter));
  };

  let navScrollInitialized = false;

  const handleNavScroller = () => {
    try {
      if (navScrollInitialized) return;
      navScrollInitialized = true;

      let previousScroll = 0;
      let ticking = false;

      const body = document.body;
      const extraNav = document.querySelector(".extra-nav");
      if (!extraNav) return;

      const update = () => {
        try {
          const screenWidth = window.innerWidth;
          if (screenWidth > 768) {
            ticking = false;
            return;
          }

          const scrollTop =
            window.scrollY || document.documentElement.scrollTop;

          const innerHeight = window.innerHeight;
          const scrollHeight = body.scrollHeight;

          if (scrollTop + innerHeight >= scrollHeight)
            extraNav.classList.add("bottom-end");
          else extraNav.classList.remove("bottom-end");

          if (scrollTop > previousScroll) extraNav.classList.add("active");
          else extraNav.classList.remove("active");

          previousScroll = scrollTop;
        } catch (err) {
          console.error("handleNavScroller scroll error:", err);
        } finally {
          ticking = false;
        }
      };

      window.addEventListener(
        "scroll",
        () => {
          if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
          }
        },
        { passive: true },
      );
    } catch (err) {
      console.error("handleNavScroller error:", err);
    }
  };

  const handleCustomSelects = () => {
    try {
      document.querySelectorAll(".dynamic-select").forEach((selectEl) => {
        initCustomSelect(selectEl);
      });
    } catch (err) {
      console.error("handleCustomSelects error:", err);
    }
  };

  let customSelectGlobalAttached = false;

  const initCustomSelect = (selectEl) => {
    try {
      if (!selectEl) return;

      if (selectEl.dataset.customized) return;
      selectEl.dataset.customized = "true";

      const selectId =
        selectEl.id || `select-${Math.random().toString(36).slice(2, 9)}`;

      const customSelectDiv = document.createElement("div");
      customSelectDiv.className = "custom-select";
      customSelectDiv.id = `custom-${selectId}`;

      const selectedDiv = document.createElement("div");
      selectedDiv.className = "select-selected";

      const textSpan = document.createElement("span");
      textSpan.className = "select-text";

      const defaultOption =
        selectEl.querySelector("option[selected]") || selectEl.options[0];

      textSpan.textContent = defaultOption?.textContent || "";

      selectedDiv.appendChild(textSpan);

      const labelText = selectEl.parentElement?.dataset?.label || "";
      if (labelText) {
        const label = document.createElement("span");
        label.className = "select-label";
        label.textContent = labelText;
        selectedDiv.appendChild(label);
      }

      customSelectDiv.appendChild(selectedDiv);

      const itemsDiv = document.createElement("div");
      itemsDiv.className = "select-items select-hide";
      customSelectDiv.appendChild(itemsDiv);

      Array.from(selectEl.options).forEach((option) => {
        const optionDiv = document.createElement("div");
        optionDiv.className = "select-item";
        optionDiv.dataset.value = option.value;
        optionDiv.textContent = option.textContent;

        if (option.selected) optionDiv.classList.add("active");

        optionDiv.addEventListener("click", (e) => {
          e.stopPropagation();

          textSpan.textContent = optionDiv.textContent;
          selectEl.value = optionDiv.dataset.value;

          selectEl.dispatchEvent(new Event("change"));

          itemsDiv
            .querySelectorAll(".select-item")
            .forEach((item) => item.classList.remove("active"));

          optionDiv.classList.add("active");

          itemsDiv.classList.add("select-hide");
          selectedDiv.classList.remove("select-active");
        });

        itemsDiv.appendChild(optionDiv);
      });

      selectEl.style.display = "none";
      selectEl.parentNode?.insertBefore(customSelectDiv, selectEl.nextSibling);

      selectedDiv.addEventListener("click", (e) => {
        e.stopPropagation();

        document.querySelectorAll(".custom-select").forEach((cs) => {
          if (cs !== customSelectDiv) {
            cs.querySelector(".select-items")?.classList.add("select-hide");
            cs.querySelector(".select-selected")?.classList.remove(
              "select-active",
            );
          }
        });

        itemsDiv.classList.toggle("select-hide");
        selectedDiv.classList.toggle("select-active");
      });

      if (!customSelectGlobalAttached) {
        document.addEventListener("click", (e) => {
          document.querySelectorAll(".custom-select").forEach((cs) => {
            if (!cs.contains(e.target)) {
              cs.querySelector(".select-items")?.classList.add("select-hide");
              cs.querySelector(".select-selected")?.classList.remove(
                "select-active",
              );
            }
          });
        });

        customSelectGlobalAttached = true;
      }
    } catch (err) {
      console.error("initCustomSelect error:", err);
    }
  };

  const handleAccordion = (container = document) => {
    try {
      const accordionContainers = container.querySelectorAll(".myAccordion");

      accordionContainers.forEach((accordion) => {
        if (accordion.dataset.bound === "true") return;
        accordion.dataset.bound = "true";

        accordion.addEventListener("click", (e) => {
          try {
            const header = e.target.closest(".accordion-header");
            if (!header || !accordion.contains(header)) return;

            const item = header.parentElement;
            const content = item.querySelector(".accordion-content");
            const arrow = header.querySelector(".arrow");
            const isOpen = header.classList.contains("open");

            accordion.querySelectorAll(".accordion-header").forEach((h) => {
              if (h !== header) {
                h.classList.remove("open");
                h.querySelector(".arrow")?.classList.remove("active");
                const c = h.parentElement.querySelector(".accordion-content");
                if (c) c.style.maxHeight = null;
              }
            });

            if (!isOpen) {
              header.classList.add("open");
              if (content)
                content.style.maxHeight = content.scrollHeight + "px";
              arrow?.classList.add("active");
            } else {
              header.classList.remove("open");
              if (content) content.style.maxHeight = null;
              arrow?.classList.remove("active");
            }
          } catch (err) {
            console.error("handleAccordion click error:", err);
          }
        });
      });

      container.querySelectorAll(".accordion-header.open").forEach((header) => {
        try {
          const content =
            header.parentElement.querySelector(".accordion-content");
          const arrow = header.querySelector(".arrow");
          if (content) content.style.maxHeight = content.scrollHeight + "px";
          arrow?.classList.add("active");
        } catch (err) {
          console.error("handleAccordion init error:", err);
        }
      });
    } catch (err) {
      console.error("handleAccordion error:", err);
    }
  };

  let videoPopupInitialized = false;

  const handleVideoPopup = () => {
    try {
      if (videoPopupInitialized) return;
      videoPopupInitialized = true;

      const dialog = document.getElementById("videoDialog");
      const container = document.getElementById("videoContainer");
      const closeBtn = document.getElementById("closeBtn");
      const videoWrapper = document.body;

      if (!dialog || !container || !closeBtn) return;

      const ALLOWED_HOSTS = [
        "www.youtube.com",
        "youtube.com",
        "youtu.be",
        "player.vimeo.com",
        "vimeo.com",
      ];

      const isSafeURL = (url) => {
        try {
          const parsed = new URL(url, window.location.origin);
          return ALLOWED_HOSTS.includes(parsed.hostname);
        } catch {
          return false;
        }
      };

      const openVideo = (type, src) => {
        try {
          container.textContent = "";

          if (type === "youtube" || type === "vimeo") {
            if (!isSafeURL(src)) return;

            const iframe = document.createElement("iframe");
            iframe.src = src + "?autoplay=1";
            iframe.allow = "autoplay; encrypted-media; fullscreen";
            iframe.allowFullscreen = true;
            iframe.loading = "lazy";

            container.appendChild(iframe);
          }

          if (type === "mp4") {
            const video = document.createElement("video");
            video.controls = true;
            video.autoplay = true;

            const source = document.createElement("source");
            source.src = src;
            source.type = "video/mp4";

            video.appendChild(source);
            container.appendChild(video);
          }

          dialog.style.display = "flex";
        } catch (err) {
          console.error("openVideo error:", err);
        }
      };

      const closeVideo = () => {
        try {
          container.textContent = "";
          dialog.style.display = "none";
        } catch (err) {
          console.error("closeVideo error:", err);
        }
      };

      const onOpenVideo = (e) => {
        const button = e.target.closest("button[data-type][data-src]");
        if (!button) return;

        openVideo(
          button.getAttribute("data-type"),
          button.getAttribute("data-src"),
        );
      };

      videoWrapper.addEventListener("click", onOpenVideo);
      closeBtn.addEventListener("click", closeVideo);
    } catch (err) {
      console.error("handleVideoPopup error:", err);
    }
  };

  const handleCountdown = () => {
    try {
      const counter = document.querySelector("#countdown");
      if (!counter) return;

      const countDownClock = (number = 100, format = "seconds") => {
        try {
          const d = document;
          const daysElement = d.querySelector("#countdown .days");
          const hoursElement = d.querySelector("#countdown .hours");
          const minutesElement = d.querySelector("#countdown .minutes");
          const secondsElement = d.querySelector("#countdown .seconds");
          let countdown;

          const convertFormat = (format) => {
            switch (format) {
              case "seconds":
                return timer(number);
              case "minutes":
                return timer(number * 60);
              case "hours":
                return timer(number * 60 * 60);
              case "days":
                return timer(number * 60 * 60 * 24);
            }
          };

          const timer = (seconds) => {
            const now = Date.now();
            const then = now + seconds * 1000;

            countdown = setInterval(() => {
              try {
                const secondsLeft = Math.round((then - Date.now()) / 1000);
                if (secondsLeft <= 0) {
                  clearInterval(countdown);
                  return;
                }
                displayTimeLeft(secondsLeft);
              } catch (err) {
                console.error("timer interval error:", err);
              }
            }, 1000);
          };

          const displayTimeLeft = (seconds) => {
            try {
              daysElement.textContent = Math.floor(seconds / 86400);
              hoursElement.textContent = Math.floor((seconds % 86400) / 3600);
              minutesElement.textContent = Math.floor(
                ((seconds % 86400) % 3600) / 60,
              );
              secondsElement.textContent =
                seconds % 60 < 10 ? `0${seconds % 60}` : seconds % 60;
            } catch (err) {
              console.error("displayTimeLeft error:", err);
            }
          };

          convertFormat(format);
        } catch (err) {
          console.error("countDownClock error:", err);
        }
      };

      countDownClock(20, "days");
    } catch (err) {
      console.error("handleCountdown error:", err);
    }
  };

  const handlePreloaderBars = () => {
    try {
      if (typeof window.gsap === "undefined") return () => {};

      const innerBars = document.querySelectorAll(".inner-bar");
      const preloader = document.querySelector(".preloader");
      const overlay = document.querySelector(".preloader-overlay");

      if (!innerBars.length || !preloader || !overlay) {
        return () => {};
      }

      let index = 0;
      let timeoutId = null;
      let isDestroyed = false;

      const animatePair = () => {
        if (isDestroyed) return;

        const first = innerBars[index];
        const second = innerBars[index + 1];

        if (!first || !second) {
          finishPreloader();
          return;
        }

        [first, second].forEach((bar) => {
          const randomWidth = Math.floor(Math.random() * 101);

          gsap.to(bar, {
            width: `${randomWidth}%`,
            duration: 0.2,
            ease: "none",
          });
        });

        timeoutId = window.setTimeout(() => {
          if (isDestroyed) return;

          [first, second].forEach((bar) => {
            gsap.to(bar, {
              width: "100%",
              duration: 0.2,
              ease: "none",
            });
          });

          index += 2;
          animatePair();
        }, 200);
      };

      const finishPreloader = () => {
        if (isDestroyed) return;

        gsap
          .timeline()
          .to(overlay, {
            x: 0,
            duration: 0.5,
            ease: "none",
            delay: 0.4,
          })
          .set(preloader, { display: "none" });
      };

      timeoutId = window.setTimeout(animatePair, 1000);

      return () => {
        isDestroyed = true;
        if (timeoutId) clearTimeout(timeoutId);
        gsap.killTweensOf(innerBars);
        gsap.killTweensOf([overlay, preloader]);
      };
    } catch (error) {
      console.error("handlePreloaderBars failed:", error);
      return () => {};
    }
  };

  const handleMenuActive = () => {
    try {
      const nav = document.querySelector(".navbar-nav");
      if (!nav) return () => {};

      const getCurrentPath = () => {
        try {
          return window.location.pathname.split("/").pop().toLowerCase();
        } catch {
          return "";
        }
      };

      const setActiveMenu = () => {
        try {
          const currentPath = getCurrentPath();
          if (!currentPath) return;

          nav
            .querySelectorAll(".active")
            .forEach((el) => el.classList.remove("active"));

          const links = nav.querySelectorAll(
            'a[href]:not([href^="javascript"])',
          );

          links.forEach((link) => {
            const href = link.getAttribute("href");
            if (!href) return;

            const linkPath = href.split("/").pop().toLowerCase();

            if (linkPath === currentPath) {
              link.classList.add("active");

              const parentLi = link.closest("li");
              if (parentLi) parentLi.classList.add("active");

              const topLevelLi = link.closest(".navbar-nav > li");
              if (topLevelLi) {
                topLevelLi.classList.add("active");

                const topAnchor = topLevelLi.querySelector(":scope > a");
                if (topAnchor) topAnchor.classList.add("active");
              }
            }
          });
        } catch (err) {
          console.error("Menu active error:", err);
        }
      };

      setActiveMenu();

      return () => {};
    } catch (err) {
      console.error("handleMenuActive failed:", err);
      return () => {};
    }
  };

  const handleAppointmentWizard = () => {
    const initWizard = (selector, orientation = "vertical") => {
      const container = document.querySelector(selector);
      if (!container) return;

      const args = {
        wz_class: selector,
        wz_nav_style: "dots",
        wz_button_style: ".btn .btn-sm .mx-3",
        wz_ori: orientation,
        buttons: true,
        navigation: "all",
        finish: "Submit",
        bubble: true,
      };

      const wizard = new Wizard(args);
      wizard.init();

      const monthEl = container.querySelector(".month");
      const yearEl = container.querySelector(".year");
      const calendarDays = container.querySelector(".calendar-days");
      const selectedDateEl = container.querySelector(".selected-date");
      const slotsContainer = container.querySelector(".slots-container");

      let currentDate = new Date();
      let selectedDay = null;

      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      const timeSlots = [
        "9:00 AM - 10:30 AM",
        "10:30 AM - 12:00 PM",
        "11:00 AM - 12:30 PM",
        "11:30 AM - 1:00 PM",
        "12:00 PM - 1:30 PM",
        "12:30 PM - 2:00 PM",
        "1:00 PM - 2:30 PM",
        "1:30 PM - 3:00 PM",
        "2:00 PM - 3:30 PM",
        "2:30 PM - 4:00 PM",
        "3:00 PM - 4:30 PM",
        "3:30 PM - 5:00 PM",
      ];

      function populateDropdowns() {
        monthEl.innerHTML = "";
        months.forEach((m, i) => {
          const opt = document.createElement("option");
          opt.value = i;
          opt.textContent = m;
          if (i === currentDate.getMonth()) opt.selected = true;
          monthEl.appendChild(opt);
        });

        yearEl.innerHTML = "";
        for (
          let y = currentDate.getFullYear();
          y <= currentDate.getFullYear() + 2;
          y++
        ) {
          const opt = document.createElement("option");
          opt.value = y;
          opt.textContent = y;
          if (y === currentDate.getFullYear()) opt.selected = true;
          yearEl.appendChild(opt);
        }
      }

      function generateCalendar() {
        calendarDays.innerHTML = "";
        const month = parseInt(monthEl.value);
        const year = parseInt(yearEl.value);
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const start = firstDay === 0 ? 6 : firstDay - 1;

        for (let i = 0; i < start; i++) {
          const blank = document.createElement("div");
          calendarDays.appendChild(blank);
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const d = new Date(year, month, day);
          const dayEl = document.createElement("div");
          dayEl.className = "day";
          dayEl.textContent = day;

          if (d.toDateString() === selectedDay?.toDateString()) {
            dayEl.classList.add("selected");
          }

          dayEl.onclick = () => {
            selectedDay = d;
            container
              .querySelectorAll(".day")
              .forEach((el) => el.classList.remove("selected"));
            dayEl.classList.add("selected");
            selectedDateEl.textContent = d.toDateString();
            renderTimeSlots();
          };

          calendarDays.appendChild(dayEl);
        }
      }

      function renderTimeSlots() {
        slotsContainer.innerHTML = "";
        timeSlots.forEach((slot) => {
          const div = document.createElement("div");
          div.className = "slot";
          div.textContent = slot;
          div.onclick = () => {
            container
              .querySelectorAll(".slot")
              .forEach((s) => s.classList.remove("selected"));
            div.classList.add("selected");
          };
          slotsContainer.appendChild(div);
        });
      }

      container.querySelector(".prev-month").onclick = (e) => {
        e.preventDefault();
        if (monthEl.selectedIndex > 0) {
          monthEl.selectedIndex--;
        } else if (yearEl.selectedIndex > 0) {
          yearEl.selectedIndex--;
          monthEl.selectedIndex = 11;
        }
        generateCalendar();
      };

      container.querySelector(".next-month").onclick = (e) => {
        e.preventDefault();
        if (monthEl.selectedIndex < 11) {
          monthEl.selectedIndex++;
        } else {
          monthEl.selectedIndex = 0;
          yearEl.selectedIndex++;
        }
        generateCalendar();
      };

      monthEl.onchange = generateCalendar;
      yearEl.onchange = generateCalendar;

      populateDropdowns();
      generateCalendar();
    };

    initWizard(".wizard-vertical", "vertical");

    initWizard(".wizard-horizontal", "horizontal");
  };

  /* Function ============ */
  return {
    init() {
      handlePricingTabs();
      handleSetCurrentYear();
      handledzNumber();
      handleBoxHover();
      handleCounter();
      handleNavScroller();
      handleAppointmentWizard();
      handleCustomSelects();
      handleAccordion();
      handleVideoPopup();
      handleCountdown();
      handlePreloaderBars();
      handleMenuActive();
    },

    load() {},

    resize() {},
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  ClinicMaster.init();
});

window.addEventListener("load", function () {
  ClinicMaster.load();
  const dzPreloader = document.getElementById("dzPreloader");
  setTimeout(function () {
    if (dzPreloader) {
      dzPreloader.remove();
    }
  }, 1000);
  document.body.addEventListener("keydown", function () {
    document.body.classList.add("show-focus-outline");
  });
  document.body.addEventListener("mousedown", function () {
    document.body.classList.remove("show-focus-outline");
  });
});

window.addEventListener("resize", function () {
  ClinicMaster.resize();
});
