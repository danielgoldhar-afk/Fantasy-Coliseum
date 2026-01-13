 <?php
/**
 * Template Name: Live Scoring
 * Template Post Type: page
 */

?>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard.css">



<body>
    <div class="dashboard">
      <div class="head-title">
        <h1>Live Scoring</h1>
        <h3>2025-26 NHL <span>Testing league</span></h3>
      </div>

      <div class="content">
        <div class="sidebar">
          <div class="card">
            <div class="cart-tabs">
              <div class="cart-sort"><h5>Sort</h5></div>
              <div class="tabs-main">
                <div class="cart-tab active">Active</div>
                <div class="cart-tab">Bench</div>
              </div>
            </div>
          </div>
          <div class="cart-card">
            <div class="cart-left">
              <div class="member-cart">
                <div style="display: flex; gap: 10px;">
                  <img
                    src="https://fantraximg.com/assets/images/icons/fantasyteams/hockey/jerseys/yellow_green_128.webp"
                    alt="Jersey">
                  <h3>1</h3>
                </div>

                <p style="margin: 0%;">CBUM</p>
                <div class="cart-stats">
                  <span>👥 0 0 0</span>
                  <span>⏱ 0</span>
                </div>
              </div>

            </div>
            <div class="cart-right">
              <div class="cart-season">
                <p>Season</p>
                <h4>0</h4>
              </div>
              <div class="cart-day">
                <p>Day</p>
                <h4>0</h4>
              </div>
            </div>
          </div>

        </div>

        <div
          style="display: flex; flex-direction: column; width: 100%; height: fit-content; gap: 40px;">
          <div class="main">
            <div class="stats">
              <div class="stat-box">
                <div class="match-card">
                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Utah-Mammoth_logo_96.webp"
                        alt="UTA">
                      <span class="team-name">UTA</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Toronto-Maple-Leafs_logo_96.webp"
                        alt="TOR">
                      <span class="team-name">TOR</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="match-time">Thu 5:00AM</div>
                </div>
              </div>
              <div class="stat-box">
                <div class="match-card">
                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Utah-Mammoth_logo_96.webp"
                        alt="UTA">
                      <span class="team-name">UTA</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Toronto-Maple-Leafs_logo_96.webp"
                        alt="TOR">
                      <span class="team-name">TOR</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="match-time">Thu 5:00AM</div>
                </div>
              </div>
              <div class="stat-box">
                <div class="match-card">
                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Utah-Mammoth_logo_96.webp"
                        alt="UTA">
                      <span class="team-name">UTA</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="team">
                    <div class="team-left">
                      <img
                        src="https://fantraximg.com/assets/images/logos/sportsteam/nhl/Toronto-Maple-Leafs_logo_96.webp"
                        alt="TOR">
                      <span class="team-name">TOR</span>
                    </div>
                    <span class="team-score">0</span>
                  </div>

                  <div class="match-time">Thu 5:00AM</div>
                </div>
              </div>

            </div>

          </div>

          <div class="">
            <div class="top-bar">
      <!-- Tabs -->
      <div class="tabs">
        <button class="tab active" id="teamTab">Team</button>
        <button class="tab" id="statsTab">Stats</button>
      </div>

      <!-- Filters Row -->
      <div class="row">
        <div class="field">
          <label>Timeframes</label>
          <div class="time-icons">
            <button class="time-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 11H4V19H20V11ZM11 13V17H6V13H11ZM7 5H4V9H20V5H17V7H15V5H9V7H7V5Z"></path></svg></button>
            <button class="time-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 11H4V19H20V11ZM8 14V16H6V14H8ZM18 14V16H10V14H18ZM7 5H4V9H20V5H17V7H15V5H9V7H7V5Z"></path></svg></button>
          </div>
        </div>

        <div class="date-field">
          <label>Date</label>
          <select class="date-select" id="dateSelect">
            <option>Mon Nov 3</option>
            <option>Tue Nov 4</option>
            <option>Wed Nov 5</option>
            <option>Thu Nov 6</option>
          </select>
          <div class="date-icons">
            <button class="arrow-btn" id="prevDate">&#8249;</button>
            <button class="arrow-btn" id="nextDate">&#8250;</button>
          </div>
        </div>

        <div class="right-side">
          <div class="field">
            <label>Projected</label>
            <div class="toggle" id="projectedToggle">
              <div class="toggle-circle"></div>
            </div>
          </div>
          

          <div class="field">
            <label>Mode</label>
            <div class="mode">
              <button class="active" id="statsBtn">Stats</button>
              <button id="fptsBtn">Fpts</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Team Tab: Member Cards -->
    <div class="team-container visible" id="teamContainer">
      <div class="cart-card">
        <div class="cart-left">
          <img
            src="https://fantraximg.com/assets/images/icons/fantasyteams/hockey/jerseys/yellow_green_128.webp"
            alt>
          <div class="member-carts">
            <p>CBUM</p>
            <div class="cart-statss">
              <span>👥 0 0 0</span>
              <span>⏱ 0</span>
            </div>
          </div>
        </div>
        <div class="cart-right">
          <div class="cart-season">
            <p>Season</p><h4>0</h4>
          </div>
          <div class="cart-day">
            <p>Day</p><h4>0</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Tab -->
  <div class="table-wrapper">
  <table class="stats-table" id="statsTable">
    <thead>
      <tr>
        <th>Rk</th><th>Team</th><th>FPts</th><th>Proj</th><th>G</th><th>A</th><th>PIM</th><th>SOG</th><th>PPP</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>1</td><td>CBUM</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
      <tr><td>2</td><td>Romail</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
    </tbody>
  </table>
