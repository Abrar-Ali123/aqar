function getChartColorsArray(a) {
    var t = document.getElementById(a);
    if (t) {
        t = t.dataset.colors;
        if (t) return JSON.parse(t).map(a => {
            var t = a.replace(/\s/g, "");
            return t.includes(",") ? 2 === (a = a.split(",")).length ? `rgba(${getComputedStyle(document.documentElement).getPropertyValue(a[0])}, ${a[1]})` : t : getComputedStyle(document.documentElement).getPropertyValue(t) || t
        });
        console.warn("data-colors attribute not found on: " + a)
    }
}
var chartColumnStacked100Chart = "",
    chartHeatMapShadesChart = "",
    usersActivityChart = "";

function loadCharts() {
    function a(a, t) {
        for (var e = 0, r = []; e < a;) {
            var n = (e + 1).toString(),
                o = Math.floor(Math.random() * (t.max - t.min + 1)) + t.min;
            r.push({
                x: n,
                y: o
            }), e++
        }
        return r
    }(e = getChartColorsArray("column_stacked_chart")) && (t = {
        series: [{
            name: "Views",
            data: [44, 55, 41, 67, 22, 43, 21, 49]
        }, {
            name: "Orders",
            data: [13, 23, 20, 50, 13, 27, 33, 27]
        }],
        chart: {
            type: "bar",
            height: 300,
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
        colors: e
    }, "" != chartColumnStacked100Chart && chartColumnStacked100Chart.destroy(), (chartColumnStacked100Chart = new ApexCharts(document.querySelector("#column_stacked_chart"), t)).render());
    var t, e;
    [{
        name: "W1",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W2",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W3",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W4",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W5",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W6",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W7",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W8",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W9",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W10",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W11",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W12",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W13",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W14",
        data: a(7, {
            min: 0,
            max: 90
        })
    }, {
        name: "W15",
        data: a(7, {
            min: 0,
            max: 90
        })
    }].reverse(), (e = getChartColorsArray("shades_heatmap")) && (t = {
        series: [{
            name: "7 AM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "8 AM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "9 AM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "10 AM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "11 AM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "12 PM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "1 PM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "2 PM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }, {
            name: "3 PM",
            data: a(7, {
                min: 0,
                max: 90
            })
        }],
        chart: {
            height: 300,
            type: "heatmap",
            toolbar: {
                show: !1
            }
        },
        stroke: {
            width: 0
        },
        plotOptions: {
            heatmap: {
                radius: 2,
                enableShades: !1,
                colorScale: {
                    ranges: [{
                        from: 0,
                        to: 50,
                        color: e[0]
                    }, {
                        from: 51,
                        to: 100,
                        color: e[1]
                    }]
                }
            }
        },
        grid: {
            show: !0,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: -18,
                right: 0,
                bottom: 0
            }
        },
        stroke: {
            width: 3
        },
        dataLabels: {
            enabled: !1
        },
        xaxis: {
            categories: ["S", "M", "T", "W", "T", "F", "S"],
            type: "category"
        }
    }, "" != chartHeatMapShadesChart && chartHeatMapShadesChart.destroy(), (chartHeatMapShadesChart = new ApexCharts(document.querySelector("#shades_heatmap"), t)).render());
    (e = getChartColorsArray("usersActivity")) && (t = {
        series: [{
            name: "Created",
            data: [20, 17, 11, 15, 20, 15, 20]
        }, {
            name: "Converted",
            data: [13, 23, 18, 8, 27, 10, 12]
        }],
        chart: {
            type: "bar",
            height: 300,
            stacked: !0,
            toolbar: {
                show: !1
            },
            zoom: {
                enabled: !0
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "35%"
            }
        },
        dataLabels: {
            enabled: !1
        },
        xaxis: {
            categories: ["Sun", "Mon", "Tue", "Wen", "Thu", "Fri", "Sat"]
        },
        grid: {
            show: !0,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: -18,
                right: 0,
                bottom: 0
            }
        },
        legend: {
            position: "bottom"
        },
        fill: {
            opacity: 1
        },
        colors: e
    }, "" != usersActivityChart && usersActivityChart.destroy(), (usersActivityChart = new ApexCharts(document.querySelector("#usersActivity"), t)).render())
}
window.addEventListener("resize", function() {
    setTimeout(() => {
        loadCharts()
    }, 250)
}), loadCharts();