var currentRequest;
var currentPostcode;
var currentCountry;
var minimumOrder;
var mixpanelData = [];
var userInfo;
var newURL = window.location.pathname;
var webFunnelUrl = "";
window.isMobileDevice = false;
window.showTimeslot = 'collection';

$(function () {

    newURL = window.location.pathname;

    $.support.transition = false;//no transitions, easy

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': CSRF_TOKEN
        }
    });

    if(!($('#user-info').html().trim().length === 0)) {
        userInfo = JSON.parse($('#user-info').html());
    }

    webFunnelUrl = window.location.origin;

    orders.init();

    if( $('#meta-is-mobile').length > 0 ) {
        window.isMobileDevice = true;
    }

    /** ------------------ Order Trackers ------------------ **/

    newURL = window.location.pathname;

    if($('#user-info').html().trim() != 0 && IS_LAUNDRAPP) {

        mixpanel.register({
            "postcode": JSON.parse($('#user-info').html())['address_postcode']
        });

    }

    if(newURL === '/items') {

        if($('#user-info').html().trim() != 0 && IS_LAUNDRAPP) {

            mixpanel.track("Product List Open", {
                postcode: JSON.parse($('#user-info').html())['address_postcode']
            });

        }

        $('#only-show-on-items').css("display", "block");

        if(JSON.parse($('#user-info').html())['category'].length) {
            $('.data-category-' + JSON.parse($('#user-info').html())['category']).click();
        }

    }

    if(newURL.indexOf('/booking') !== -1) {

        if(IS_LAUNDRAPP) {
            mixpanel.track("Basket Open");
        }

        if(JSON.parse($('#user-info').html())['instant_order'] === true) {
            orders.get_timeslots();
        }

        if(JSON.parse($('#user-info').html())['selected_merchant_available'] === false) {
            hideTimeslotsNoSearch();
            showNoTimeslotsAvailableForPostcode();
            $('#booking_validate_submit').addClass('disabled');
        }

        orders.selected_merchant_available = JSON.parse($('#user-info').html())['selected_merchant_available'];

        $('#FAQ-link').on("click", function(e) {
            e.preventDefault();

            if (IS_LAUNDRAPP) {
                mixpanel.track('FAQs Open', {
                    source: window.location.pathname
                });
            }

            window.open('https://laundrapp.zendesk.com/hc/en-us');
        });

        $('#only-show-on-items').css('display', 'none');

    }

    if(newURL === '/contact' && $('#business-account-page').length === 0) {
        document.getElementById('terms').checked = false;
        document.getElementById('postContact').disabled = false;
    }

    if(document.referrer) {
        var oldURL = document.referrer;

        if(oldURL.substring(-7) === 'contact' && newURL.indexOf('/booking') !== -1) {

            if(IS_LAUNDRAPP) {
                mixpanel.track("Complete Booking Pressed", {
                    'Had error': true,
                    'Error': $('#error-text').text()
                });
            }

        }

        if(oldURL.substring(-7) === 'contact' && newURL === '/done') {

            if(IS_LAUNDRAPP) {
                mixpanel.track("Complete Booking Pressed", {
                    'Had error': false
                });
            }
        }

    }

    if(newURL === '/done') {

        transactionTracker();

        $('.android-button').removeClass('hidden');
        $('.ios-button').removeClass('hidden');

        $('.sub_title-3').css("background", "white");
    }

});

/** ------------- Basket Tracker -------------- **/
var criteoBasketTracker = function () {

    if(newURL.indexOf('/booking') !== -1 && $('#booking-cart-summary').children('.cart-item').length > 0 && IS_LAUNDRAPP) {

        if(!$('body').hasClass('criteoBasketTrackerSent')) {
            var criteo_items = [];
            var userInfo = JSON.parse($('#user-info').html());
            var cartItems = $('#booking-cart-summary').children('.cart-item');

            for(var i = 0; i < cartItems.length; i++) {
                criteo_items.push({
                    id: String($(cartItems[i]).data('id')),
                    price: parseFloat($(cartItems[i]).data('price')),
                    quantity: parseFloat($(cartItems[i]).data('quantity'))
                });
            }

            if( $('#meta-is-mobile').length > 0 ) {
                if( $('#meta-is-tablet').length > 0 ) {
                    window.criteo_q = window.criteo_q || [];
                    window.criteo_q.push(
                        {event: "setAccount", account: 30270},
                        {event: "setHashedEmail", email: userInfo['hashed_email']},
                        {event: "setSiteType", type: "t"},
                        {event: "viewBasket", item: criteo_items}
                    );
                } else {
                    window.criteo_q = window.criteo_q || [];
                    window.criteo_q.push(
                        {event: "setAccount", account: 30270},
                        {event: "setHashedEmail", email: userInfo['hashed_email']},
                        {event: "setSiteType", type: "m"},
                        {event: "viewBasket", item: criteo_items}
                    );
                }
            } else {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push(
                    { event: "setAccount", account: 30270 },
                    { event: "setHashedEmail", email: userInfo['hashed_email'] },
                    { event: "setSiteType", type: "d" },
                    { event: "viewBasket", item: criteo_items}
                );
            }
            $('body').addClass('criteoBasketTrackerSent');
        }
    }
};

/** -------------- Transaction Tracker -------------- **/
var transactionTracker = function() {

    if( ($('#tracking-data').html().replace(/\s/g,'').length > 0) && IS_LAUNDRAPP ) {

        var trackingData = JSON.parse($('#tracking-data').html());

        if (IS_LAUNDRAPP) {
            mixpanel.track("Order Placed", {
                'Order Value' : trackingData.order_value,
                'Number of Items' : trackingData.num_items,
                'Voucher' : trackingData.voucher,
                'Postcode' : trackingData.postcode,
                'Payment Method' : trackingData.payment_method
            });
        }

        var newCustomer;

        if(trackingData.isFirstOrder === false) {
            newCustomer = 0;
        } else {
            newCustomer = 1;
        }

        var criteo_items = [];
        var items = JSON.parse($('#tracking-data').html()).items;

        Object.keys(items).forEach(function(key) {
            criteo_items.push({ id:  String(items[key].id), price : items[key].price, quantity: items[key].qty });
        });

        if($('#meta-is-mobile').length > 0) {
            if ($('#meta-is-tablet').length > 0) {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push(
                    {
                        event: "setAccount",
                        account: 30270
                    },
                    {
                        event: "setHashedEmail",
                        email: trackingData.hashed_email
                    },
                    {
                        event: "setSiteType",
                        type: "t"
                    },
                    {
                        event: "trackTransaction",
                        id: trackingData.order_id,
                        new_customer: newCustomer,
                        item: criteo_items,
                        requiresDOM: "yes"
                    })
            } else {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push(
                    {
                        event: "setAccount",
                        account: 30270
                    },
                    {
                        event: "setHashedEmail",
                        email: trackingData.hashed_email
                    },
                    {
                        event: "setSiteType",
                        type: "m"
                    },
                    {
                        event: "trackTransaction",
                        id: trackingData.order_id,
                        new_customer: newCustomer,
                        item: criteo_items,
                        requiresDOM: "yes"
                    });
            }
        } else {
            window.criteo_q = window.criteo_q || [];
            window.criteo_q.push(
                { event: "setAccount",
                    account: 30270 },
                { event: "setHashedEmail",
                    email: trackingData.hashed_email },
                { event: "setSiteType",
                    type: "d" },
                { event: "trackTransaction" ,
                    id: trackingData.order_id,
                    new_customer: newCustomer,
                    item: criteo_items,
                    requiresDOM: "yes"
                })
        }
    }

};

/** -------------- Timeslot Interaction Tracker -------------- **/
var selectDay = function(day) {

    var type = $(day).data("type");

    if($('body').hasClass(type+'_initialSelectDayFired')) {
        var now = moment();
        var selectedDate = moment.utc($(day).data("date"), "YYYY-MM-DD");

        /* day selected */
        var dayTitle = $(day).children('a').attr('title');
        if(!(dayTitle === 'Today' || dayTitle === 'Tomorrow')) {
            dayTitle = dayTitle.substr(0, dayTitle.indexOf(" "));
        }
        mixpanelData[type+'_daySelected'] = dayTitle;

        /* num days to selected day */
        selectedDate.add(1, 'day');
        var daysToSelected = selectedDate.diff(now, 'days');
        mixpanelData[type+'_daysToSelected'] = daysToSelected;

        /* list of slots available */
        var slotsListName = $(day).children('a').data('target');
        var slotsList = $(slotsListName).find('.time_available');
        var timeslotsList = [];
        for(var i =0; i < slotsList.length; i++) {
            var slot = slotsList[i];
            var timeslot = $(slot).data('time');
            var timeStart = moment.utc(timeslot, "HH:mm").format("HH:mm");
            var timeEnd = moment.utc(timeslot, "HH:mm").add(1,'hour').format("HH:mm");
            timeslotsList.push(timeStart+' - '+timeEnd);
        }
        mixpanelData[type+'_daySelectedSlotsAvailable'] = timeslotsList;

        /* num slots available */
        var numSlotsAvailable = $(slotsListName).find('.time_available').length;
        mixpanelData[type+'_daySelectedNumSlotsAvailable'] = numSlotsAvailable;

        /* first slot available */
        mixpanelData[type+'_daySelectedFirstSlotAvailable'] = timeslotsList[0];

        /* hours to first available slot */
        if(dayTitle === 'Today') {
            var firstAvailableSlotDatetime = moment.utc($(day).data("date") + ' ' + timeslotsList[0], "YYYY-MM-DD HH:mm");
            var hoursToFirstAvailableSlot = firstAvailableSlotDatetime.diff(now, 'hours');
            mixpanelData[type+'_daySelectedHoursToFirstAvailableSlot'] = hoursToFirstAvailableSlot;
        }

        if(IS_LAUNDRAPP) {
            mixpanel.track("Collection Day Selected",{
                "Day Selected" : mixpanelData[type+'_daySelected'],
                "Days to Selected Day" : mixpanelData[type+'_daysToSelected'],
                "Number of Slots Available" : mixpanelData[type+'_daySelectedNumSlotsAvailable'],
                "Slots Available" : mixpanelData[type+'_daySelectedSlotsAvailable'],
                "First Slot Available" : mixpanelData[type+'_daySelectedFirstSlotAvailable'],
                "Hours to First Available Slot" : mixpanelData[type+'_daySelectedHoursToFirstAvailableSlot']
            });
        }

        $(this).tab('show');
    }

    $('body').addClass(type+'_initialSelectDayFired');

};

function getAllowancesOnLoad() {
    // we only need it on the items page, do this if it's not there so it doesn't break
    if (typeof allowancesOnLoad === "undefined") {
        return {};
    }

    // deep clone the object otherwise we will get a reference
    return JSON.parse(JSON.stringify(allowancesOnLoad));
}

var CustomerAllowance = {
    allowance: getAllowancesOnLoad(),
    updateUI: function() {
        for (productId in this.allowance) {
            $('.allowance-product-' + productId).text(this.allowance[productId]);
        }
    },
    calculateRemainingAllowances: function(items) {
        var allowances = getAllowancesOnLoad();

        for (var i = 0; i < items.length; i++) {
            if (items[i].id in allowances) {
                allowances[items[i].id] -= items[i].qty;
                allowances[items[i].id] = Math.max(allowances[items[i].id], 0);
            }
        }

        this.allowance = allowances;
        this.updateUI();
    }
};