</div>

          </div>

        </div>

      </div>
    </div>

 <script>
  // === Sidebar Tabs (Active/Bench) ===
  const tabs = document.querySelectorAll('.cart-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

  // === Top Bar Tabs: Team / Stats ===
  const teamTab = document.getElementById("teamTab");
  const statsTab = document.getElementById("statsTab");
  const teamContainer = document.getElementById("teamContainer");
  const statsTable = document.getElementById("statsTable");

  teamTab.addEventListener("click", () => {
    teamTab.classList.add("active");
    statsTab.classList.remove("active");
    teamContainer.classList.add("visible");
    statsTable.classList.remove("visible");
  });

  statsTab.addEventListener("click", () => {
    statsTab.classList.add("active");
    teamTab.classList.remove("active");
    statsTable.classList.add("visible");
    teamContainer.classList.remove("visible");
  });

  // === Date Navigation ===
  const dateSelect = document.getElementById("dateSelect");
  const prevDate = document.getElementById("prevDate");
  const nextDate = document.getElementById("nextDate");
  let currentDate = new Date();

  function formatDate(date) {
    return date.toLocaleDateString("en-US", {
      weekday: "short",
      month: "short",
      day: "numeric"
    });
  }

  function updateDateSelect() {
    const formattedDate = formatDate(currentDate);
    let found = false;

    for (let i = 0; i < dateSelect.options.length; i++) {
      if (dateSelect.options[i].textContent === formattedDate) {
        dateSelect.selectedIndex = i;
        found = true;
        break;
      }
    }

    if (!found) {
      const customOption = document.createElement("option");
      customOption.value = "customDate";
      customOption.textContent = formattedDate;
      dateSelect.appendChild(customOption);
      dateSelect.value = "customDate";
    }
  }

  prevDate.addEventListener("click", () => {
    currentDate.setDate(currentDate.getDate() - 1);
    updateDateSelect();
  });

  nextDate.addEventListener("click", () => {
    currentDate.setDate(currentDate.getDate() + 1);
    updateDateSelect();
  });

  updateDateSelect();

  const projectedToggle = document.getElementById("projectedToggle");

projectedToggle.addEventListener("click", () => {
  projectedToggle.classList.toggle("active");
});


  // === Mode Buttons (Stats / Fpts) ===
  const statsBtn = document.getElementById("statsBtn");
  const fptsBtn = document.getElementById("fptsBtn");

  statsBtn.addEventListener("click", () => {
    statsBtn.classList.add("active");
    fptsBtn.classList.remove("active");
  });

  fptsBtn.addEventListener("click", () => {
    fptsBtn.classList.add("active");
    statsBtn.classList.remove("active");
  });


  
</script>


  </body>




 <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    body {
      margin: 0;
      padding: 0;
      background: #0b111a;
      color: #fff;
      font-family: 'Inter', sans-serif;
    }

    .dashboard {
      gap: 20px;
      padding: 40px;
      flex-wrap: wrap;
    }

    .sidebar {
      flex: 1 1 400px;
    }

    .main {
      background: #161d27;
      border-radius: 12px;
     height: fit-content;
    padding: 0px 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    }

    h2 {
      font-size: 22px;
      font-weight: 600;
      color: #ff9500;
      margin-bottom: 20px;
    }

    .card {
      background: #1b232d;
      border-radius: 12px;
      padding: 13px;
      margin-bottom: 14px;
      transition: all 0.3s ease;
    }

    .card:hover {
      background: #222b36;
      transform: translateY(-2px);
    }

    .card h3 {
      font-size: 16px;
      margin: 0 0 8px;
      color: #ffffff;
    }

    .card p {
      font-size: 14px;
      color: #aaa;
      margin: 0;
    }

    .input-box {
      display: flex;
      flex-direction: column;
      margin-bottom: 16px;
    }

    label {
      font-size: 14px;
      margin-bottom: 6px;
      color: #ccc;
    }

    input, select {
      padding: 10px 12px;
      border-radius: 6px;
      border: 1px solid #1f2933;
      background: #0b111a;
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
    }

    input:hover, select:hover {
      border-color: #ff9500;
    }

    .btn {
      background: #ff9500;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background: #ffb74d;
      transform: translateY(-1px);
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 16px;
    }

    .stat-box {
      border-right: 1px solid;  
      padding: 0px 20px 0px 0px;
      transition: 0.3s ease;
    }

    .stat-box:hover {
      background: #222b36;
    }

    .stat-box h4 {
      color: #ff9500;
      font-size: 18px;
      margin-bottom: 6px;
    }

    .stat-box span {
      color: #fff;
      font-size: 14px;
    }

    .head-title{}

    .content{
      display: flex;
      gap: 24px;
    }

    /* New Cart Styles */
    .cart-section {
      background: #191c1f;
      border-radius: 8px;
      padding: 16px;
      margin-top: 24px;
    }

    .cart-section h3 {
      font-size: 18px;
      color: #ff9500;
      margin-bottom: 12px;
    }

    .cart-input {
      display: flex;
      flex-direction: column;
      margin-bottom: 16px;
    }

    .cart-input input {
      padding: 10px 12px;
      border-radius: 6px;
      border: 1px solid #191c1f;
      background: #0b111a;
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
    }

    .cart-input input:hover,
    .cart-input input:focus {
      border-color: #ff9500;
      box-shadow: 0 0 5px #ff9500;
    }

.cart-tabs {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 16px;
  border: 1px solid #2b3240;
  border-radius: 8px;
  background: #1b232d;
}

.cart-sort h5 {
  color: #ffffff;
  font-size: 15px;
  margin: 0;
  font-weight: 600;
}

.cart-tab {
  padding: 6px 14px;
  border-radius: 50px;
  cursor: pointer;
  color: #aaa;
  font-weight: 600;
  background: transparent;
  transition: all 0.3s ease;
  width: auto; /* 🔹 auto width according to text */
  white-space: nowrap;
}

.cart-tab.active {
  background: #ff9500;
  color: #fff;
  border-radius: 50px;
}

.cart-tab:hover {
  background: #222b36;
  color: #fff;
}

.tabs-main{
  display: flex;
  flex-direction: row;
}

.cart-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #1b232d;
  border-radius: 14px;
  padding: 14px 18px;
  color: #fff;
  min-height: 77px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}
