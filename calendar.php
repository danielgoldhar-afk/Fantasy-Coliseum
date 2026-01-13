function custom_calendar_shortcode() {
    ob_start(); ?>

    <!-- ✅ Calendar Wrapper -->
    <div class="calendar-wrapper">
      <div class="date-picker">
        <button class="date-btn" id="prev">
          <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft.png" alt="Previous">
        </button>
        <div class="dates" id="dates"></div>
        <button class="date-btn" id="next">
          <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft-1.png" alt="Next">
        </button>
      </div>

      <!-- Calendar Icon Button -->
      <button class="calendar-btn" id="calendarBtn">
        <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/Vector-1.png" alt="Calendar" width="28">
      </button>

      <!-- Hidden date input for jQuery UI datepicker -->
      <input type="text" id="datepicker">
    </div>

    <style>
    body {
      font-family: Arial, sans-serif;
      color: #fff;
    }
    .calendar-wrapper {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      width: 100%;
      max-width: 1540px;
      justify-content: center;
    }

    .date-picker {
      max-width: 100%;
      height: 64px;
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 0 8px;
      color: #fff;
      gap: 8px;
      box-shadow: 0px 13px 16.3px 0px rgba(0, 0, 0, 0.12);
      flex: 1;
      overflow-x: auto;
      scroll-behavior: smooth;
    }

    .date-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border: none;
      color: #fff;
      width: 48px;
      height: 48px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .dates {
      display: flex;
      gap: 16px;
      flex: 1;
    }

    .date-item {
      text-align: center;
      padding: 8px 12px;
      border-radius: 12px;
      transition: 0.3s;
      min-width: 90px;
      cursor: pointer;
      flex-shrink: 0;
    }
    .date-item.active {
      background: rgba(255, 255, 255, 0.2);
    }
    .day {
      font-size: 12px;
      opacity: 0.8;
    }
    .date {
      font-size: 14px;
      font-weight: 600;
    }

    .calendar-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      color: #fff;
      width: 64px;
      height: 64px;
      border-radius: 8px;
      box-shadow: 0px 6px 19.3px 0px rgba(0, 0, 0, 0.24);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 0.3px solid rgba(255, 255, 255, 0.15);
      flex-shrink: 0;
      position: relative;
    }

    #datepicker {
      position: absolute;
      left: -9999px;
      opacity: 0;
    }

    /* ==================== Calendar Styling ==================== */
    .ui-datepicker {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 12px;
      width: 100%;
      max-width: 320px;
      box-shadow: 0px 13px 16.3px 0px rgba(0, 0, 0, 0.12);
      z-index: 9999 !important;
      font-family: Arial, sans-serif;
    }

    .ui-datepicker-header {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border-radius: 8px;
      padding: 12px;
      color: #fff;
      font-weight: bold;
      font-size: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .ui-datepicker-prev,
    .ui-datepicker-next {
      cursor: pointer;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      transition: 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .ui-datepicker-prev:hover,
    .ui-datepicker-next:hover {
      background: rgba(255,255,255,0.4);
    }
    .ui-datepicker-prev span,
    .ui-datepicker-next span {
      display: none !important;
    }

    .ui-datepicker-prev {
      background: url('<?php echo get_stylesheet_directory_uri(); ?>/images/ArrowLeft.svg') no-repeat center center;
      background-size: 14px 14px;
    }

    .ui-datepicker-next {
      background: url('<?php echo get_stylesheet_directory_uri(); ?>/images/ArrowRight.svg') no-repeat center center;
      background-size: 14px 14px;
    }

    .ui-datepicker thead th {
      color: #bbb;
      font-size: 12px;
      font-weight: normal;
      padding: 6px;
      text-align: center;
    }

    .ui-datepicker td a {
      display: block;
      padding: 8px 0;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      transition: 0.2s;
      text-align: center;
      color: #fff;
    }

    .ui-datepicker td a:hover {
      background: #ffa2053b !important;
    }

    .ui-state-active {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%) !important;
      color: #fff !important;
      font-weight: bold;
      border: none;
    }

    .ui-state-highlight {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border-radius: 8px;
      border: none;
    }

    @media (max-width: 1024px) {
      .date-picker {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
      .date-picker::-webkit-scrollbar {
        display: none;
      }
    }

    @media (min-width: 1025px) {
      .date-picker {
        width: 100%;
        overflow: hidden;
        justify-content: space-between;
      }
      .dates {
        flex: 1;
        justify-content: space-between;
      }
    }
    </style>

    <script>
    jQuery(document).ready(function ($) {
      function generateDates(startDate, selectedDate = null) {
        const datesContainer = $("#dates");
        datesContainer.empty();

        for (let i = 0; i < 10; i++) {
          let d = new Date(startDate);
          d.setDate(startDate.getDate() + i);

          const dayName = d.toLocaleDateString("en-US", { weekday: "short" });
          const monthDay = d.toLocaleDateString("en-US", { month: "short", day: "numeric" });

          let item = $(
            `<div class="date-item" data-date="${d.toISOString().split("T")[0]}">
              <div class="day">${dayName}</div>
              <div class="date">${monthDay}</div>
            </div>`
          );

          if ((selectedDate && d.toDateString() === selectedDate.toDateString()) || (!selectedDate && i === 0)) {
            item.addClass("active");
            setTimeout(() => {
              item[0].scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
            }, 100);
          }

          datesContainer.append(item);
        }

        $(".date-item").click(function () {
          $(".date-item").removeClass("active");
          $(this).addClass("active");
          this.scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
        });
      }

      let today = new Date();
      let startDate = new Date(today);
      generateDates(startDate, today);

      $("#prev").click(function () {
        startDate.setDate(startDate.getDate() - 10);
        generateDates(startDate);
      });

      $("#next").click(function () {
        startDate.setDate(startDate.getDate() + 10);
        generateDates(startDate);
      });

      $("#datepicker").datepicker({
        showAnim: "fadeIn",
        dateFormat: "yy-mm-dd",
        beforeShow: function (input, inst) {
          var btn = $("#calendarBtn");
          var offset = btn.offset();
          setTimeout(function () {
            let left = offset.left;
            let dpWidth = inst.dpDiv.outerWidth();
            if (left + dpWidth > $(window).width()) {
              left = $(window).width() - dpWidth - 10;
            }
            inst.dpDiv.css({
              top: offset.top + btn.outerHeight() + 5,
              left: left
            });
          }, 0);
        },
        onSelect: function (dateText) {
          let picked = new Date(dateText);
          generateDates(picked, picked);
        }
      });

      $("#calendarBtn").click(function () {
        $("#datepicker").datepicker("show");
      });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('custom_calendar', 'custom_calendar_shortcode');