var selectTime = function(time) {

    var type = $(time).data('type');
    var Type = type.capitalize();

    var slot = moment.utc($(time).data('date') + ' ' + $(time).data('time'), 'YYYY-MM-DD HH:mm');
    var now = moment();

    /* Slot Selected */
    var timeStart = $(time).data('time');
    var timeEnd = moment.utc($(time).data('time'), "HH:mm").add(1, 'hour').format('HH:mm');
    mixpanelData[type+'_slotSelected'] = timeStart + ' - ' + timeEnd;

    /* Hours to Selected Slot */
    if(slot.isSame(now, 'day')) {
        var hours = slot.diff(now, 'hours');
        mixpanelData[type + '_HoursToSelectedSlot'] = hours;

        if(IS_LAUNDRAPP) {
            mixpanel.track(Type+" Slot Selected",{
                "Slot Selected" : mixpanelData[type+'_slotSelected'],
                "Hours to Selected Slot" : mixpanelData[type + '_HoursToSelectedSlot']
            });
        }

    } else {
        if(IS_LAUNDRAPP) {
            mixpanel.track(Type + " Slot Selected", {
                "Slot Selected": mixpanelData[type + '_slotSelected']
            });
        }
    }

    $('body').addClass(type+'_initialSelectTimeFired');

    pulseTimeslots(type);
};

/** -------------- Address Input -------------- **/
$('#address_postcode').on("change focus focusin focusout select keyup keydown click trigger keypress paste mouseup mouseout mousein", function() {
    orders.check_postcode();
});

$('.booking_address_form_country .country_code').on("change", function() {
    $('#address_postcode').trigger('change');
});

$('#address_line_1').on("change select keyup keydown click trigger focus focusin focusout keypress", function() {
    if($('#address_line_1').val().length > 1) {
        $('#booking_address_feedback').hide(500);
        $('#address_line_1-error').css("display","none");
    }
});

var showAddressInput = function() {
    $('#booking_address_form_head').css("margin-bottom","0px");
    $('#booking_address_form_head').show();
    $('#booking_address_form_line_1').show();
    $('#booking_address_form_line_2').show();
    $('#booking_address_form_postcode').show();
    $('#booking_address_form_country').show();
    $('#booking_address_form_delivery_notes').show();
    $('#booking_address_edit_link').hide();
    $('#booking_address_display').hide();
    $('#address-suggest-row').hide();
    $('#address-original-row').hide();
};

var showAddressEntered = function() {
    $('#booking_address_form_line_1').hide();
    $('#booking_address_form_line_2').hide();
    $('#booking_address_form_postcode').hide();
    $('#booking_address_form_country').hide();
    $('#booking_address_form_delivery_notes').hide();
    $('#booking_address_display').show();
    $('#booking_address_edit_link').show();
};

/** ------------ Address Validation ------------ **/
var pleaseEnterAValidAddress = function() {
    $('html, body').animate({
        scrollTop: ($("#booking_address_form_head").offset().top) - 100
    }, 1000);
    $('#booking_address_feedback').show();
    orders.reset_spinner();
};

var pleaseEnterAValidLine1 = function() {
    $('html, body').animate({
        scrollTop: ($("#booking_address_form_head").offset().top) - 100
    }, 1000);
    $('#address_line_1-error').text(window.WebFunnel.please_enter_line_1).show();
    orders.reset_spinner();
};

var pleaseEnterAValidPostcode = function() {
    $('html, body').animate({
        scrollTop: ($("#booking_address_form_head").offset().top) - 100
    }, 200);
    $('#booking_postcode_feedback').html(window.WebFunnel.please_enter_valid_pc);
    $('#booking_postcode_feedback').show();
    orders.reset_spinner();
};

var pleaseEnterAFullPostcode = function() {
    $('html, body').animate({
        scrollTop: ($("#booking_address_form_head").offset().top) - 100
    }, 200);
    $('#booking_postcode_feedback').html(window.WebFunnel.please_enter_full_pc);
    $('#booking_postcode_feedback').show();
    orders.reset_spinner();
};


/** ------------------ Timeslots ------------------ **/
$('#collectionTimesModalLink').on("click", function(e) {
    showCollectionTimesModal(e);
});

$('#deliveryTimesModalLink').on("click", function(e) {
    showDeliveryTimesModal(e);
});

window.setDeliveryDay = function() {
    if(JSON.parse($('#user-info').html())['has_user_selected_delivery_times']) {
        setTimeout(
            $('ul.deliveryDaysList [data-date="'+body.dataset.delivery.substr(0,10)+'"] a').click(),
            100
        )
    } else {
        $('.deliveryDaysList li:first-child').addClass('active');
    }
};

function checkIfTimeslotsRendered() {
    if(!$('#collection_div_timeslots_inject').children().length || !$('#delivery_div_timeslots_inject').children().length) {
        orders.got_timeslots = false;
    }
}

function showCollectionTimesModal(e) {

    checkIfTimeslotsRendered();

    if($('#address_postcode').val().replace(/\s/g,'').length != 0) {
        if(IS_LAUNDRAPP) {
            mixpanel.track("Collection Time Open", {
                "Nb of Days Available": mixpanelData['numCollectionDaysAvailable'],
                "Today Available": mixpanelData['collectionTodayAvailable'],
                "Tomorrow Available": mixpanelData['collectionTomorrowAvailable'],
                "Days Available": mixpanelData['collectionDaysAvailableList'],
                "Days to First Available Day": mixpanelData['numDaysToFirstCollection']
            });
        }

        window.showTimeslot = 'collection';

        orders.got_timeslots = false;

        if(orders.timeslots_request === null) {
            orders.get_timeslots();
            showCollectionModal();
        } else {
            showCollectionModal();
        }

    } else {
        // scroll to address
        e.stopPropagation();
        pleaseEnterAValidPostcode();
    }
}

function showDeliveryTimesModal(e) {

    checkIfTimeslotsRendered();

    if($('#address_postcode').val().replace(/\s/g,'').length != 0) {

        if(document.body.dataset.collection === null) {
            window.showTimeslot = 'collection';
            showCollectionTimesModal();
            $('#please-select-collection').show();


            if(IS_LAUNDRAPP) {

                mixpanel.track("Collection Time Open",{
                    "Nb of Days Available" : mixpanelData['numCollectionDaysAvailable'],
                    "Today Available" : mixpanelData['collectionTodayAvailable'],
                    "Tomorrow Available" : mixpanelData['collectionTomorrowAvailable'],
                    "Days Available" : mixpanelData['collectionDaysAvailableList'],
                    "Days to First Available Day" : mixpanelData['numDaysToFirstCollection']
                });
            }
        } else {
            window.showTimeslot = 'delivery';
        }


        if(IS_LAUNDRAPP) {
            mixpanel.track("Delivery Time Open",{
                "Nb of Days Available" : mixpanelData['numDeliveryDaysAvailable'],
                "Days Available" : mixpanelData['deliveryDaysAvailableList'],
                "Days to First Available Day" : mixpanelData['numDaysToFirstDelivery']
            });
        }

        if(!orders.got_timeslots) {

            if(orders.timeslots_request === null) {
                orders.get_timeslots();
            }

            showDeliveryModal();

        } else {
            if(!$('#delivery_div_timeslots_inject').children().length) {
                showCollectionModal();
            } else {
                showDeliveryModal();
            }

        }

        /* -- update delivery times list -- */
        $('.deliveryDay').each(function () {
            $(this).removeClass('active'); // unset all days
        });

        if($('#delivery_div_timeslots_inject').html().trim() === 0) {
            $('.booking_timeslots-modal-loader').show();
        } else {
            $('.booking_timeslots-modal-loader').hide();
            $('#booking_timeslots-collection-modal-content').show();
        }

    } else {
        e.stopPropagation();
        pleaseEnterAValidPostcode();
    }
}

var pulseTimeslots = function(type) {
    $('#'+type+'TimesModalLinkWrap').addClass('pulse animated');
    setTimeout(function () {
        $('#'+type+'TimesModalLinkWrap').removeClass('pulse animated');
    }, 2000);
};


/** ------------ Timeslots Validation ------------ **/
var pleaseSelectCollectionAndDeliveryTimes = function() {
    $('html, body').animate({
        scrollTop: ($("#booking_times_form_head").offset().top) - 100
    }, 1000);
    $('#booking_times_feedback').show();
};

var hideTimeslots = function() {
    $('#collectionTimesModalLinkWrap').hide();
    $('#deliveryTimesModalLinkWrap').hide();
    $('#download-ical-wrap').hide();
    $('.calendar-download').hide();
    $('#searching-for-timeslots-text').show();
    $('#noTimeslotsAvailableForPostcode').hide();
};

var hideTimeslotsNoSearch = function() {
    $('#collectionTimesModalLinkWrap').hide();
    $('#deliveryTimesModalLinkWrap').hide();
    $('#download-ical-wrap').hide();
    $('.calendar-download').hide();
    $('#searching-for-timeslots-text').hide();
    $('#noTimeslotsAvailableForPostcode').hide();
};

var showNoTimeslotsAvailableForPostcode = function() {
    $('#noTimeslotsAvailableForPostcode').show();
    NProgress.done();
};

var showTimeslots = function() {
    $('#collectionTimesModalLinkWrap').show();
    $('#deliveryTimesModalLinkWrap').show();
    $('#download-ical-wrap').show();
    $('#searching-for-timeslots-text').hide();
    $('.calendar-download').show();
    $('#noTimeslotsAvailableForPostcode').hide();
};

var showPostcodeLoader = function(){
    $('#address_postcode').addClass('input-loader');
};

var hidePostcodeLoader = function() {
    $('#address_postcode').removeClass('input-loader');
};

var showPostcodeFeedback = function(message) {
    if(message) {
        $('#booking_postcode_feedback').html(message);
    }
    $('#booking_postcode_feedback').css("display","block");
};

var hidePostcodeFeedback = function() {
    $('#booking_postcode_feedback').css("display","none");
    $('#address_postcode').removeClass('input-loader');
};

/** ------ Analytics ------ **/
track = {
    event: function (name, type, value) {
        if (IS_LAUNDRAPP) {
            if (typeof ga == 'function') {
                ga('send', 'event', name, type, value);
            }
        }
    }
};

/* -- View Category Item -- */
$('.category-item').on("click", function() {
    var catId = $(this).data('filter').substr(10);
    var catIndex = $(this).index(".category-item");

    if(IS_LAUNDRAPP) {
        ga('send', {
            hitType: 'event',
            eventCategory: 'WebOrderFunnel',
            eventAction: 'ViewNewCategory',
            eventLabel: $(this).find('.category-label').text()
        });

        mixpanel.track("Category Selected", {
            "Category Name": $(this).find('.category-label').text(),
            "Category ID": catId,
            "Category Index": catIndex
        });

        /* -- Criteo category / listing tracker -- */
        var itemsInCategory = $('.category-' + catId);
        var itemIdsInCategory = [];


        if (!($('#user-info').html().trim().length === 0)) {
            userInfo = JSON.parse($('#user-info').html());
        }

        for (var i = 0; i < itemsInCategory.length; i++) {
            itemIdsInCategory.push($(itemsInCategory[i]).data("product-id"));
            if (i == 2) {
                break;
            }
            /* criteo takes up to 3 item ids */
        }

        if (typeof userInfo['hashed_email'] === 'undefined') {
            userInfo['hashed_email'] = "";
        }

        if ($('#meta-is-mobile').length > 0) {
            if ($('#meta-is-tablet').length > 0) {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push(
                    {event: "setAccount", account: 30270},
                    {event: "setHashedEmail", email: userInfo['hashed_email']},
                    {event: "setSiteType", type: "t"},
                    {event: "viewList", item: JSON.stringify(itemIdsInCategory), requiresDOM: "yes"}
                );
            } else {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push(
                    {event: "setAccount", account: 30270},
                    {event: "setHashedEmail", email: userInfo['hashed_email']},
                    {event: "setSiteType", type: "m"},
                    {event: "viewList", item: JSON.stringify(itemIdsInCategory), requiresDOM: "yes"}
                );
            }
        } else {
            window.criteo_q = window.criteo_q || [];
            window.criteo_q.push(
                {event: "setAccount", account: 30270},
                {event: "setHashedEmail", email: userInfo['hashed_email']},
                {event: "setSiteType", type: "d"},
                {event: "viewList", item: JSON.stringify(itemIdsInCategory), requiresDOM: "yes"}
            );
        }
    }

});