.cart-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.cart-left img {
  width: 50px;
  height: 50px;
}

.cart-info h3 {
  font-size: 22px;
  margin: 0;
  font-weight: 700;
}

.cart-info p {
  margin: 2px 0 6px;
  font-size: 14px;
  color: #ccc;
}

.cart-stats {
  display: flex;
  gap: 10px;
  font-size: 13px;
  color: #60a5fa;
  background: #0f172a;
  padding: 4px 8px;
  border-radius: 6px;
}

.cart-right {
   
    display: flex;
    align-items: center;
    gap: 40px;
}

.cart-right p {
  font-size: 13px;
  color: #999;
  margin: 0;
  text-align: right;
}

.cart-right h4 {
  font-size: 20px;
  margin-top: 15px;
  text-align: center;
  color: #fff;
}

.cart-day p {
  color: #ff9500;
}
.member-cart{
      display: flex;
    flex-direction: column;
    position: absolute;
    top: 248px;
    gap: 5px;
}


.match-card {

  color: #fff;
  font-family: 'Inter', sans-serif;
  padding: 10px 0px;
}

.team {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 6px;
	background: none;
}

.team-left {
  display: flex;
  align-items: center;
  gap: 6px;
}

.team-left img {
  width: 26px;
  height: 26px;
}

