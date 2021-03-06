$(function () {
    // Hide div containing the select and the submit button
    $('.select-period').hide()
    $('.submit-data-form').hide()
    $('.tickets-counter-container').hide()

    $('.datepickette').datepicker({
        regional: "fr",
        altField: "#alternate",
        altFormat: "yy-mm-dd",
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        minDate: 0,
        beforeShowDay: disabledDates,
        onSelect: function () {
            disableFullDay(this.value)
            onDateSelect()
        }
    }).attr("readonly", "readonly");

    $.datepicker.regional['fr'] = {
        closeText: 'Fermer',
        prevText: '&#x3c;Préc',
        nextText: 'Suiv&#x3e;',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier','Fevrier','Mars','Avril','Mai','Juin',
            'Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun',
            'Jul','Aou','Sep','Oct','Nov','Dec'],
        dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
        dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        minDate: 0,
        maxDate: '+12M +0D',
        numberOfMonths: 1,
        showButtonPanel: true
    };
    $.datepicker.setDefaults($.datepicker.regional['fr']);
})

let disableFullDay = function (value) {
    let date = new Date(),
        day = '' + date.getDate(),
        month = '' + (date.getMonth() + 1),
        year = date.getFullYear(),
        maxHour = 13,
        actualHour = date.getHours()

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    let fullDate = [day, month, year].join('-')
    console.log(value, fullDate)

    if (actualHour > maxHour && value === fullDate) {
        $('.select-period-full').remove()
        console.log(true)
    }
    else {
        $('.select-period-full').remove()
        $('#appbundle_command_visitPeriod').prepend("<option class='select-period-full' value='day' selected>Journée</option>")
        console.log(false)
    }
}

let disabledDates = function (date) {
    let daysoff = ["1-1", "4-17", "5-1", "5-8", "5-25", "6-5", "7-14", "8-15", "11-1", "11-11", "12-25"]
    let day = date.getDate()
    let today = date.getDay()
    let month = date.getMonth() + 1

    for (let i = 0; i < daysoff.length; i++) {
        if ($.inArray(month + "-" + day, daysoff) !== -1 || (new Date() + 1) > date || today === 2 || today === 0) {
            return [false]
        }
    }
    return [true]

}

let onDateSelect = function () {
    let value = $('#alternate').val()
    let d = new Date(value),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    // inversion de "day" et "month" pour l'affichage
    let date = [year, month, day].join('-')
    countTickets(date)

    $('.select-period').show()
    $('.submit-data-form').show()
}

let countTickets = function (date) {
    $.ajax({
        url: '/api/tickets/count/' + date,
        type: "GET",
        data: {
            format: "jsonp"
        },
        success: function (data) {
            let container = $('.tickets-counter-container')
            container.show()
            let title = $('#tickets-counter')
            title.text("Il reste " + (1000 - data) + " billets disponibles pour cette date.")

            if ((1000 - data) === 0) {
                $('.submit-data-form').attr("disabled", "disable")
            }
            else {
                $('.submit-data-form').removeAttr("disabled")
            }
        }

    })
}