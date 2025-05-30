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
var chartStorkeRadialbarImagesChart = "";

function loadCharts() {
    getChartColorsArray("storage_chart") && ("" != chartStorkeRadialbarImagesChart && chartStorkeRadialbarImagesChart.destroy(), (chartStorkeRadialbarImagesChart = new ApexCharts(document.querySelector("#storage_chart"), {
        series: [67],
        chart: {
            height: 315,
            type: "radialBar"
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 15,
                    size: "65%",
                    image: "assets/images/comingsoon.png",
                    imageWidth: 56,
                    imageHeight: 56,
                    imageClipped: !1
                },
                dataLabels: {
                    name: {
                        show: !1,
                        color: "#fff"
                    },
                    value: {
                        show: !0,
                        color: "#333",
                        offsetY: 65,
                        fontSize: "22px"
                    }
                }
            }
        },
        fill: {
            type: "image",
            image: {
                src: ["assets/images/small/img-8.jpg"]
            }
        },
        stroke: {
            lineCap: "round"
        },
        labels: ["Volatility"]
    })).render())
}
window.addEventListener("resize", function() {
    setTimeout(() => {
        loadCharts()
    }, 250)
}), loadCharts();
var previewTemplate, dropzone, dropzonePreviewNode = document.querySelector("#dropzone-preview-list"),
    options = (dropzonePreviewNode.id = "", dropzonePreviewNode && (previewTemplate = dropzonePreviewNode.parentNode.innerHTML, dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode), dropzone = new Dropzone(".file-dropzone", {
        url: "https://httpbin.org/post",
        method: "post",
        previewTemplate: previewTemplate,
        previewsContainer: "#dropzone-preview"
    })), {
        valueNames: ["docs_type", "document_name", "size", "file_item", "date"]
    }),
    contactList = new List("contactList", options).on("updated", function(e) {
        0 == e.matchingItems.length ? document.getElementsByClassName("noresult")[0].style.display = "block" : document.getElementsByClassName("noresult")[0].style.display = "none", 0 < e.matchingItems.length ? document.getElementsByClassName("noresult")[0].style.display = "none" : document.getElementsByClassName("noresult")[0].style.display = "block"
    }),
    sorttableDropdown = document.querySelectorAll(".sortble-dropdown"),
    bodyElement = (sorttableDropdown && sorttableDropdown.forEach(function(o) {
        o.querySelectorAll(".dropdown-menu .dropdown-item").forEach(function(t) {
            t.addEventListener("click", function() {
                var e = t.innerHTML;
                o.querySelector(".dropdown-title").innerHTML = e
            })
        })
    }), document.getElementsByTagName("body")[0]),
    isShowMenu = (Array.from(document.querySelectorAll("#file-list tr")).forEach(function(e) {
        e.querySelector(".view-item-btn").addEventListener("click", function() {
            bodyElement.classList.add("file-detail-show")
        })
    }), Array.from(document.querySelectorAll(".close-btn-overview")).forEach(function(e) {
        e.addEventListener("click", function() {
            bodyElement.classList.remove("file-detail-show")
        })
    }), !1),
    emailMenuSidebar = document.getElementsByClassName("file-manager-wrapper");

function windowResize() {
    document.documentElement.clientWidth < 1400 ? document.body.classList.remove("file-detail-show") : document.body.classList.add("file-detail-show")
}
Array.from(document.querySelectorAll(".file-menu-btn")).forEach(function(e) {
    e.addEventListener("click", function() {
        Array.from(emailMenuSidebar).forEach(function(e) {
            e.classList.add("menubar-show"), isShowMenu = !0
        })
    })
}), window.addEventListener("click", function(e) {
    document.querySelector(".file-manager-wrapper").classList.contains("menubar-show") && (isShowMenu || document.querySelector(".file-manager-wrapper").classList.remove("menubar-show"), isShowMenu = !1)
});