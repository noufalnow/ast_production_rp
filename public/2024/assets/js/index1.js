$(function (e) {
  //______Data-Table
  /*$("#data-table").DataTable({
    language: {
      searchPlaceholder: "Search...",
      sSearch: "",
    },
  });

  //______Select2
  $(".select2").select2({
    minimumResultsForSearch: Infinity,
  });*/
});



/*



window.Apex = {
  chart: {
    // foreColor: "#fff",
    toolbar: {
      show: true,
    },
  },
  // colors: ["#FCCF31", "#17ead9", "#f02fc2"],
  stroke: {
    width: 3,
  },
  dataLabels: {
    enabled: false,
  },
  // grid: {
  //   borderColor: "#40475D"
  // },
  // xaxis: {
  //   axisTicks: {
  //     color: "#333"
  //   },
  //   axisBorder: {
  //     color: "#333"
  //   }
  // },
  // fill: {
  //   type: "gradient",
  //   gradient: {
  //     gradientToColors: ["#F55555", "#6078ea", "#6094ea"]
  //   }
  // },
  tooltip: {
    theme: "light",
    x: {
      formatter: function (val) {
        return moment(new Date(val)).format("HH:mm:ss");
      },
    },
  },
  yaxis: {
    decimalsInFloat: 2,
    opposite: true,
    labels: {
      offsetX: -10,
    },
  },
};

var trigoStrength = 3;
var iteration = 11;

function getRandom() {
  var i = iteration;
  return (
    (Math.sin(i / trigoStrength) * (i / trigoStrength) +
      i / trigoStrength +
      1) *
    (trigoStrength * 2)
  );
}

function getRangeRandom(yrange) {
  return Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
}

function generateMinuteWiseTimeSeries(baseval, count, yrange) {
  var i = 0;
  var series = [];
  while (i < count) {
    var x = baseval;
    var y =
      (Math.sin(i / trigoStrength) * (i / trigoStrength) +
        i / trigoStrength +
        1) *
      (trigoStrength * 2);

    series.push([x, y]);
    baseval += 300000;
    i++;
  }
  return series;
}

function getNewData(baseval, yrange) {
  var newTime = baseval + 300000;
  return {
    x: newTime,
    y: Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min,
  };
}
var totalRevenue = paidAmount.map(function (value, index) {
  return parseFloat(value) + parseFloat(billCollection[index]); // Ensure numbers are added
});


var optionsLineDifference = {
  chart: {
    height: 350,
    type: "line",
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: "smooth",
    width: 2,
  },
  series: [
    {
      name: "Revenue",
      data: totalRevenue,
    },
    {
      name: "Expenditure",
      data: totalExpenditure,
    },
  ],
  xaxis: {
    categories: labels,
    type: "category",
  },
  title: {
    text: "Revenue and Expenditure Comparison with Difference",
    align: "left",
  },
  colors: ["#00E396", "#FF4560"],
  legend: {
    show: true,
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: ["#F44336", "#FFEB3B"], // Gradient for the shaded area
      inverseColors: false,
    },
  },
};

if (document.querySelector("#linechart")) {
  var chartLineDiff = new ApexCharts(
    document.querySelector("#linechart"),
    optionsLineDifference
  );
  chartLineDiff.render();
}



// Update the data periodically every 3 seconds (or your desired interval)
window.setInterval(function () {
  iteration++;
}, 3000); */
