function updateChart() {
    var chart = new ApexCharts(profileReportChartEl, profileReportChartConfig);
    chart.updateSeries([{
        data: [0, 0, 0, 0, 0, 0, 0]
    }])
};

$(document).ready(function () {
    $('#summernote').summernote();
    $('#summernote2').summernote();
    $('#summernote3').summernote();
    $('#summernote4').summernote();
    $('#summernote5').summernote();
    $('#gallery-textarea').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']]
        ],
        callbacks: {
            onKeydown: function (e) {
                var i = e.currentTarget.innerText;
                if (i.trim().length >= 230) {
                    if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey))
                        e.preventDefault();
                }
            },
            onKeyup: function (e) {
                var t = e.currentTarget.innerText;
                $('#gallery-textarea').text(400 - t.trim().length);
            },
        }
    });

    $('#select2').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
    });
});

function limit230(element) {
    var max_char = 230;

    if (element.value.length > max_char) {
        element.value = element.value.substr(0, max_char);
    }
}

function deleteModalData() {
    document.getElementById('kategori_input').value = null;
}

function saveDataModal() {
    var a = document.getElementById('kategori_input').value;

    document.getElementById('kategori_new').hidden = false;
    document.getElementById('kategori_new').value = a;

    document.getElementById('kategori').hidden = true;
}

// Fitur cuaca dinonaktifkan (OpenWeatherMap & alert popup telah dinonaktifkan)
function showWeather(data) {}
function weatherCheck(position) {}
function getLocation() {}