/** ------ Calendar ------ **/
var downloadCalendar = function(ajaxRoute) {
    if(IS_LAUNDRAPP) {
        ga('send', {
            hitType: 'event',
            eventCategory: 'WebOrderFunnel',
            eventAction: 'DownloadCalendar'
        });
    }

    currentRequest = $.ajax({
        type: "GET",
        url: ajaxRoute,
        cache: false,
        beforeSend: function () {
            if (currentRequest != null)
                currentRequest.abort()
        },
        success: function (res) {

        },
        error: function (xhr) {
            var error = $.parseJSON(xhr.responseText);
            $('#login-error').html(error.message);

        }
    });
};

/** ------ Disable submit if terms disabled ------ **/
$('#terms').on("click", function() {
    if(document.getElementById('terms').checked) {
        document.getElementById('postContact').disabled = false;
    } else {
        document.getElementById('postContact').disabled = true;
    }
});

function showLoginModal(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        data: {email: $('.email-popup').val()},
        success: function (res) {
            $('.login-modal-content').html(res);
            $('#login-modal').modal('show');
        }
    });
}

function showReferralModal() {
    $.ajax({
        type: "GET",
        url: '/referral_unavailable',
        cache: false,
        //data: {email: $('.email-popup').val()},
        success: function (res) {
            $('.login-modal-content').html(res);
            $('#login-modal').modal('show');
        }
    });
}