.team-name {
  font-weight: 700;
  font-size: 15px;
  letter-spacing: 0.5px;
}

.team-score {
  color: #00ff88;
  font-weight: 700;
  font-size: 16px;
}

.match-time {
  color: #9ca3af;
  font-size: 13px;
  font-weight: 500;
  margin-top: 6px;
}

/* ✅ Responsive Breakpoints */
      @media (max-width: 1024px) {
        .content {
          flex-direction: column;
        }

        .sidebar,
        .main {
          flex: 1 1 100%;
        }

        .cart-right {
          justify-content: flex-start;
          gap: 20px;
          margin-top: 10px;
        }
      }

      @media (max-width: 768px) {


         .member-cart{
         left: 67px;
  top: 231px;
        }


        .head-title h1 {
          font-size: 24px;
        }

        .cart-card {
          flex-direction: row;
          align-items: flex-start;
        }

        .cart-right {
          flex-direction: row-reverse;
          margin-top: 10px;
        }

        .cart-tabs {
          flex-direction: row;
        }

       

        .match-card {
          padding: 12px;
        }
      }

      @media (max-width: 480px) {

        .table-wrapper {
    overflow-x: auto;
  }

        .dashboard {
          padding: 20px 10px;
        }

        .cart-left img {
          width: 40px;
          height: 40px;
        }

        .member-cart{
             left: 29px;
    top: 202px;
        }

        .stat-box{
          border-bottom: 1px solid;
          border-right: none;
        }
        .member-cart h3 {
          font-size: 18px;
        }

        .cart-tab {
          padding: 6px 10px;
          font-size: 13px;
        }

        .match-card {
          font-size: 13px;
        }

        .head-title h1 {
          font-size: 20px;
        }
      }



      .time-icons {
  display: flex;
  gap: 12px;
}

.time-icons span {
  font-size: 22px;
  cursor: pointer;
  transition: color 0.3s ease;
}

.time-icons span:hover {
  color: #ff9500;
}

/* === Timeframe Row === */
.timeframe-row {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
  padding: 14px 16px;
  border-radius: 10px;
}

/* === Date Selector === */
.date-box {
  flex: 1 1 40%;
  min-width: 250px;
  display: flex;
  flex-direction: column;
  color: #fff;
}

.date-box label {
  font-size: 14px;
  color: #ccc;
  margin-bottom: 6px;
}




/* Wrapper containing select + subtext */
.select-wrapper {
  display: flex;
  flex-direction: column;
  flex: 1;
  position: relative;
}

.select-wrapper select {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 14px;
  outline: none;
  appearance: none;
  cursor: pointer;
  padding-bottom: 4px;
}

.date-subtext {
  font-size: 12px;
  color: #ffb74d;
  margin-top: 2px;
}

/* Right icons for day nav */
.date-nav {
  display: flex;
  align-items: center;
  gap: 4px;
  background: #141b24;
  padding: 0 8px;
}

.date-nav button {
  background: none;
  border: none;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  transition: color 0.3s ease;
}

