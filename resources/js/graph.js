window.initGraph = async function initGraph(activities) {
  const ACTION_MAP = {
    routing: "route",
    approve: "approved",
    signed: "signed",
    confirmed: "confirm",
    disapprove: "disapproved",
  };
  const isSameDay = (a, b) =>
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

  const startOfDay = (d) =>
    new Date(d.getFullYear(), d.getMonth(), d.getDate());
  const week = () => ({
    routing: Array(7).fill(0),
    approved: Array(7).fill(0),
    signed: Array(7).fill(0),
    confirmed: Array(7).fill(0),
    disapprove: Array(7).fill(0),
  });

  const month = (daysInMonth) => ({
    routing: Array(daysInMonth).fill(0),
    approved: Array(daysInMonth).fill(0),
    signed: Array(daysInMonth).fill(0),
    confirmed: Array(daysInMonth).fill(0),
    disapprove: Array(daysInMonth).fill(0),
  });

  const year = () => ({
    routing: Array(12).fill(0),
    approved: Array(12).fill(0),
    signed: Array(12).fill(0),
    confirmed: Array(12).fill(0),
    disapprove: Array(12).fill(0),
  });

  function buildWeeklyData(logs) {
    const today = startOfDay(new Date());

    const days = [...Array(7)].map((_, i) => {
      const d = new Date(today);
      d.setDate(today.getDate() - (6 - i));
      return d;
    });

    const labels = days.map((d) =>
      d.toLocaleDateString("en-US", {
        weekday: "short",
      }),
    );

    const counts = week();

    logs.forEach((log) => {
      const logDate = startOfDay(new Date(log.created_at));

      days.forEach((day, index) => {
        if (isSameDay(logDate, day)) {
          if (log.action === ACTION_MAP.routing) counts.routing[index]++;
          if (log.action === ACTION_MAP.approve) counts.approved[index]++;
          if (log.action === ACTION_MAP.signed) counts.signed[index]++;
          if (log.action === ACTION_MAP.confirmed) counts.confirmed[index]++;
          if (log.action === ACTION_MAP.disapprove) counts.disapprove[index]++;
        }
      });
    });

    return {
      labels,
      ...counts,
    };
  }

  function buildMonthlyData(logs) {
    const now = new Date();
    const yearNow = now.getFullYear();
    const monthNow = now.getMonth();

    const daysInMonth = new Date(yearNow, monthNow + 1, 0).getDate();
    const labels = Array.from(
      {
        length: daysInMonth,
      },
      (_, i) => `Day ${i + 1}`,
    );

    const counts = month(daysInMonth);

    logs.forEach((log) => {
      const d = new Date(log.created_at);

      if (d.getFullYear() === yearNow && d.getMonth() === monthNow) {
        const index = d.getDate() - 1;

        if (log.action === ACTION_MAP.routing) counts.routing[index]++;
        if (log.action === ACTION_MAP.approve) counts.approved[index]++;
        if (log.action === ACTION_MAP.signed) counts.signed[index]++;
        if (log.action === ACTION_MAP.confirmed) counts.confirmed[index]++;
        if (log.action === ACTION_MAP.disapprove) counts.disapprove[index]++;
      }
    });

    return {
      labels,
      ...counts,
    };
  }

  function buildYearlyData(logs) {
    const now = new Date();
    const yearNow = now.getFullYear();
    const currentMonth = now.getMonth();

    const labels = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ];

    // fresh yearly counts (no reuse, no mutation risk)
    const counts = {
      routing: Array(12).fill(0),
      approved: Array(12).fill(0),
      signed: Array(12).fill(0),
      confirmed: Array(12).fill(0),
      disapprove: Array(12).fill(0),
    };

    // daily buckets per month
    const daily = {};

    logs.forEach((log) => {
      const d = new Date(log.created_at);
      if (d.getFullYear() !== yearNow || d.getMonth() > currentMonth) return;

      const m = d.getMonth();
      const day = d.getDate();
      const key = `${m}-${day}`;

      if (!daily[key]) {
        daily[key] = {
          routing: 0,
          approved: 0,
          signed: 0,
          confirmed: 0,
          disapprove: 0,
        };
      }

      if (log.action === ACTION_MAP.routing) daily[key].routing++;
      if (log.action === ACTION_MAP.approve) daily[key].approved++;
      if (log.action === ACTION_MAP.signed) daily[key].signed++;
      if (log.action === ACTION_MAP.confirmed) daily[key].confirmed++;
      if (log.action === ACTION_MAP.disapprove) daily[key].disapprove++;
    });

    // reduce daily → yearly (MAX per month)
    Object.keys(daily).forEach((key) => {
      const [monthIndex] = key.split("-").map(Number);

      counts.routing[monthIndex] = Math.max(
        counts.routing[monthIndex],
        daily[key].routing,
      );
      counts.approved[monthIndex] = Math.max(
        counts.approved[monthIndex],
        daily[key].approved,
      );
      counts.signed[monthIndex] = Math.max(
        counts.signed[monthIndex],
        daily[key].signed,
      );
      counts.confirmed[monthIndex] = Math.max(
        counts.confirmed[monthIndex],
        daily[key].confirmed,
      );
      counts.disapprove[monthIndex] = Math.max(
        counts.disapprove[monthIndex],
        daily[key].disapprove,
      );
    });

    return {
      labels,
      ...counts,
    };
  }

  window.renderFileActivityGraph = async function (range = "week") {
    try {
      const sampleData = {
        week: buildWeeklyData(activities),
        month: buildMonthlyData(activities),
        year: buildYearlyData(activities),
      };
      // console.log(sampleData);

      const ctx = document.getElementById("fileGraph").getContext("2d");

      // Create gradients for each line
      const routingGradient = ctx.createLinearGradient(
        0,
        0,
        0,
        ctx.canvas.height,
      );
      routingGradient.addColorStop(0, "rgba(255, 165, 0, 0.5)");
      routingGradient.addColorStop(1, "rgba(255, 165, 0, 0)");

      const approveGradient = ctx.createLinearGradient(
        0,
        0,
        0,
        ctx.canvas.height,
      );
      approveGradient.addColorStop(0, "rgba(0, 128, 0, 0.5)");
      approveGradient.addColorStop(1, "rgba(0, 128, 0, 0)");

      const signedGradient = ctx.createLinearGradient(
        0,
        0,
        0,
        ctx.canvas.height,
      );
      signedGradient.addColorStop(0, "rgba(0, 0, 255, 0.5)");
      signedGradient.addColorStop(1, "rgba(0, 0, 255, 0)");

      const confirmedGradient = ctx.createLinearGradient(
        0,
        0,
        0,
        ctx.canvas.height,
      );
      confirmedGradient.addColorStop(0, "rgba(128, 0, 128, 0.5)");
      confirmedGradient.addColorStop(1, "rgba(128, 0, 128, 0)");

      const disapproveGradient = ctx.createLinearGradient(
        0,
        0,
        0,
        ctx.canvas.height,
      );
      disapproveGradient.addColorStop(0, "rgba(255, 0, 0, 0.5)");
      disapproveGradient.addColorStop(1, "rgba(255, 0, 0, 0)");

      // Destroy previous chart if exists
      if (window.fileActivityChart) {
        window.fileActivityChart.destroy();
      }

      window.fileActivityChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: sampleData[range].labels,
          datasets: [
            {
              label: "Routing",
              data: sampleData[range].routing,
              borderColor: "orange",
              backgroundColor: routingGradient,
              fill: true,
              tension: 0.2,
            },
            {
              label: "Approved",
              data: sampleData[range].approved,
              borderColor: "green",
              backgroundColor: approveGradient,
              fill: true,
              tension: 0.2,
            },
            {
              label: "Signed",
              data: sampleData[range].signed,
              borderColor: "green",
              backgroundColor: signedGradient,
              fill: true,
              tension: 0.2,
            },
            {
              label: "Confirmed",
              data: sampleData[range].confirmed,
              borderColor: "blue",
              backgroundColor: confirmedGradient,
              fill: true,
              tension: 0.2,
            },
            {
              label: "Disapproved",
              data: sampleData[range].disapprove,
              borderColor: "red",
              backgroundColor: disapproveGradient,
              fill: true,
              tension: 0.2,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: "top",
            },
            tooltip: {
              mode: "index",
              intersect: false,
            },
          },
          interaction: {
            mode: "nearest",
            intersect: false,
          },
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: "Time",
              },
            },
            y: {
              display: true,
              title: {
                display: true,
                text: "Count",
              },
              beginAtZero: true,
            },
          },
        },
      });
      // console.log(data);
    } catch (error) {
      console.error(error);
    }
  };

  window.renderFileActivityGraph("week"); // default week

  // Change graph on range select
  document
    .getElementById("graph-range")
    .addEventListener("change", function () {
      window.renderFileActivityGraph(this.value);
    });
};
