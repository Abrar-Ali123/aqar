function getChartColorsArray(e) {
    var t = document.getElementById(e);
    if (t) {
        t = t.dataset.colors;
        if (t) return JSON.parse(t).map(e => {
            var t = e.replace(/\s/g, "");
            return t.includes(",") ? 2 === (e = e.split(",")).length ? `rgba(${getComputedStyle(document.documentElement).getPropertyValue(e[0])}, ${e[1]})` : t : getComputedStyle(document.documentElement).getPropertyValue(t) || t
        });
        console.warn("data-colors attribute not found on: " + e)
    }
}
var sessionChart = "",
    visitDurationChart = "",
    impressionsChart = "",
    viewsChart = "",
    monthlyProfitChart = "",
    chartColumnStacked100Chart = "",
    customerImpressionChart = "";

function loadCharts() {
    getChartColorsArray("session_chart") && (r = {
        series: [{
            name: "Total Sessions",
            data: [31, 40, 28, 51, 42, 109, 103]
        }],
        chart: {
            height: 124,
            type: "line",
            toolbar: {
                show: !1
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        grid: {
            show: !1,
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        colors: getChartColorsArray("session_chart"),
        xaxis: {
            categories: ["S", "M", "T", "W", "T", "F", "S"],
            labels: {
                style: {
                    fontSize: "10px"
                }
            }
        },
        yaxis: {
            show: !1
        }
    }, "" != sessionChart && sessionChart.destroy(), (sessionChart = new ApexCharts(document.querySelector("#session_chart"), r)).render()), (t = getChartColorsArray("visti_duration_chart")) && (r = {
        series: [{
            name: "Avg. Visit Duration",
            data: [29, 43, 71, 58, 99, 93, 130]
        }],
        chart: {
            height: 124,
            type: "line",
            toolbar: {
                show: !1
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        grid: {
            show: !1,
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        colors: t,
        xaxis: {
            categories: ["S", "M", "T", "W", "T", "F", "S"],
            labels: {
                style: {
                    fontSize: "10px"
                }
            }
        },
        yaxis: {
            show: !1
        }
    }, "" != visitDurationChart && visitDurationChart.destroy(), (visitDurationChart = new ApexCharts(document.querySelector("#visti_duration_chart"), r)).render()), (t = getChartColorsArray("impressions_chart")) && (r = {
        series: [{
            name: "Impressions",
            data: [50, 18, 47, 32, 84, 110, 93]
        }],
        chart: {
            height: 124,
            type: "line",
            toolbar: {
                show: !1
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        grid: {
            show: !1,
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        colors: t,
        xaxis: {
            categories: ["S", "M", "T", "W", "T", "F", "S"],
            labels: {
                style: {
                    fontSize: "10px"
                }
            }
        },
        yaxis: {
            show: !1
        }
    }, "" != impressionsChart && impressionsChart.destroy(), (impressionsChart = new ApexCharts(document.querySelector("#impressions_chart"), r)).render());

    function e(e, t, r) {
        for (var o = 0, a = []; o < t;) {
            var s = Math.floor(100 * Math.random()) + 1,
                i = Math.floor(Math.random() * (r.max - r.min + 1)) + r.min,
                n = Math.floor(61 * Math.random()) + 15;
            a.push([s, i, n]), o++
        }
        return a
    }(t = getChartColorsArray("views_chart")) && (r = {
        series: [{
            name: "Total Views",
            data: [72, 58, 30, 51, 42, 95, 119]
        }],
        chart: {
            height: 124,
            type: "line",
            toolbar: {
                show: !1
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        grid: {
            show: !1,
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        stroke: {
            width: 2,
            curve: "smooth"
        },
        colors: t,
        xaxis: {
            categories: ["S", "M", "T", "W", "T", "F", "S"],
            labels: {
                style: {
                    fontSize: "10px"
                }
            }
        },
        yaxis: {
            show: !1
        }
    }, "" != viewsChart && viewsChart.destroy(), (viewsChart = new ApexCharts(document.querySelector("#views_chart"), r)).render());
    var t, r;
    (t = getChartColorsArray("monthly_profit")) && (r = {
        series: [{
            name: "Product1",
            data: e(new Date("11 Feb 2017 GMT").getTime(), 8, {
                min: 1,
                max: 15
            })
        }, {
            name: "Product2",
            data: e(new Date("11 Feb 2017 GMT").getTime(), 8, {
                min: 1,
                max: 15
            })
        }],
        chart: {
            height: 248,
            type: "bubble",
            toolbar: {
                show: !1
            }
        },
        dataLabels: {
            enabled: !1
        },
        legend: {
            show: !1
        },
        grid: {
            padding: {
                top: 0,
                right: 0,
                bottom: 0
            }
        },
        xaxis: {
            show: !1,
            tickAmount: 6,
            type: "datetime",
            labels: {
                rotate: 0
            }
        },
        yaxis: {
            max: 15
        },
        theme: {
            palette: "palette2"
        },
        colors: t
    }, "" != monthlyProfitChart && monthlyProfitChart.destroy(), (monthlyProfitChart = new ApexCharts(document.querySelector("#monthly_profit"), r)).render()), (t = getChartColorsArray("column_stacked_chart")) && (r = {
        series: [{
            name: "Views",
            data: [44, 55, 41, 67, 22, 43, 21, 49]
        }, {
            name: "Orders",
            data: [13, 23, 20, 50, 13, 27, 33, 27]
        }],
        chart: {
            type: "bar",
            height: 252,
            stacked: !0,
            stackType: "100%",
            toolbar: {
                show: !1
            }
        },
        xaxis: {
            categories: ["Jan", "Feb", "March", "April", "May", "June", "July", "Aug"],
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            }
        },
        yaxis: {
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                show: !1
            }
        },
        grid: {
            show: !1,
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: -25,
                left: -15,
                right: 0,
                bottom: 0
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            show: !1
        },
        colors: t
    }, "" != chartColumnStacked100Chart && chartColumnStacked100Chart.destroy(), (chartColumnStacked100Chart = new ApexCharts(document.querySelector("#column_stacked_chart"), r)).render());
    (t = getChartColorsArray("customer_impression_charts")) && (r = {
        series: [{
            name: "Orders",
            data: [34, 65, 46, 68, 49, 61, 42, 44, 78, 52, 63, 67]
        }, {
            name: "Earnings",
            data: [89.25, 98.58, 68.74, 108.87, 77.54, 84.03, 51.24, 28.57, 92.57, 42.36, 88.51, 36.57]
        }, {
            name: "Refunds",
            data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35]
        }],
        chart: {
            height: 322,
            type: "line",
            toolbar: {
                show: !1
            }
        },
        stroke: {
            curve: "smooth",
            dashArray: [0, 0, 8],
            width: [1, 1, 2]
        },
        fill: {
            opacity: [1, 1, 1]
        },
        markers: {
            size: [0, 0, 0],
            strokeWidth: 3,
            hover: {
                size: 4
            }
        },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            axisTicks: {
                show: !1
            },
            axisBorder: {
                show: !1
            }
        },
        grid: {
            show: !0,
            padding: {
                right: -2,
                bottom: -10,
                left: 10
            }
        },
        legend: {
            show: !0,
            horizontalAlign: "right",
            position: "top",
            offsetX: 0,
            offsetY: 5,
            markers: {
                width: 9,
                height: 9,
                radius: 6
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        },
        plotOptions: {
            bar: {
                columnWidth: "20%",
                barHeight: "100%",
                borderRadius: [8]
            }
        },
        colors: t,
        tooltip: {
            shared: !0,
            y: [{
                formatter: function(e) {
                    return void 0 !== e ? e.toFixed(0) : e
                }
            }, {
                formatter: function(e) {
                    return void 0 !== e ? "$" + e.toFixed(2) + "k" : e
                }
            }, {
                formatter: function(e) {
                    return void 0 !== e ? e.toFixed(0) + " Sales" : e
                }
            }]
        }
    }, "" != customerImpressionChart && customerImpressionChart.destroy(), (customerImpressionChart = new ApexCharts(document.querySelector("#customer_impression_charts"), r)).render())
}
window.addEventListener("resize", function() {
    setTimeout(() => {
        loadCharts()
    }, 250)
}), loadCharts();
var options = {
        valueNames: ["browsers", "click", "pageviews"]
    },
    contactList = new List("networks", options),
    options = {
        valueNames: ["activePage", "activePageNo", "pageUsers"]
    },
    topPages = new List("top-Pages", options),
    sorttableDropdown = document.querySelectorAll(".sortble-dropdown"),
    swiper = (sorttableDropdown && sorttableDropdown.forEach(function(r) {
        r.querySelectorAll(".dropdown-menu .dropdown-item").forEach(function(t) {
            t.addEventListener("click", function() {
                var e = t.innerHTML;
                r.querySelector(".dropdown-title").innerHTML = e
            })
        })
    }), new Swiper(".mySwiper", {
        spaceBetween: 22,
        loop: !0,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: !0
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        breakpoints: {
            1200: {
                slidesPerView: 2
            },
            576: {
                slidesPerView: 2
            }
        }
    }));