.date-nav button:hover {
  color: #ff9500;
}

/* === Toggle Switch === */
.toggle-box {
    padding: 11px;
    border-radius: 10px;
    border: 1px solid;
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
}

.toggle-box label {
  font-size: 14px;
  color: #ccc;
}

.switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #555;
  transition: 0.3s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #ff9500;
}

input:checked + .slider:before {
  transform: translateX(22px);
}

/* === Mode Buttons === */
.mode-box {
  display: flex;
  align-items: center;
  gap: 12px;
   padding: 11px;
    border-radius: 10px;
    border: 1px solid;
}

.mode-box label {
  color: #ccc;
  font-size: 14px;
}

.btn {
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-orange {
  background: #ff9500;
  color: #fff;
}

.btn-orange:hover {
  background: #ffb74d;
}

.btn-dark {
  background: #222b36;
  color: #fff;
}

.btn-dark:hover {
  background: #2d3845;
}

/* === Responsive === */
@media (max-width: 768px) {
  .timeframe-row {
    flex-direction: column;
    align-items: flex-start;
  }
}


/* Make table horizontally scrollable on mobile */
.table-wrapper {
  overflow-x: auto;   /* horizontal scroll */
  -webkit-overflow-scrolling: touch; /* smooth scrolling on iOS */
}

.table-wrapper table {
  min-width: 600px; /* optional: ensures table has a minimum width */
}

/* Optional: hide scrollbar for clean look */
.table-wrapper::-webkit-scrollbar {
  height: 6px;
}

.table-wrapper::-webkit-scrollbar-thumb {
  background: #ff9500;
  border-radius: 3px;
}


.date-select-container {
  display: flex;
  padding: 11px 10px;
  align-items: center;
  border: 1px solid ;
  border-radius: 11px;
  overflow: hidden;
  transition: border-color 0.3s ease;
  position: relative;
  z-index: 1;
  width: 500px;
}



/* Top bar */
    .top-bar {
      position: relative;
      background:  #161d27;
      border-radius: 22px;
      padding: 40px 17px 10px;
      box-shadow: inset 0 0 0 1px #222;
      margin-bottom: 24px;
    }

    /* Tabs */
    .tabs {
      position: absolute;
      z-index: 10;
      top: -14px;
      display: flex;
      background: #0d1419;
      border-radius: 50px;
      overflow: hidden;
      width: fit-content;
      height: 40px;
    }
    .tab {
      padding: 6px 18px;
      cursor: pointer;
      font-weight: 600;
      background: transparent;
      border: none;
      color: #9ca3af;
      transition: all 0.2s;
      border-radius: 50px;
    }
    .tab.active {
      background: #ff9500;
      color: #fff;
      border-radius: 50px;
    }

    /* Layout */
    .row {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      align-items: center;
    }

    /* Field */
    .field {
      position: relative;
      border: 1px solid #2a3239;
      border-radius: 8px;
      padding: 12px 12px 10px;
      background: #161d27;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .field label {
      position: absolute;
      top: -8px;
      left: 10px;
      background: #13191f;
      color: #9ca3af;
      font-size: 12px;
      padding: 0 4px;
    }

    /* Timeframe icons */
    .time-icons {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .time-icon {
      background: transparent;
      border: none;
      border-radius: 6px;
      color: #0ea5e9;
      width: 32px;
      height: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: 0.2s;
      font-size: 16px;
    }
    .time-icon:hover {
      background: #ff9500;
      color: #fff;
    }

    /* Date */
    .date-field {
      position: relative;
     flex: 1 1 auto; /* take available space */
  min-width: 150px; /* optional, prevents too small on tiny screens */
  display: flex;
  align-items: center;
  border: 1px solid #2a3239;
  border-radius: 8px;
  background: #161d27;
  padding: 8px 12px 6px;
    }
    .date-field label {
      position: absolute;
      top: -8px;
      left: 10px;
      background: #13191f;
      color: #9ca3af;
      font-size: 12px;
      padding: 0 4px;
    }
    .date-select {
      background: transparent;
      border: none;
      color: #fff;
      font-size: 14px;
      outline: none;
      appearance: none;
      cursor: pointer;
      padding-right: 60px;
    }
    .date-icons {
      position: absolute;
      right: 8px;
      display: flex;
      gap: 4px;
    }
    .arrow-btn {
      background: #0d1419;
      border: none;
      border-radius: 50%;
      color: #ff9500;
      width: 26px;
      height: 26px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: 0.2s;
    }
    .arrow-btn:hover {
      background: #ff9500;
      color: #fff;
    }

    .toggle {
  position: relative;
  width: 52px;
  height: 30px;
  border-radius: 34px;
  background-color: #374151;
  cursor: pointer;
  transition: background-color 0.4s, box-shadow 0.4s;
  display: flex;
  align-items: center;
}

.toggle-circle {
  position: absolute;
  top: 4px;
  left: 4px;
  width: 22px;
  height: 22px;
  background-color: #0b111a;
  border: 2px solid #FF9500;
  border-radius: 50%;
  transition: transform 0.4s, background-color 0.4s, border-color 0.4s;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: #3b82f6;
}

/* Active state */
.toggle.active {
  background-color: #FF9500;
  box-shadow: 0 0 7px #FF9500;
}

.toggle.active .toggle-circle {
  transform: translateX(22px);
  background-color: black;
  border-color: white;
  color: white;
  content: "✔"; /* optional: can show checkmark */
}


.toggle-circle::after {
  content: "";
  position: absolute;
  font-size: 14px;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  text-align: center;
}

.toggle.active .toggle-circle::after {
  content: "✔";
}


    /* Mode */
    .mode {
      display: flex;
      background: #0d1419;
      border-radius: 20px;
      overflow: hidden;
    }
    .mode button {
      padding: 6px 12px;
      border: none;
      background: transparent;
      color: #9ca3af;
      cursor: pointer;
      transition: 0.2s;
      border-radius: 50px;
    }
    .mode button.active {
      background: #ff9500;
      color: #fff;
      border-radius: 50px;
    }

    .right-side {
      margin-left: auto;
      display: flex;
      gap: 16px;
      align-items: center;
    }

    /* Member Card Styles */
    .team-container {
      display: none;
      flex-direction: column;
      gap: 16px;
      animation: fadeIn 0.3s ease;
    }

    .team-container.visible {
      display: flex;
    }

    .cart-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #1b232d;
      border-radius: 14px;
      padding: 14px 18px;
      color: #fff;
      min-height: 90px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      transition: all 0.3s ease;
    }

    .cart-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .member-cart {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .cart-left img {
      width: 50px;
      height: 50px;
    }
    .cart-stats {
      display: flex;
      gap: 10px;
      font-size: 13px;
      color: #60a5fa;
      background: #0f172a;
      padding: 4px 8px;
      border-radius: 6px;
      width: fit-content;
    }
    .cart-right {
      display: flex;
      align-items: center;
      gap: 40px;
    }
    .cart-right p {
      font-size: 13px;
      color: #999;
      margin: 0;
      text-align: right;
    }
    .cart-right h4 {
      font-size: 20px;
      margin-top: 10px;
      text-align: center;
      color: #fff;
    }

    /* Stats Table */
    .stats-table {
      width: 100%;
      background: #13191f;
      border-radius: 12px;
      border-collapse: collapse;
      overflow: hidden;
      box-shadow: inset 0 0 0 1px #222;
      display: none;
      animation: fadeIn 0.3s ease;
    }
    .stats-table th, .stats-table td {
      padding: 10px 12px;
      text-align: center;
      font-size: 13px;
      border-bottom: 1px solid #1f2429;
    }
    .stats-table th {
      background: #161d27;
      color: #FF9500;
      font-weight: 600;
    }
    .stats-table tr:last-child td {
      border-bottom: none;
    }
    .stats-table td:first-child, .stats-table th:first-child {
      text-align: left;
    }
    .stats-table tr:hover {
      background: #0f161c;
    }
    .stats-table.visible {
      display: table;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(5px); }
      to { opacity: 1; transform: translateY(0); }
    }

  </style>