/** ------------ Order! ------------ **/
var orders = {

    collection_date: null,
    collection_time: null,
    delivery_date: null,
    delivery_time: null,
    button_text: null,
    address: '',
    address_checked: false,
    full_address : null,
    postcode_covered: false,
    postcode_checked: false,
    got_timeslots: false,
    timeslots_request: null,
    line_1 : null,
    line_2 : null,
    postcode : null,
    delivery_notes : null,
    special_instructions: null,
    voucher: null,
    postcode_formatted: false,
    is_original_address: null,

    init: function () {



        if(!($('#user-info').html().trim().length === 0)) {
            currentPostcode = userInfo['address_postcode'];
            currentCountry = userInfo['country_code'];
        }

        /** --- Load the initial cart object --- **/
        orders.init_submit_items();
        orders.init_back_button();
        orders.cart_get();

        /** --- prepare submission / validation --- **/
        $('#booking_validate_submit').on("click", function() {

            var line_1_value = $.trim($('.address_line_1').val()).length;

            if (!line_1_value) {
                //$('#address_line_1-error').text(window.WebFunnel.please_enter_line_1).show();
                pleaseEnterAValidLine1();
                return;
            }

            if(!orders.postcode_covered && orders.postcode_checked) {
                pleaseEnterAValidPostcode();
                return;
            }

            orders.validate_booking();
        });

        if(newURL.indexOf('/booking') !== -1) {
            if(JSON.parse($('#user-info').html())['address_postcode'].length > 0) {
                $('#address_postcode').val(JSON.parse($('#user-info').html())['address_postcode']);
                $('#country_code').val(JSON.parse($('#user-info').html())['country_code']);
            }

            if(IS_LAUNDRAPP) { // need to check if user has entered half a postcode, if so, ask for more postcode
                if( !(orders.check_postcode_regex(JSON.parse($('#user-info').html())['address_postcode'], JSON.parse($('#user-info').html())['country_code'])) ) {
                    orders.postcode_covered = false;
                    pleaseEnterAFullPostcode();
                    hideTimeslotsNoSearch();
                }
            }

            if(JSON.parse($('#user-info').html())['has_user_selected_collection_times'] === true) {

                if (JSON.parse($('#user-info').html())['collection_time'].length > 0 && JSON.parse($('#user-info').html())['delivery_time'].length > 0) {

                    /* TODO:nia - now check if expiry dates have been reached... this will happen on next screen anyway.. */
                    document.body.dataset.userselectedtimes = true;

                    document.body.dataset.collection = JSON.parse($('#user-info').html())['collection_time'];
                    document.body.dataset.delivery = JSON.parse($('#user-info').html())['delivery_time'];

                    /* display timeslots in modal links */
                    $('#booking__collection-date').html(moment.utc(document.body.dataset.collection, 'YYYY-MM-DD HH:mm').format("ddd, MMM Do"));
                    $('#booking__collection-slot-start').html(moment.utc(document.body.dataset.collection, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#booking__collection-slot-end').html(moment.utc(document.body.dataset.collection, 'YYYY-MM-DD HH:mm').add(60, "minutes").format('HH:mm'));

                    $('#booking__delivery-date').html(moment.utc(document.body.dataset.delivery, 'YYYY-MM-DD HH:mm').format("ddd, MMM Do"));
                    $('#booking__delivery-slot-start').html(moment.utc(document.body.dataset.delivery, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#booking__delivery-slot-end').html(moment.utc(document.body.dataset.delivery, 'YYYY-MM-DD HH:mm').add(60, "minutes").format('HH:mm'));

                    orders.got_timeslots = true;
                }
            }

            if( !(JSON.parse($('#user-info').html())['collection_fee'] === '0.00' || JSON.parse($('#user-info').html())['collection_fee'] == '0' || JSON.parse($('#user-info').html())['collection_fee'] === null) ) {
                $('#booking_collection_price').html(window.WebFunnel.currency_symbol+JSON.parse($('#user-info').html())['collection_fee']);
                document.body.dataset.collectionfee = window.WebFunnel.currency_symbol+JSON.parse($('#user-info').html())['collection_fee'];
            }

            if( !(JSON.parse($('#user-info').html())['delivery_fee'] === '0.00' || JSON.parse($('#user-info').html())['delivery_fee'] == '0' || JSON.parse($('#user-info').html())['delivery_fee'] === null) ) {
                $('#booking_delivery_price').html(window.WebFunnel.currency_symbol+JSON.parse($('#user-info').html())['delivery_fee']);
                document.body.dataset.deliveryfee = window.WebFunnel.currency_symbol+JSON.parse($('#user-info').html())['delivery_fee'];
            }

            if (!(document.body.dataset.userselectedtimes === "true" || document.body.dataset.userselectedtimes === "TRUE")) {
                if(typeof document.body.dataset.collection === 'undefined' ) {
                    orders.get_timeslots();
                }
            }

        }


        /** --------- Select Address from Lookup --------- **/
        $(document).on('click', '.address_button', function () {
            var el = $(this);
            orders.address_checked = true;
            orders.address = true;

            $('.address_button').removeClass('active');

            el.addClass('active');

            $('.address_line_1').val(el.data('address-line_1'));
            $('.address_line_2').val(el.data('address-line_2'));
            $('.address_postcode').val(el.data('address-postcode'));
            if (el.data('is-original') && $('.address_line_1').val !=='') {
                orders.is_original_address = true;
            }
            orders.validate_booking();
        });


        /** --------- Prepare the carousel --------- **/
        var owl = $("#category-carousel");

        if (owl.length) {
            owl.owlCarousel({
                items: CAROUSEL_ITEMS, //10 items above 1000px browser width
                itemsDesktop: [1000, 5], //5 items between 1000px and 901px
                itemsDesktopSmall: [900, 3], // betweem 900px and 601px
                itemsTablet: [600, 2], //2 items between 600 and 0
                itemsMobile: false, // itemsMobile disabled - inherit from itemsTablet option
                pagination: false,
                navigation: true,
                navigationText: [
                    "<i class='left_arrow'><</i>",
                    "<i class='right_arrow'>></i>"
                ],
                scrollPerPage: true,
                mouseDraggable: false,
                afterAction: orders.caruselArrows
            });

        }

        if ($('.products').length) {
            $('.products').mixItUp({
                load: {
                    filter: '.category-'+MAIN_CATEGORY
                },
                animation: {
                    enable: false
                }
            });
        }
        $('#category-carousel .item').on('click', function (event) {
            $('#category-carousel .item').removeClass('active');
            $(this).addClass('active');
        });

        // Custom Navigation Events
        $(".cat-next").click(function () {
            owl.trigger('owl.next');
        });

        $(".cat-prev").click(function () {
            owl.trigger('owl.prev');
        });

        /** ------------ Login ------------ **/
        $(document).on('click', '.popup-load', function () {
            var modalUrl = $(this).data('url');
            showLoginModal(modalUrl);
        });

        $(document).ready(function() {
            if (document.URL.indexOf("signin") > -1) {
                // hard coded as AJAX req gives maxium stack error
                showLoginModal('/login');
            }
        });

        $(document).on('submit', '.js-customer-login', function () {

            var url = $('.js-customer-login').attr('action');
            currentRequest = $.ajax({
                type: "POST",
                url: url,
                cache: false,
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function () {
                    if (currentRequest != null)
                        currentRequest.abort()
                },
                success: function (res) {

                    if(IS_LAUNDRAPP) {
                        mixpanel.identify(res.customer_id.toString());
                    }

                    if (res.view) {
                        $('.modal-content').html(res.view);
                    } else if (res.redirect) {
                        // window.location.href = res.redirect;
                        window.location.href = window.location.href.split('?')[0];
                    } else if (res.reload) {
                        window.location.href = window.location.href.split('?')[0];
                    }
                },
                error: function (xhr) {
                    var error = $.parseJSON(xhr.responseText);

                    $('.login-error').html(error.message);

                }
            });

            return false; // avoid to execute the actual submit of the form.

        });

        $(document).on('submit', '.js-customer-remind', function () {

            var url = $('.js-customer-remind').attr('action');

            currentRequest = $.ajax({
                type: "POST",
                url: url,
                cache: false,
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function () {
                    if (currentRequest != null)
                        currentRequest.abort()
                },
                success: function (res) {

                    if (res.view) {
                        $('.modal-content').html(res.view);
                    } else if (res.redirect) {
                        window.location.href = res.redirect;
                    } else if (res.reload) {
                        window.location.href = window.location.href;
                    }

                },
                error: function (error) {

                    $('#login-error').html('<span class="help-block error">'+window.WebFunnel.email_not_recognised+'</span>');

                }
            });

            return false; // avoid to execute the actual submit of the form.

        });


        $(document).on("click", '#timeslotsEditLink', function() {

            window.showTimeslot = 'collection';
            orders.got_timeslots = false;
            orders.get_timeslots();


            if(IS_LAUNDRAPP) {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'WebOrderFunnel',
                    eventAction: 'EditTimes'
                });
            }
        });

        $(document).on("click", '.trustpilot-widget', function() {
            if(IS_LAUNDRAPP) {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'WebOrderFunnel',
                    eventAction: 'ViewTrustpilotReviews',
                    eventLabel: window.location.href
                });
            }
        });

        var updateAllowance = function(productId, remaining) {
            $('.allowance-product-' + productId).text(remaining);
        };

        /** --------- Cart interactions --------- **/
        $(document).on('click', '.cart_add', function () {
            var productId = $(this).data('id');
            var priceId = $(this).data('price_id');
            orders.cart_add(productId, priceId, 1);
        });

        $(document).on('click', '.cart_remove', function () {
            var productId = $(this).data('id');
            if(window.location.href.substring(window.location.href.length -7) === 'booking') {
                $(this).parent().addClass('animated fadeOut');
            }
            orders.cart_remove(productId, 1);
        });

        /* --- submit button preload spinner --- */
        if ($('.submit-box').length) {
            $('.submit-box').append('<img src="' + window.WebFunnel.spinner + '" style="display:none"/>');
        }

        $(document).on('click', 'button.btn-success', function () {
            var button = $(this);
            //orders.set_spinner(button);
        });

        /** --------- Select Collection Timeslot --------- **/
        $(document).on('click', '.collection_times .time_available', function (event) {

            $('body').addClass('initialCollectionTimesAvailableFired');


            if($('body').hasClass('initialCollectionTimesAvailableFired')) {
                document.body.dataset.collectionelement = event.target.id;
                $('.timepicker__content--list-item').removeClass('active');

                $(this).addClass('active');
                $('.collection_timeslots .timepicker__day-list--li').removeClass('selected');
                $('.collection_timeslots .timepicker__content').children().each(function (i, el) {
                    if ($(el).hasClass('active')) {
                        $('.collection_timeslots .timepicker__day-list--li').eq(i).addClass('selected'); //adds selected to the day tabs
                    }
                });

                orders.collection_date = $(this).data('date');
                orders.collection_time = $(this).data('time');

                var collection_datetime = moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm');

                orders.write_time('collection', moment.utc(orders.collection_date + ' ' + orders.collection_time, 'YYYY-MM-DD HH:mm'), $(this).data('fee'));
                $('.collection_fee').val($(this).data('fee'));
                $('.collection_expiry').val($(this).data('expiry'));

                if($(this).data('fee') == '0.00' || $(this).data('fee') == '0' || $(this).data('fee') == null) {
                    $('#booking_collection_price').hide();
                } else {
                    $('#booking_collection_price').html(window.WebFunnel.currency_symbol + $(this).data('fee'));
                    $('#booking_collection_price').show();
                }

                // TODO:nia get updated turnaround time
                var earliest_delivery = collection_datetime.add(48, 'hours');
                var change_delivery = orders.check_if_delivery_changed(collection_datetime);

                orders.set_earliest_delivery(earliest_delivery, change_delivery);

                $('#booking__collection-date').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format("ddd, MMM Do"));
                $('#booking__collection-slot-start').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format('HH:mm'));
                $('#booking__collection-slot-end').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').add(60, "minutes").format('HH:mm'));

                /* -- if user has already selected delivery times, dont reload thm -- */
                //if( !(JSON.parse($('#user-info').html())['has_user_selected_delivery_times'] === true) ){
                orders.loadDelivery(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format("YYYY-MM-DD HH:mm:ss"));
                //} else {
                //     $('.deliveryModalLinkNextAvailable').css("display", "block");
                //     $('br.deliveryModalLinkNextAvailable').css("display", "none");
                //     $('#deliveryModalLinkLoadingImg').css("display", "none");
                // }

                document.body.dataset.delivery = "";
                document.body.dataset.usersetdelivery = "";
                document.body.dataset.deliveryelement = "";

                document.body.dataset.collection = moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format("YYYY-MM-DD HH:mm");
                document.body.dataset.usersetcollection = "TRUE";
                document.body.dataset.usersetcollectionfee = $(this).data('fee');

                var orderEventLabel = moment.utc(orders.collection_date + ' ' + orders.collection_time, 'YYYY-MM-DD HH:mm');

                if(IS_LAUNDRAPP) {
                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'WebOrderFunnel',
                        eventAction: 'SelectCollectionTime',
                        eventLabel: orderEventLabel
                    });
                }

                $('.modal').modal('hide');

                if(window.isMobileDevice) {
                    hideCollectionModalMobile();
                } else {
                    $("#collectionTimesSelectModal .close").click();
                }

            }
        });

        /** --------- Select Delivery Timeslot --------- **/
        $(document).on('click', '.delivery_times .time_available', function (event) {

            $('.delivery_times .time_available').removeClass('active');
            $('.delivery_timeslots .timepicker__day-list--li').removeClass('selected');
            $('.delivery_timeslots .timepicker__content').children().each(function (i, el) {
                if ($(el).hasClass('active')) {
                    $('.delivery_timeslots .timepicker__day-list--li').eq(i).addClass('selected');
                }
            });

            $(this).addClass('active');

            document.body.dataset.deliveryelement = event.target.id;

            orders.delivery_date = $(this).data('date');
            orders.delivery_time = $(this).data('time');

            var delivery_datetime = moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm');

            orders.write_time('delivery', delivery_datetime, $(this).data('fee'));
            $('.delivery_fee').val($(this).data('fee'));
            $('.delivery_expiry').val($(this).data('expiry'));

            if($(this).data('fee') === '0.00' || $(this).data('fee') === '') {
                $('#booking_delivery_price').hide();
            } else {
                $('#booking_delivery_price').html(window.WebFunnel.currency_symbol+$(this).data('fee'));
                $('#booking_delivery_price').show();
            }

            $('#booking__delivery-date').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format("ddd, MMM Do"));

            $('#booking__delivery-slot-start').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').format('HH:mm'));
            $('#booking__delivery-slot-end').html(moment.utc($(this).data('date') + ' ' + $(this).data('time'), 'YYYY-MM-DD HH:mm').add(60, "minutes").format('HH:mm'));

            var eventLabel = moment.utc(orders.delivery_date + ' ' + orders.delivery_time, 'YYYY-MM-DD HH:mm').format("YYYY-MM-DD HH:mm");

            document.body.dataset.delivery = eventLabel;
            document.body.dataset.usersetdelivery = "TRUE";

            if(IS_LAUNDRAPP) {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'WebOrderFunnel',
                    eventAction: 'SelectDeliveryTime',
                    eventLabel: eventLabel
                });
            }

            $('.modal').modal('hide');

            if(window.isMobileDevice) {
                hideDeliveryModalMobile();
            } else {
                $("#deliveryTimesSelectModal .close").click();
            }
        });

        /** --------- Apply Voucher --------- **/
        $(document).on('click', '.voucher_button', function () {

            var voucher = $('.voucher').val();
            var voucher_url = $(this).data('url');

            if(voucher != '') {
                currentRequest = $.ajax({
                    type: "POST",
                    url: voucher_url,
                    cache: false,
                    dataType: 'json',
                    data: {voucher: voucher},
                    beforeSend: function () {
                        if (currentRequest != null)
                            currentRequest.abort()
                        NProgress.start();
                    },
                    success: function (res) {

                        $('.js-voucher-add').addClass('hide');

                        if(IS_LAUNDRAPP) {
                            ga('send', {
                                hitType: 'event',
                                eventCategory: 'WebOrderFunnel',
                                eventAction: 'VoucherValid',
                                eventLabel: voucher
                            });
                            mixpanel.track("Voucher Entered", {
                                Valid: true,
                                Voucher: voucher
                            });
                        }

                        orders.cart_get();
                        $('.voucher_message').css("display","none");
                        NProgress.done();

                    },
                    error: function (xhr) {

                        var errorMessage;

                        if(xhr.responseJSON.message.error_message.length > 1) {
                            errorMessage = xhr.responseJSON.message.error_message;
                        } else {
                            var $json = $.parseJSON(xhr.responseText);
                            errorMessage = $json.hasOwnProperty('error') ? $json.error.error_message : $json.error_message;
                        }

                        $('.voucher_message').removeClass('success').addClass('error').html(errorMessage);
                        $('.voucher_message').show();
                        $('.voucher').val('');
                        NProgress.done();
                        orders.reset_spinner();

                        if(IS_LAUNDRAPP) {
                            ga('send', {
                                hitType: 'event',
                                eventCategory: 'WebOrderFunnel',
                                eventAction: 'VoucherInvalid',
                                eventLabel: voucher
                            });
                            mixpanel.track("Voucher Entered", {
                                Valid: false,
                                Voucher: voucher
                            });
                        }

                    }
                });
            }

        });

        /** --------- Remove Voucher --------- **/
        $(document).on('click', '.remove_voucher', function () {

            var url = $('.cart-checkout .coupon').data('url');

            currentRequest = $.ajax({
                type: "POST",
                url: url,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    if (currentRequest != null)
                        currentRequest.abort()
                },
                success: function (res) {

                    $('.js-voucher-add').removeClass('hide');
                    orders.cart_get();

                }
            });

        });

        /** --------- Check Email --------- **/
        $('.js-mailchimp').on('submit', function (e) {

            e.preventDefault();
            var data = $(this).serializeArray();


            /** Email checking Regex */
            var reg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

            var email = $('.email_input').val();
            var postcode = $('.js-postcode-value').val();

            if (!reg.test(email)) {

                $('.email_block').addClass('has-error');
                $('.email_input').focus();

                return false;

            }

            $('.email_block').removeClass('has-error');

            var url = $(this).attr('action');

            currentRequest = $.ajax({
                type: "POST",
                url: url,
                cache: false,
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    if (currentRequest != null)
                        currentRequest.abort()
                },
                success: function (res) {
                    $('.email_block').removeClass('has-error');

                    $('.js-mailchimp').addClass('zoomOutDown animated');
                    $('.email_success').addClass('zoomInDown animated').removeClass('hide');

                    orders.reset_spinner();

                },
                error: function () {

                    orders.reset_spinner();
                    $('.email_block').addClass('has-error');
                    return false;
                }
            });

            orders.log(data);
        });

        /** --------- Phone Validator --------- **/
        $('.js-sms-link').on('submit', function (e) {

            e.preventDefault();

            $('.phone-block').removeClass('has-error');

            var phone = $('.js-phone').val();

            if (phone.length < 9) {

                $('.phone-block').addClass('has-error');
                $('.phone-block').focus();

                return false;

            }

            var url = $(this).attr('action');

            currentRequest = $.ajax({
                type: "POST",
                url: url,
                cache: false,
                dataType: 'json',
                data: {phone: phone},
                beforeSend: function () {
                    if (currentRequest != null)
                        currentRequest.abort()
                },
                success: function (res) {

                    $('.phone-block').removeClass('has-error');
                    $('.js-sms-link').addClass('zoomOutDown animated');
                    $('.sms-success').addClass('zoomInDown animated').removeClass('hide');


                    orders.reset_spinner();
                    if(IS_LAUNDRAPP) {
                        track.event('download app', 'sms', phone);
                    }

                },
                error: function () {

                    orders.reset_spinner();
                    $('.phone-block').addClass('has-error');
                    return false;
                }
            });
        });
        // ?? orders.get_timeslots();
    },

    /** --------- Update Delivery Times when Collection is set --------- **/
    loadDelivery: function (collection_date) {

        var h = $('#collectionTimesModalLink').height();
        var w = $('#collectionTimesModalLink').width();

        $('.deliveryModalLinkNextAvailable').hide();
        $('#deliveryModalLinkLoadingImg').css({"display" : "block",
            "height"  : h,
            "width"   : w});

        currentRequest = $.ajax({
            type: 'GET',
            url: BASE_URL + '/ajax/times',
            cache: false,
            dataType: 'json',
            data: { "date" : collection_date },

            beforeSend: function () {
                if (currentRequest != null)
                    currentRequest.abort();
                if(collection_date === 'Invalid date') {
                    window.location.href = webFunnelUrl + '/items';
                }
            },

            success: function (res) {

                if(window.isMobileDevice) {
                    $('#delivery_div_timeslots_inject-mobile').html(res.view);
                } else {
                    $('#delivery_div_timeslots_inject').html(res.view);
                    $('#delivery_div_timeslots_inject').removeClass('hidden');
                }

                var nextAvailableDeliveryDate;
                var nextAvailableDeliveryDay;
                var nextAvailableDeliveryTime;
                var nextAvailableDeliveryExpiry;
                var nextAvailableDeliveryFee;

                /* ---- Looks for next available delivery time | TODO:nia ---- */
                for (var d = 0; d < res.dates.length; d++) {
                    if (typeof nextAvailableDeliveryTime=== "undefined") {
                        if (res.dates[d].available === true) {
                            nextAvailableDeliveryDate = res.dates[d].value;
                            for (var i = 0; i < res.dates[d].times.length; i++) {

                                if (typeof nextAvailableDeliveryTime === "undefined") {
                                    if (res.dates[d].times[i].available === true) {
                                        nextAvailableDeliveryTime = res.dates[d].times[i].value;
                                        nextAvailableDeliveryDay = d;
                                        nextAvailableDeliveryExpiry = res.dates[d].times[i].expiry;
                                        nextAvailableDeliveryFee = res.dates[d].times[i].fee;
                                    } else {
                                        // unset this timeslot
                                        var _time = res.dates[d].times[i].value;
                                        $('#delivery_'+d+'_'+_time.substring(0,2)).removeClass('time_available');
                                        $('#delivery_'+d+'_'+_time.substring(0,2)).addClass('time_disabled');
                                    }
                                }

                            }
                        }
                    }
                }

                orders.delivery_time = nextAvailableDeliveryTime;
                orders.delivery_date = nextAvailableDeliveryDate;

                orders.write_time('delivery', moment.utc(nextAvailableDeliveryDate + ' ' +nextAvailableDeliveryTime, 'YYYY-MM-DD HH:mm'), nextAvailableDeliveryFee);

                var nextAvailableDeliveryDateFormatted = moment.utc(nextAvailableDeliveryDate, 'YYYY-MM-DD HH:mm').format('ddd, MMM Do');
                var nextAvailableDeliveryTimeFormatted = moment.utc(nextAvailableDeliveryTime, 'HH:mm').format('HH:mm');
                var nextAvailableDeliveryTimeEndFormatted = moment.utc(nextAvailableDeliveryTime, 'HH:mm').add(1, "hours").format('HH:mm');

                $('#delivery-times-list > .address_button').each(function() {
                    $( this ).removeClass('active'); // unset all other delivery timeslots
                });
                $('#delivery_' + nextAvailableDeliveryDay + '_' + nextAvailableDeliveryTime.substring(0, 2)).addClass('active');

                $('#booking__delivery-date').text(nextAvailableDeliveryDateFormatted);
                $('#booking__delivery-slot-start').text(nextAvailableDeliveryTimeFormatted);
                $('#booking__delivery-slot-end').text(nextAvailableDeliveryTimeEndFormatted);

                $('#deliveryModalLinkLoadingImg').hide();
                $('.deliveryModalLinkNextAvailable').fadeIn();

                orders.reset_spinner();

            }
        });


    },
    caruselArrows: function () {

        if (this.owl.currentItem == 0) {
            $('.owl-prev').hide();
            $('.owl-next').show();
        } else {

            $('.owl-prev').show();
            $('.owl-next').hide();
        }
    },


    menu: function () {
        var pull = $('#pull');
        menu = $('.menu ul');
        menuHeight = menu.height();

        $(pull).on('click', function (e) {
            e.preventDefault();
            menu.slideToggle();
        });

    },
    /** --------- Checks postcode input --------- **/
    check_postcode: function(country) {

        if ( !($('#address_postcode').val() != currentPostcode || $('#country_code').val() != currentCountry) ) {
            return;
        }

        if( !(orders.check_postcode_regex($('#address_postcode').val(), $('#country_code').val())) ) {

            pleaseEnterAValidPostcode();
            hideTimeslotsNoSearch();
            orders.got_timeslots = false;
            orders.postcode_covered = false;
            orders.postcode_checked = true;
            currentPostcode = $('#address_postcode').val();
            currentCountry = $('#country_code').val();

            return;
        }

        showPostcodeLoader();
        hidePostcodeFeedback();


        currentRequest = $.ajax({
            type: "POST",
            url: '/ajax/postcode',
            cache: false,
            data: {
                address_postcode: $('#address_postcode').val(),
                country_code: $('#country_code').val()
            },
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }

                orders.got_timeslots = false;
                orders.postcode_covered = false;
                orders.postcode_checked = true;
            },

            statusCode: {
                400: function(xhr) {

                    if(xhr.responseJSON.error) {
                        showPostcodeFeedback(xhr.responseJSON.error);
                        hidePostcodeLoader();
                    }

                    currentPostcode = $('#address_postcode').val();
                    currentCountry = $('#country_code').val();

                    orders.postcode_covered = false;
                    orders.postcode_checked = true;

                    document.getElementById('booking_validate_submit').disabled = true;

                    hideTimeslotsNoSearch();
                },

                200: function(postcodeCovered) {

                    if (postcodeCovered.status == 'success') {
                        orders.cart_get();
                        orders.postcode_covered = true;
                        orders.postcode_checked = true;
                        minimumOrder = postcodeCovered.minimum;

                        document.body.dataset.userselection = "";
                        document.body.dataset.usersetcollection = "";
                        document.body.dataset.usersetdelivery = "";

                        hidePostcodeFeedback();
                        hidePostcodeLoader();

                        orders.get_timeslots();
                        showTimeslots();

                        currentPostcode = $('#address_postcode').val();
                        currentCountry = $('#country_code').val();

                        return true;
                    }

                }
            },
            success: function (postcodeCovered) {

            },
            error: function (xhr) {

            }
        });

        return currentRequest;
    },

    check_postcode_regex: function(postcode, countryCode) {

        var regex = postcodeRegexes[countryCode].substring(1, postcodeRegexes[countryCode].length - 1);

        var postcodeRegEx = new RegExp(regex, 'i');

        return postcodeRegEx.test(postcode);

    },

    /** --------- Checks Address against Lookup --------- **/
    check_address: function (address, callback) {

        if(!address) {
            address = {
                line_1: $('.address_line_1').val(),
                line_2: $('.address_line_2').val(),
                postcode: $('.address_postcode').val(),
                country: $('.country_code').val(),
            };
        }

        var $addressBlockOriginal = $('.address-original-block');
        $addressBlockOriginal.html('');
        var originalAddress = '';
        originalAddress +=
            '<div class="col-xs-12 address_button__container">' +
            '<div class="address_button btn address_button_original" ' +
            'data-address-postcode="' + address.postcode + '"' +
            'data-address-line_1="' + address.line_1 + '"' +
            'data-address-line_2="' + address.line_2 + '"' +
            'data-is-original="1">';

        if (address.line_1.length > 0) {
            originalAddress += ' ' + address.line_1 + ', ';
        }
        if (address.line_2.length > 1) {
            originalAddress += ' ' + address.line_2 + ', ';
        }

        originalAddress += ' ' + address.postcode + '</div></div>';


        $addressBlockOriginal.append(originalAddress);
        $('.js-address-suggest').fadeIn('slow');
        $('.js-address-original').fadeIn('slow');

        return $.ajax({
            type: 'POST',
            url: CHECK_ADDRESS,
            cache: false,
            dataType: 'json',
            data: address,
            async: false,
            beforeSend: function () {

            },
            statusCode: {
                404: function () {

                    orders.reset_spinner();
                    orders.address_checked = false;

                    callback({
                        status: 404,
                        message: 'We could not find your address. Please check inputs and try again.'
                    });

                },
                400: function () {

                    orders.reset_spinner();
                    orders.address_checked = false;

                    callback({
                        status: 400,
                        message: 'We could not find your address. Please check inputs and try again.'
                    });

                },
                204: function () {

                    $('.js-address-suggest').hide();
                    $('.js-address-original').hide();

                    orders.reset_spinner();

                    callback({status: 204, message: 'Address found'});
                },
                200: function (res) {
                    var $addressBlock = $('.address-suggest-block');
                    $addressBlock.html(' ');
                    var item = '<div class="row">';
                    if (res.length > 0) {
                        $.each(res, function (index) {

                            var item = '<div class="col-xs-12 address_button__container"><div class="address_button btn" ' +
                                'data-address-postcode="' + res[index].postcode + '"' +
                                'data-address-line_1="' + res[index].line_1 + '"' +
                                'data-address-line_2="' + res[index].line_2 + '">';

                            if (res[index].line_1.length > 0) {
                                item = item + ' ' + res[index].line_1 + ', ';
                            }
                            if (res[index].line_2.length > 0) {
                                item = item + ' ' + res[index].line_2 + ', ';
                            }

                            item = item + ' ' + res[index].postcode + '</div></div>';

                            item = item + '</div>';
                            $addressBlock.append(item);

                            $('.js-address-suggest').fadeIn('slow');
                            $('.js-address-original').fadeIn('slow');

                        });
                    }

                    var $addresses = $addressBlock.children();
                    if ($addresses.length % 2 === 0 && $(window).width() > 768) {

                        $addresses.eq(0).addClass('first-border');
                        $addresses.eq(1).addClass('second-border');
                        $addresses.eq(-1).addClass('last-border');
                        $addresses.eq(-2).addClass('second-to-last-border');

                    } else if ($addresses.length % 2 === 1 && $(window).width() > 768) {

                        $addresses.eq(0).addClass('first-border');
                        $addresses.eq(1).addClass('second-border');
                        $addresses.eq(-2).addClass('second-to-last-border');
                        $addresses.eq(-1).addClass('last-border'); //switch

                    }
                    orders.reset_spinner();

                    callback({status: 200, message: 'Suggestions found'});

                }
            }
        });

    },

    /** --------- Gets Timeslots array and first available collection/delivery --------- **/
    get_timeslots: function() {

        if(window.location.href.indexOf('booking') === -1) {
            return;
        }

        if(!orders.check_postcode_regex($('#address_postcode').val(), $('#country_code').val())) {
            return;
        }

        if (!($('#address_postcode').val().length > 1)) {
            return;
        }


        if (orders.got_timeslots === false) {

            $('#timeslotsEditLink').hide();
            $('#timeslotsLoadingImg').show();

            hidePostcodeFeedback();
            hideTimeslots();

            var submit_btn = document.getElementById('booking_validate_submit');
            submit_btn.disabled = true;

            orders.timeslots_request = $.ajax({
                type: "POST",
                url: '/ajax/getTimeslots',
                cache: false,
                data: {
                    address_postcode: $('#address_postcode').val(),
                    country_code: $('#country_code').val()
                },
                beforeSend: function () {
                    if (orders.timeslots_request != null) {
                        orders.timeslots_request.abort();
                    }
                    NProgress.start();
                },
                statusCode: {
                    500: function() {
                        hideTimeslotsNoSearch();
                        showNoTimeslotsAvailableForPostcode();
                        orders.selected_merchant_available = false;
                        $('#booking_validate_submit').addClass('disabled');
                    }
                },
                success: function (timeslots_array) {

                    var timeslot_views = JSON.parse(timeslots_array);
                    orders.postcode_covered = true;
                    orders.postcode_checked = true;
                    orders.address_checked = true;
                    orders.selected_merchant_available = true;

                    var collectionHTML = timeslot_views[0].collection_view;
                    var deliveryHTML = timeslot_views[0].delivery_view;

                    var firstCollectionDate;
                    var firstCollectionTime;
                    var firstCollectionFee;
                    var firstCollectionDay = 0;
                    var firstCollectionExpiry;

                    var firstDeliveryDate;
                    var firstDeliveryFee;
                    var firstDeliveryTime;
                    var firstDeliveryDay = 0;
                    var firstDeliveryExpiry;

                    var firstCollectionString = timeslot_views[0].first_collection;
                    var firstDeliveryString = timeslot_views[0].first_delivery;

                    document.body.dataset.collection = timeslot_views[0].first_collection;
                    document.body.dataset.delivery = timeslot_views[0].first_delivery;


                    var unavailableTimes = [];

                    /* get number of available days from timeslot array */
                    var collectionDaysAvailable = 0;
                    var deliveryDaysAvailable = 0;
                    var collectionTodayAvailable, collectionTomorrowAvailable, numDaysToFirstCollection,
                        deliveryTodayAvailable, deliveryTomorrowAvailable, numDaysToFirstDelivery;
                    var collectionDaysAvailableList = [];
                    var deliveryDaysAvailableList = [];

                    if (timeslot_views[0].collection_dates[1].available === true) {
                        collectionTomorrowAvailable = true;
                    } else {
                        collectionTomorrowAvailable = false;
                    }

                    /* get first available timeslots!! */
                    for (var c = 0; c < timeslot_views[0].collection_dates.length; c++) {
                        var dayName = moment.utc(timeslot_views[0].collection_dates[c].value, "YYYY-MM-DD").format('dddd');
                        if (timeslot_views[0].collection_dates[c].available === true) {
                            collectionDaysAvailable++;
                            collectionDaysAvailableList.push(dayName);
                            if (typeof firstCollectionTime === "undefined") {
                                firstCollectionDate = timeslot_views[0].collection_dates[c].value;
                                for (var i = 0; i < timeslot_views[0].collection_dates[c].times.length; i++) {
                                    if (typeof firstCollectionTime === "undefined") {
                                        if (timeslot_views[0].collection_dates[c].times[i].available === true) {

                                            var firstCollectionDateTime = moment.utc(timeslot_views[0].collection_dates[c].value+' '+timeslot_views[0].collection_dates[c].times[i].value, 'YYYY-MM-DD HH:mm');
                                            // if first collection is before now, its not first collection
                                            var now = moment().format('YYYY-MM-DD HH:mm');
                                            if (firstCollectionDateTime.isAfter(moment.utc(now, 'YYYY-MM-DD HH:mm'))) {
                                                // if first collection expiry is within an hour of now, its not available
                                                if (moment.utc(new Date(), 'YYYY-MM-DD HH:mm').isBefore(moment.utc(timeslot_views[0].collection_dates[c].times[i].expiry, 'YYYY-MM-DD HH:mm').subtract(1, "hour"))) {
                                                    firstCollectionFee = timeslot_views[0].collection_dates[c].times[i].fee;
                                                    firstCollectionExpiry = timeslot_views[0].collection_dates[c].times[i].expiry;
                                                    firstCollectionTime = timeslot_views[0].collection_dates[c].times[i].value;
                                                    firstCollectionDay = c;
                                                } else {
                                                    var timestring = '#collection_' + firstCollectionDay + '_' + timeslot_views[0].collection_dates[c].times[i].value.substring(0, 2);
                                                    unavailableTimes.push(timestring);
                                                }
                                            } else {
                                                var timestring = '#collection_' + firstCollectionDay + '_' + timeslot_views[0].collection_dates[c].times[i].value.substring(0, 2);
                                                unavailableTimes.push(timestring);
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }


                    /* gets first available timeslots from order controller */
                    for (var d = 0; d < timeslot_views[0].delivery_dates.length; d++) {
                        var dayName = moment.utc(timeslot_views[0].delivery_dates[d].value, "YYYY-MM-DD").format('dddd');
                        if (timeslot_views[0].delivery_dates[d].available === true) {
                            deliveryDaysAvailable++;
                            deliveryDaysAvailableList.push(dayName);
                            if (typeof firstDeliveryTime === "undefined") {
                                firstDeliveryDate = timeslot_views[0].delivery_dates[d].value;
                                for (var i = 0; i < timeslot_views[0].delivery_dates[d].times.length; i++) {
                                    if (typeof firstDeliveryTime === "undefined") {
                                        if (timeslot_views[0].delivery_dates[d].times[i].available === true) {

                                            var firstDeliveryDateTime = moment.utc(timeslot_views[0].delivery_dates[d].value+' '+timeslot_views[0].delivery_dates[d].times[i].value);
                                            var now = moment().format('YYYY-MM-DD HH:mm');
                                            // if first delivery is before now, its not first delivery
                                            if( firstDeliveryDateTime.isAfter( moment.utc( now ) ) ) {
                                                // if first delivery expiry is within an hour of now, its not available
                                                if (moment.utc( now, 'YYYY-MM-DD HH:mm').isBefore(moment.utc(timeslot_views[0].delivery_dates[d].times[i].expiry, 'YYYY-MM-DD HH:mm').subtract(1, "hour"))) {
                                                    firstDeliveryTime = timeslot_views[0].delivery_dates[d].times[i].value;
                                                    firstDeliveryFee = timeslot_views[0].delivery_dates[d].times[i].fee;
                                                    firstDeliveryDay = d;
                                                    firstDeliveryExpiry = timeslot_views[0].delivery_dates[d].times[i].expiry;
                                                } else {
                                                    var timestring = '#delivery_' + firstDeliveryDay + '_' + timeslot_views[0].delivery_dates[d].times[i].value.substring(0, 2);
                                                    unavailableTimes.push(timestring);
                                                }
                                            } else {
                                                var timestring = '#delivery_' + firstDeliveryDay + '_' + timeslot_views[0].delivery_dates[d].times[i].value.substring(0, 2);
                                                unavailableTimes.push(timestring);
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }

                    var now = moment();
                    var firstD = moment.utc(firstDeliveryDate, "YYYY-MM-DD");
                    var firstC = moment.utc(firstCollectionDate, "YYYY-MM-DD");

                    numDaysToFirstDelivery = firstD.diff(now, "days") + 1;

                    if (timeslot_views[0].collection_dates[0].available === true) {
                        collectionTodayAvailable = true;
                        numDaysToFirstCollection = 0;
                    } else {
                        collectionTodayAvailable = false;
                        numDaysToFirstCollection = firstC.diff(now, "days") + 1;
                    }

                    /* Day Availability Data for Tracking */
                    mixpanelData['numCollectionDaysAvailable'] = collectionDaysAvailable;
                    mixpanelData['numDeliveryDaysAvailable'] = deliveryDaysAvailable;
                    mixpanelData['numDaysToFirstCollection'] = numDaysToFirstCollection;
                    mixpanelData['collectionTodayAvailable'] = collectionTodayAvailable;
                    mixpanelData['collectionTomorrowAvailable'] = collectionTomorrowAvailable;
                    mixpanelData['numDaysToFirstDelivery'] = numDaysToFirstDelivery;
                    mixpanelData['collectionDaysAvailableList'] = collectionDaysAvailableList;
                    mixpanelData['deliveryDaysAvailableList'] = deliveryDaysAvailableList;
                    mixpanelData['numDaysToFirstDelivery'] = numDaysToFirstDelivery;
                    mixpanelData['numDaysToFirstCollection'] = numDaysToFirstCollection;

                    orders.collection_date = firstCollectionDate;
                    orders.collection_time = firstCollectionTime;
                    orders.delivery_date = firstDeliveryDate;
                    orders.delivery_time = firstDeliveryTime;

                    /* set front end first available times for collection ... */
                    var collectionDateFormatted = moment.utc(firstCollectionDate, 'YYYY-MM-DD').format('ddd, MMM Do');
                    var collectionTimeFormatted = moment.utc(firstCollectionTime, 'HH:mm').format('HH:mm');
                    var collectionTimeEndFormatted = moment.utc(firstCollectionTime, 'HH:mm').add(1, "hours").format('HH:mm');

                    if (moment.utc(firstCollectionDate, 'YYYY-MM-DD').isSame(now, "day")) {
                        $('#booking__collection-date').text("Today, " + moment.utc(firstCollectionDate, 'YYYY-MM-DD').format('MMM Do'));
                    } else {
                        $('#booking__collection-date').text(collectionDateFormatted);
                    }
                    $('#booking__collection-slot-start').text(collectionTimeFormatted);
                    $('#booking__collection-slot-end').text(collectionTimeEndFormatted);

                    /* now for delivery .. */
                    var deliveryDateFormatted = moment.utc(firstDeliveryDate, 'YYYY-MM-DD').format('ddd, MMM Do');
                    var deliveryTimeFormatted = moment.utc(firstDeliveryTime, 'HH:mm').format('HH:mm');
                    var deliveryTimeEndFormatted = moment.utc(firstDeliveryTime, 'HH:mm').add(1, "hours").format('HH:mm');

                    // TODO:nia - check if its within 48 hours? although this wld be done in OrderController already
                    if (moment.utc(firstDeliveryDate, 'YYYY-MM-DD').isSame(now, "day")) {
                        $('#booking__delivery-date').text("Today, " + moment.utc(firstDeliveryDate, 'YYYY-MM-DD').format('MMM Do'));
                    } else {
                        $('#booking__delivery-date').text(deliveryDateFormatted);
                    }
                    $('#booking__delivery-slot-start').text(deliveryTimeFormatted);
                    $('#booking__delivery-slot-end').text(deliveryTimeEndFormatted);

                    var m = '';
                    if(window.isMobileDevice) {
                        m = '-mobile';
                    }
                    $('#collection_div_timeslots_inject'+m).html(collectionHTML);
                    $('#delivery_div_timeslots_inject'+m).html(deliveryHTML);

                    /* -- update delivery times list -- */
                    $('.deliveryDay').each(function () {
                        $(this).removeClass('active'); // unset all days
                    });

                    /* -- show the tab with the next available delivery slot -- */

                    if (document.body.dataset.usersetcollection === "TRUE") {
                        $('.deliveryDaysList li:nth-child(' + (numDaysToFirstDelivery + 1) + ')').addClass('active');
                    } else {
                        $('.deliveryDaysList li:first-child').addClass('active');
                    }

                    // find element in list
                    $('.address_button').each(function () {
                        $(this).removeClass('active'); // unset all other timeslots
                    });
                    $('#collection_' + firstCollectionDay + '_' + firstCollectionTime.substring(0, 2)).addClass('active');
                    $('#delivery_' + firstDeliveryDay + '_' + firstDeliveryTime.substring(0, 2)).addClass('active');

                    //$('#booking_timeslots-delivery-modal-content').show();
                    //$('#booking_timeslots-collection-modal-content').show();
                    $('.booking_timeslots-collection-modal-content').show();
                    $('.booking_timeslots-modal-loader').hide();
                    $('.modal-footer-row').css("display", "block");
                    submit_btn.disabled = false;
                    NProgress.done();
                    showTimeslots();
                    $('#timeslotsEditLink').show();
                    $('#timeslotsLoadingImg').hide();
                    $('#address_postcode').removeClass('input-loader');

                    if(firstCollectionFee !== '0.00' && firstCollectionFee !== '0' && firstCollectionFee !== null && firstCollectionFee !== '') {
                        $('#booking_collection_price').html(window.WebFunnel.currency_symbol+firstCollectionFee);
                        document.body.dataset.collectionfee = window.WebFunnel.currency_symbol+firstCollectionFee;
                    } else {
                        $('#booking_collection_price').html('');
                        document.body.dataset.collectionfee = '';
                    }

                    if(firstDeliveryFee !== '0.00' && firstDeliveryFee !== '0' && firstDeliveryFee !== null) {
                        $('#booking_delivery_price').html(window.WebFunnel.currency_symbol+firstDeliveryFee);
                        document.body.dataset.deliveryfee = window.WebFunnel.currency_symbol+firstDeliveryFee;
                    } else {
                        $('#booking_delivery_price').html('');
                        document.body.dataset.deliveryfee = '';
                    }

                    orders.got_timeslots = true;
                    orders.postcode_covered = true;
                    orders.postcode_checked = true;
                    submit_btn.removeAttribute('disabled');
                    $('#booking_validate_submit').prop("disabled", false);
                    $('#download-ical-wrap').css("display", "block");

                    unavailableTimes.forEach(function (timestring) {
                        $(timestring).removeClass('active');
                        $(timestring).addClass('time_disabled');
                        $(timestring).removeClass('time_available');
                    });

                    $('#booking_validate_submit').removeClass('disabled');

                },
                error: function (xhr) {
                    if(xhr.responseJSON !== undefined) {
                        if (xhr.responseJSON.error !== undefined) {
                            $('#booking_postcode_feedback').html(xhr.responseJSON.error);
                        }
                    }
                    orders.got_timeslots = false;
                }
            });
        }
    },

    /** --------- Store address in session --------- **/
    store_address: function() {

        var currentAddressRequest = $.ajax({
            type: "POST",
            url: '/ajax/address_store',
            cache: false,
            data: orders.full_address,
            beforeSend: function () {
                if (currentAddressRequest != null) {
                    currentAddressRequest.abort();
                }
            },
            success: function (res) {

            },
            error: function (xhr) {

            }
        });
    },


    init_submit_items: function () {

        if ($('.submit-box').length) {
            $('.submit-box .form-group').prepend('<div class="order-items"></div>');
        }
    },

    init_back_button: function () {

        if ($('.submit-box .back-group').length) {
            $('.submit-box .back-group').append('<span class="back-button btn btn-default pull-left">'+window.WebFunnel.back_text+'</span>');


            $('.back-button').on('click', function () {
                NProgress.start();
                var currentURL = window.location.href.substring(window.location.href.length-5);
                if(currentURL === "ntact") {
                    var newURL =  window.location.href.substring(window.location.href.length-7,0) + 'booking';
                    window.location.href = newURL;
                } else if(currentURL === "oking") {
                    var newURL =  window.location.href.substring(window.location.href.length-7,0) + 'items';
                    window.location.href = newURL;
                }

            })

        }
    },

    reset_spinner: function () {
        $('.btn-spinner').fadeOut(300, function () {
            $(this).remove();
        });
    },


    notify: function (type, message) {

        var title = {
            error: 'Oh no!',
            success: 'Success',
            info: 'Information'
        };

    },

    set_spinner: function (button) {
        $('.btn-spinner').fadeOut(300, function () {
            $(this).remove();
        });
        button.append(' <img src="' + window.WebFunnel.spinner + '" class="btn-spinner"/>');
    },

    check_if_delivery_changed: function (collection_datetime) {

        var delivery = $('ul.delivery_times li.active');

        if (delivery.length && delivery.data('date') != 'undefined') {

            var li_time = moment.utc(delivery.data('date') + ' ' + delivery.data('time'), 'YYYY-MM-DD HH:mm');

            //if (li_time.diff(collection_datetime, 'seconds') > (3600 * CONSTANT.HOURS_COLLECTION_TO_DELIVERY)) {
            if (li_time.diff(collection_datetime, 'seconds') > (3600 * 48)) {
                return false;
            }
        }

        return true;
    },
    set_earliest_delivery: function (collection_time, change_delivery) {

        var active = false;

        $('.delivery_times').each(function () {
            var settab = false;

            orders.reset_time('delivery');

            $(this).find('li').each(function () {

                var li_time = moment.utc($(this).data('date') + ' ' + $(this).data('time') + ':00', 'YYYY-MM-DD HH:mm:ss');

                if ($(this).data('available') == true) {

                    $(this).removeClass('time_disabled').removeClass('time_available');

                    //   if (li_time.diff(collection_time, 'seconds') >= (3600 * CONSTANT.HOURS_COLLECTION_TO_DELIVERY)) {
                    if (li_time.diff(collection_time, 'seconds') >= (3600 * 48)) {
                        $(this).addClass('time_available');

                        delivery_date = null;
                        delivery_time = null;
                        settab = true;
                    } else {
                        $(this).addClass('time_disabled')
                    }
                }

            });

            if (!active && settab && change_delivery) {
                var tab_id = $(this).closest('.tab-pane').attr('id');
                $('.delivery_timeslots .nav-tabs a[href=#' + tab_id + ']').tab('show');
                active = true;
            }

        });
    },

    set_time: function (type, time) {

        time = moment.utc(time);

        var current = $('.' + type + '_times li[data-date="' + time.format('YYYY-MM-DD') + '"][data-time="' + time.format('HH') + ':00"]');

        if (current.length && current.hasClass('time_available') && current.data('available') == true) {

            var tab_id = current.closest('.tab-pane').attr('id');
            $('.block_' + tab_id).tab('show');

            current.removeClass('time_disabled').click();

        }

    },
    write_time: function (type, datetime, fee) {

        var date = datetime.format('YYYY-MM-DD HH:mm:00');

        $.ajax({
            type: 'POST',
            url: BASE_URL + '/storeTimeslots',
            cache: false,

            data: { date : date,
                type : type,
                fee  : fee },

            success: function (timeslots) {

                mixpanelData['preselected_delivery'] = timeslots.delivery;

                if(timeslots.collection != "null") {
                    $('#download-ical-wrap').css("display", "block");
                }
                document.body.dataset.userselection = "TRUE";

                if(type === 'collection') {
                    document.body.dataset.collection = timeslots.collection;
                    document.body.dataset.collectionfee = timeslots.collection_fee;
                }
                if(type === 'delivery') {
                    document.body.dataset.delivery = timeslots.delivery;
                    document.body.dataset.deliveryfee = timeslots.delivery_fee;
                }

                orders.cart_get();
                orders.reset_spinner();
            }
        });

        $('.' + type + '_input').val(datetime.format('YYYY-MM-DD HH:mm:ss'));
        $('.time_' + type).html(datetime.format('H:mm') + ' - ' + datetime.add(1, 'hour').format('H:mm') + ' ' + datetime.format('dddd, MMMM Do'));

    },
    reset_time: function (type) {
        var field = $('.time_' + type);
        field.html(field.data('default'));
    },

    cart_get: function () {

        $('.cart-checkout').css('opacity','0');
        $('#cart-loader').show();

        $.ajax({
            type: 'POST',
            url: BASE_URL + '/cart',
            cache: false,
            dataType: 'json',
            data: {page: PAGE},
            success: function (res) {

                orders.cart_load(res);

                orders.reset_spinner();

                $('.cart-checkout').css('opacity','1');
                $('#cart-loader').hide();

                if(res.message != "Cart loaded") {
                    $('#funnel-error-text').text(res.message);
                }
            },
            error: function(res) {

            }
        });
    },

    cart_load: function (res) {

        var orderItems = $('.order-items');
        var cartSumary = $('.cart-summary');
        var body = $('body');

        var subtotal; // calculated subtotal (with minimum order value / minimum voucher order value if relevant)
        var cartValue; // cost of all items in the cart (w/no discounts applied)
        var total; // the actual total, that will be displayed & charged

        var cartSubtotal; // cost of all items in cart
        var minimumTotal;

        $('.product-cart').css("opacity","1");

        if (orderItems.length) {
            if (body.is('.body_items, .body_address, .body_booking, .body_contact')) {

                if (body.hasClass('body_items')) {
                    if (res.count) {
                        orderItems.html(res.formatted);
                    } else {
                        orderItems.html(res.default);
                    }

                    if (res.count === 0) {
                        orders.cart_button(window.WebFunnel.skip_item_selection);
                        $('.continue_button').addClass('btn-laundrapp-blue-no-items').removeClass('btn-laundrapp-blue-items');
                        $('.footer__cart--cost').addClass('footer__cart--cost-items');
                    } else {
                        orders.cart_button(window.WebFunnel.your_basket);
                        $('.continue_button').addClass('btn-laundrapp-blue-items').removeClass('btn-laundrapp-blue-no-items');
                    }

                } else {
                    orderItems.html(res.formatted);
                }

            }
        }

        var keys = Object.keys(res.content);
        if (keys.length > 0) {
            cartSumary.show();
        } else {
            cartSumary.hide();
        }

        var updatedItems = [];
        $.each(res.content, function (k, v) {
            updatedItems.push(v);
        });
        CustomerAllowance.calculateRemainingAllowances(updatedItems);

        if (cartSumary.length > 0) {

            cartSumary.html(' ');

            $.each(res.content, function (k, v) {

                cartSumary.append(
                    '<li class="cart-summary-li row cart-item" ' +
                    'data-quantity="'+v.qty+'" data-price="'+v.price+'" data-id="'+v.id+'" >' +
                    '<div class="hidden-xs col-sm-1 no-pad"><img src="' + v.options.image + '" class="cart-summary-img img-circle"/></div>' +
                    '<span class="col-xs-1 col-sm-2 col-lg-2 no-pad qty">' + v.qty + 'x</span>' +
                    '<p class="col-xs-7 col-sm-6 no-pad name">' + v.name + '</p>' +
                    '<span class="col-xs-3 col-sm-2 price">'+ Currency.format(res.currency, (v.price * v.qty).toFixed(2)) + '</span>' +
                    '<span class="col-xs-1 no-pad cart_remove cp remove webicon" data-id="' + v.id + '">Z</span></li>');


            });

            criteoBasketTracker();
        }

        if ($('.product-block').length) {

            $('.product-block .badge').hide();
            $('.product-block .cart_remove').removeClass('hasItems');

            $.each(res.content, function (k, v) {

                $('.product-' + v.id + ' .cart_number').html(v.qty).show();
                $('.product-' + v.id + ' .cart_remove').addClass('hasItems');

            });
        }


        $('.cart-checkout .coupon').hide();
        $('.subtotal-minimum .amount').html("");
        $('.subtotal-minimum').hide();
        $('.checkout-fee').hide();
        $('.total-minimum').hide();
        $('.subtotal').css('border-bottom-width', '1px');

        $('.continue_button').removeAttr('disabled');

        var realTotalAmount = res.cartValue;//res.realtotal;
        var minimum = res.minimum;

        /* -- if product prices / availability changes -- */
        if (res.update.updated > 0 || res.update.removed > 0) {
            $.get("/order_product_change", function (data) {
                $('#items_flash_text').css("display","block");
            });
        }

        /* -- cart subtotal -- */
        cartSubtotal = res.cartValue;
        $('.cart-price').html(Currency.format(res.currency, parseFloat(res.cartValue).toFixed(2)));

        total = res.total;

        /* -- checks for fees -- */
        if (res.fee > 0) {
            $('.checkout-fee-total').html(Currency.format(res.currency, res.fee));
            $('.checkout-fee').show();
        } else {
            $('.checkout-fee').hide();
        }

        $('.checkout-transaction-fee').hide();
        $('.waive-fee-container').hide();
        $('.waive-fee-threshold').hide();
        if (!res.waiveFees) {
            if (res.transactionFee > 0) {
                $('.checkout-transaction-fee-total').html(Currency.format(res.currency, res.transactionFee));
                $('.checkout-transaction-fee').show();
            }

            if (parseFloat(res.waiveFeeThreshold) > 0) {
                $('.waive-fee-container').show();
            }

            $('.waive-fee-threshold').html(Currency.format(res.currency, res.waiveFeeThreshold));
            $('.waive-fee-threshold').show();
        }

        if (parseInt(res.waiveFeeThreshold) <= 0) {
            $('.waive-fee').hide();
            $('#waive-fee-value').hide();
            $('.checkout-transaction-fee').css('padding', '18px 0px');
            $('.checkout-transaction-fee-total').css('top', '0px');
        }

        /* -- check minimum amount -- */
        // if cartSubtotal < minimum, charge the minimum
        minimum = res.minimum;
        var minimumShow = false;
        var voucherMinimumShow = false;

        if (res.allowanceDiscount) {
            $('.cart-checkout .allowance').html('Pre-Paid: <span class="pull-right">-'+ LOCALE_CURRENCY + res.allowanceDiscount.toFixed(2) +'</span>');
            $('.cart-checkout .allowance').show();
        } else {
            $('.cart-checkout .allowance').hide();
        }

        /* - check for voucher, use this minimum order value if it exists - */
        if (res.user.voucher.length > 0 && typeof res.user.voucher_order_value != 'undefined') {

            minimum = res.user.voucher_order_value; // set minimum to voucher miniumm

            voucher_amount = res.voucherDiscount;

            $('.cart-checkout .coupon').html(res.user.voucher_title + '<span class="webicon pull-right remove_voucher cp">Z</span> ' +
            '<span class="cart-voucher pull-right">-'+ Currency.format(res.currency, voucher_amount.toFixed(2)) + '</span>');
            $('.cart-checkout .coupon').show();

            /* if subtotal < voucher min order, show voucher min order */
            if(cartSubtotal < minimum) {
                voucherMinimumShow = true;
            }

        } else {

            $('.cart-checkout .coupon').hide();
            $('.js-voucher-add').removeClass('hide');
        }

        // if order total is less than minimum
        if ( parseInt(minimum) > parseInt(res.cartValue) ) {
            minimumShow = true;
            minimumTotal = minimum;
        }

        // print new minimum to screen
        $('.subtotal-minimum-brackets').html(Currency.format(res.currency, minimum));
        $('.subtotal-minimum .amount').html(Currency.format(res.currency, parseFloat(minimum).toFixed(2)));
        $('.total-minimum .amount').html(Currency.format(res.currency, minimum));

        if (minimumShow) {
            $('.subtotal-minimum').show();
            $('.subtotal').css('border-bottom', '0px');

            $('.cart-checkout .total').show();
            $('.cart-checkout .subtotal').show();

            $('.subtotal').hide();

        } else {
            $('.subtotal').show();
        }

        if(voucherMinimumShow) {
            $('.total-minimum').css("display","block");
        }

        if (res.count === 0 && !body.hasClass('body_contact')) {
            orders.cart_button(window.WebFunnel.skip_item_selection);
        }

        if (body.hasClass('body_booking')) {
            orders.cart_button(window.WebFunnel.place_order);
        }

        calculatedTotal = total;

        /* if total is under min and there is no voucher applied, they will pay the minimum */
        if((res.subtotal < minimum) && !(res.user.voucher.length > 0)) {
            calculatedTotal = minimum;

            // not nice, as this whole minimum value checking should be done server side. Refactor
            if (!res.waiveFees) {
                if (res.transactionFee > 0) {
                    calculatedTotal = parseFloat(calculatedTotal) + parseFloat(res.transactionFee);
                }
            }
        }

        $('.cart-total').html(Currency.format(res.currency, parseFloat(calculatedTotal).toFixed(2)));

        $('.product .cart_number').each(function (i, el) {

            if (el.innerHTML.length === 0) {
                $(el).addClass('hidden');
                $(el).siblings('.cart_remove').addClass('hidden');
            }
        });

        if(body.hasClass('body_contact')) {
            orders.cart_button(window.WebFunnel.complete_booking);
        }


    },
    cart_remove: function (productId, qty) {

        var numItems = parseInt($('.product-' + productId + ' .cart_number').html());
        qty = qty || 1;

        if (+$('.product-' + productId + ' .cart_number').html() === 1) {
            $('.product-' + productId + ' .cart_number').addClass('hidden');
            $('.product-' + productId + ' .cart_remove').addClass('hidden');
            $('.product-' + productId + ' .cart_number').html("");
        } else {
            $('.product-' + productId + ' .cart_number').html( (numItems-1) );
        }

        var item = {
            id: productId,
            qty: qty
        };

        $.ajax({
            type: 'POST',
            url: BASE_URL + '/cart/remove',
            cache: false,
            dataType: 'json',
            data: item,
            beforeSend: function() {
                shield();
            },
            error: function () {

                orders.log('remove from cart error');
                $('.product-' + productId + ' .cart_number').html( (numItems) ); // change it back if remove doesnt work
                unshield();
            },
            success: function (res) {

                orders.cart_load(res);

                if (!res.count) {
                    orders.cart_button(window.WebFunnel.skip_item_selection);
                    $('.footer__cart--cost').removeClass('footer__cart--cost-items');
                } else {
                    $('.footer__cart--cost').addClass('footer__cart--cost-items');
                }
                unshield();
                orders.reset_spinner();

            }
        });
    },

    cart_add: function (productId, priceId, qty) {

        $('.product-' + productId + ' .cart_number').removeClass('hidden');
        $('.product-' + productId + ' .cart_remove').removeClass('hidden');

        var numItems = parseInt($('.product-' + productId + ' .cart_number').html());
        if(isNaN(numItems)) {
            $('.product-' + productId + ' .cart_number').html("1");
        } else {
            $('.product-' + productId + ' .cart_number').html( (numItems+1) );
        }

        qty = qty || 1;

        var item = {
            id: productId,
            priceId: priceId,
            qty: qty
        };

        $.ajax({
            type: 'POST',
            url: BASE_URL + '/cart/add',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                shield();

            },
            data: item,
            error: function () {

                orders.log('add to cart error');
                $('.product-' + productId + ' .cart_number').html( (numItems) ); // change it back if addtocart doesnt work

                unshield();
            },
            success: function (res) {

                orders.cart_load(res);

                orders.reset_spinner();
                $('#clickShield').css('z-index', '-800');

                if (!res.count) {
                    orders.cart_button(window.WebFunnel.skip_item_selection);
                } else {
                    $('.footer__cart--cost').addClass('footer__cart--cost-items');
                }
                unshield();

                if(IS_LAUNDRAPP) {
                    /* -- fire criteo product tracker -- */
                    if (!($('#user-info').html().trim().length === 0)) {
                        userInfo = JSON.parse($('#user-info').html());
                    }

                    /* -- fire criteo product tracker -- */
                    if ($('#meta-is-mobile')) {
                        if ($('#meta-is-tablet').length > 0) {
                            window.criteo_q = window.criteo_q || [];
                            window.criteo_q.push(
                                {event: "setAccount", account: 30270},
                                {event: "setHashedEmail", email: userInfo['hashed_email']},
                                {event: "setSiteType", type: "t"},
                                {event: "viewItem", item: productId}
                            );
                        } else {
                            window.criteo_q = window.criteo_q || [];
                            window.criteo_q.push(
                                {event: "setAccount", account: 30270},
                                {event: "setHashedEmail", email: userInfo['hashed_email']},
                                {event: "setSiteType", type: "m"},
                                {event: "viewItem", item: productId}
                            );
                        }
                    } else {
                        window.criteo_q = window.criteo_q || [];
                        window.criteo_q.push(
                            {event: "setAccount", account: 30270},
                            {event: "setHashedEmail", email: userInfo['hashed_email']},
                            {event: "setSiteType", type: "d"},
                            {event: "viewItem", item: productId}
                        );
                    }
                }
            }
        });
    },

    cart_button: function (msg) {
        $('.continue_button').html(msg);
    },

    validate_booking: function() {

        if(orders.selected_merchant_available === false) {
            return;
        }

        var address = {
            line_1: $('.address_line_1').val(),
            line_2: $('.address_line_2').val(),
            postcode: $('.address_postcode').val(),
            country_code: $('.country_code').val()
        };

        if(orders.collection_date === null) {
            orders.collection_date = $('#session_8432').data("collection");
        }

        if(orders.delivery_date === null) {
            orders.delivery_date = $('#session_8432').data("delivery");
        }

        var timeslots = {
            collection_date : orders.collection_date,
            collection_time : orders.collection_time,
            delivery_date   : orders.delivery_date,
            delivery_time   : orders.delivery_time
        };

        var booking_data = {
            address_data         : address,
            timeslots_data       : timeslots,
            delivery_notes       : $('#delivery_notes').val(),
            special_instructions : $('.special_instructions').val(),
            collection_fee       : $('.collection_fee').val(),
            collection_time      : moment.utc(orders.collection_date + ' ' + orders.collection_time+':00', 'YYYY-MM-DD HH:mm:ss').format("YYYY-MM-DD HH:mm:ss"),
            collection_expiry    : $('.collection_expiry').val(),
            delivery_fee         : $('.delivery_fee').val(),
            delivery_time        :  moment.utc(orders.delivery_date + ' ' + orders.delivery_time+':00', 'YYYY-MM-DD HH:mm:ss').format("YYYY-MM-DD HH:mm:ss"),
            delivery_expiry      : $('.delivery_expiry').val(),
            initial_total        : $('.cart-total').text().substr(1)
        };

        var readyToSubmit = false;
        //** here we are going to check that the address is non-empty **//
        if($('.address_line_1').val().replace(/\s/g,'').length === 0) {
            showAddressInput();
            pleaseEnterAValidLine1();
            readyToSubmit = false;
        } else {
            readyToSubmit = true;
        }

        // alternative check: !str.replace(/\s/g, '').length
        if(!$('.address_postcode').val().replace(/\s/g,'').length) {
            showAddressInput();
            pleaseEnterAValidPostcode();
            readyToSubmit = false;
        } else {
            readyToSubmit = true;
        }

        // if enough address info entered, we can lookup suggested addresses
        if(readyToSubmit) {

            if(IS_LAUNDRAPP) {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'WebOrderFunnel',
                    eventAction: 'ValidateBooking',
                    eventLabel: $('.cart-total').text()
                });
            }

            orders.check_address(null, function (res) {

                if(IS_LAUNDRAPP) {
                    mixpanel.track("Order Button Pressed", {
                        'Address Status' : res.status
                    });
                }

                if (orders.is_original_address || res.status == 204) {
                    // empty response = address is fine
                    orders.address_checked = true;

                    if (orders.collection_date != null && orders.delivery_date != null) {

                        var validationRequest = $.ajax({
                            type: "POST",
                            url: BASE_URL + '/ajax/booking',
                            cache: false,
                            dataType: 'json',
                            data: booking_data,
                            beforeSend: function () {
                                if (validationRequest != null)
                                    validationRequest.abort()
                            },
                            success: function (response) {

                                if(IS_LAUNDRAPP) {
                                    ga('send', {
                                        hitType: 'event',
                                        eventCategory: 'WebOrderFunnel',
                                        eventAction: 'Booking Validated',
                                        eventLabel: $('.cart-total').text()
                                    });

                                    mixpanel.track("Address Confirmed", {
                                        'Had Error' : false
                                    });
                                }

                                if (response[0].status == 'success') {
                                    window.location.href = response[0].redirect;
                                }

                            },
                            error: function (xhr) {
                                if(IS_LAUNDRAPP) {
                                    mixpanel.track("Address Confirmed", {
                                        'Had Error' : true,
                                        'Error' : xhr
                                    });
                                }
                            }
                        });
                    } else {
                        pleaseSelectCollectionAndDeliveryTimes();
                    }
                } else if (res.status === 200) {

                    // normal expected response
                    orders.address_checked = true;
                    orders.address = true;
                    $('#address-suggest-row').show();
                    $('#address-original-row').show();
                    $('.js-address-suggest').css("margin", "0");
                    $('.js-address-original').css("margin", "0");

                    showAddressModal();
                    NProgress.done();

                } else if(res.status === 404) {

                    /* address not found */
                    orders.address_checked = false;
                    pleaseEnterAValidAddress();
                }
            });
        }

    },

    log: function (x) {

    },

    selected_merchant_available: true

};

function showAddressModalMobile() {
    $('#didYouMeanAddressModal-mobile').addClass('address-modal-open');
    $('body').addClass('body-address-modal-open');
    $('html, body').animate({scrollTop : 0},100);
}

function hideAddressModalMobile() {
    $('#didYouMeanAddressModal-mobile').removeClass('address-modal-open');
    $('body').removeClass('body-address-modal-open');
}

function showAddressModal() {
    if(window.isMobileDevice) {
        showAddressModalMobile();
    } else {
        $('#didYouMeanAddressModal').modal('show');
    }
}

function hideAddressModal() {
    if(window.isMobileDevice) {
        hideAddressModalMobile();
    } else {
        $('#didYouMeanAddressModal').modal('hide');
    }
}

function showTimeslotsModal() {
    if(window.showTimeslot == 'collection') {
        showCollectionModal();
    } else {
        showDeliveryModal();
    }
}

function showCollectionModal() {
    if(window.isMobileDevice) {
        showCollectionModalMobile();
    } else {
        $('#collectionTimesSelectModal').modal('show');
    }
}

function showDeliveryModal() {
    if(window.isMobileDevice) {
        showDeliveryModalMobile();
    } else {
        $('#deliveryTimesSelectModal').on('shown.bs.modal', function() {

            setTimeout(function() {
                setDeliveryDay();
            }, 300);

        }).modal('show');
    }
}


function showCollectionModalMobile() {
    $('#timeslots-modal_collection').fadeIn("medium", function() {
        $(this).addClass('timeslots-modal-open');
        $('body').addClass('body-timeslots-modal-open');
        $('html, body').animate({scrollTop : 0},100);
    });
}

function showDeliveryModalMobile() {
    $('#timeslots-modal_delivery').fadeIn("medium", function() {
        $(this).addClass('timeslots-modal-open');
        $('body').addClass('body-timeslots-modal-open');
        $('html, body').animate({scrollTop : 0},100);

        setTimeout(function() {
            setDeliveryDay();
        }, 300);
    });
}

function hideCollectionModalMobile() {
    $('#timeslots-modal_collection').fadeOut("medium", function() {
        $('this').removeClass('timeslots-modal-open');
        $('body').removeClass('body-timeslots-modal-open');
    });
}

function hideDeliveryModalMobile() {
    $('#timeslots-modal_delivery').fadeOut("medium", function() {
        $(this).removeClass('timeslots-modal-open');
        $('body').removeClass('body-timeslots-modal-open');
    });

}

$('#collectionTimesSelectModal').on('show.bs.modal', function (e) {
    if(window.isMobileDevice) {
        return e.preventDefault() // stops modal from being shown
    }
});

$('#deliveryTimesSelectModal').on('show.bs.modal', function (e) {
    if(window.isMobileDevice) {
        return e.preventDefault() // stops modal from being shown
    }
});

/** ------------------ Helper Functions ------------------ **/

var shield = function() {
    $('#clickShield').css("z-index","900");
};

var unshield = function() {
    $('#clickShield').css("z-index","-900");
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};