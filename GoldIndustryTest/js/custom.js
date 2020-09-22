jQuery(document).ready(function ($) {
  dc_menu_login_dialog();
  register_on_home();
  login_on_home();

  $("#jobStatTable").DataTable();
  $("#applicationRecordsTable").DataTable({
    order: [[2, "desc"]],
  });

  $(".applyCountBtn").on("click", function () {
    var jobID = $(this).attr("data-job");
    var time = moment().format("h:mm a");

    $.ajax({
      type: "POST",
      url: CUSTOM_PARAMS.ajax_url,
      data: {
        action: "add_applyCount_ajax",
        jobID: jobID,
        time: time,
      },
      dataType: "JSON",
      success: function (results) {
        //console.log(results);
      },
    });
  });

  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      //console.log('event' , event.wpcf7mailsent.detail.status);
      var jobID = $("#apply-dialog").attr("data-job");
      var time = moment().format("h:mm a");
      if (jobID) {
        $.ajax({
          type: "POST",
          url: CUSTOM_PARAMS.ajax_url,
          data: {
            action: "add_applyCount_ajax",
            jobID: jobID,
            time: time,
          },
          dataType: "JSON",
          success: function (results) {
            //console.log(results);
          },
        });
      }
    },
    false
  );

  if (jQuery("body").hasClass("home")) {
    jQuery(
      "body.home .intro-banner-search-form .intro-search-field #intro-keywords"
    ).attr("placeholder", "Job title or Skill");
    jQuery(
      "body.home .intro-banner-search-form .intro-search-field #search_location"
    ).attr("placeholder", "City or State");
  }

  jQuery("#signup-dialog .small-dialog-headline h2").html(
    "Sign up as a Job Seeker"
  );

  change_fontawesome_icons();

  // 	$(".vc_carousel-slideline-inner").slick({
  //         slidesToShow: 5, // default desktop values
  //         slidesToScroll: 1,
  //         rows: 1,
  //         dots: false,
  //         arrows: true,
  //         responsive: [
  //             {
  //                 breakpoint: 980, // tablet breakpoint
  //                 settings: {
  //                     slidesToShow: 3,
  //                     slidesToScroll: 3
  //                 }
  //             },
  //             {
  //                 breakpoint: 480, // mobile breakpoint
  //                 settings: {
  //                     slidesToShow: 2,
  //                     slidesToScroll: 2
  //                 }
  //             }
  //         ]
  //     });
});

function dc_menu_login_dialog() {
  jQuery('.new-header #navigation > ul li a[href="#login-dialog"]').click(
    function (e) {
      e.preventDefault();
      jQuery(
        '.new-header #header .right-side .header-widget .login-register-buttons a[href="#login-dialog"]'
      ).click();
    }
  );

  jQuery(document).on(
    "touchstart click",
    '.mm-listitem__text[href="#login-dialog"]',
    function (event) {
      //event.preventDefault();

      var mmenuAPI = $(".mmenu-init").data("mmenu");

      jQuery(
        '.new-header #header .right-side .header-widget .login-register-buttons a[href="#login-dialog"]'
      ).trigger("click");
      jQuery(
        '.new-header #header .right-side .header-widget .login-register-buttons a[href="#login-dialog"]'
      ).trigger("touchstart");

      mmenuAPI.close();
    }
  );

  jQuery(document).on(
    "touchstart click",
    '.mm-listitem__text[href="#signup-dialog"]',
    function (event) {
      //event.preventDefault();

      var mmenuAPI = $(".mmenu-init").data("mmenu");

      jQuery(
        '.new-header #header .right-side .header-widget .login-register-buttons a[href="#signup-dialog"]'
      ).trigger("click");
      jQuery(
        '.new-header #header .right-side .header-widget .login-register-buttons a[href="#signup-dialog"]'
      ).trigger("touchstart");

      mmenuAPI.close();
    }
  );
}

function register_on_home() {
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("action") && urlParams.get("action") == "register") {
    jQuery(
      '.new-header #header .right-side .header-widget .login-register-buttons a[href="#signup-dialog"]'
    ).click();
  }
}

function login_on_home() {
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("action") && urlParams.get("action") == "login") {
    jQuery(
      '.new-header #header .right-side .header-widget .login-register-buttons a[href="#login-dialog"]'
    ).click();
  }
}

function change_fontawesome_icons() {
  //Manage Job Alerts
  jQuery("table.manage-table td.action a.job-alerts-action-view").html(
    '<i class="fas fa-check"></i> Show Results'
  );
  jQuery("table.manage-table td.action a.job-alerts-action-edit").html(
    '<i class="fas fa-pencil-alt"></i> Edit'
  );

  //Manage Jobs
  jQuery("table.manage-table td.action a.job-dashboard-action-edit").html(
    '<i class="fas fa-pencil-alt"></i> Edit'
  );
  jQuery("table.manage-table th.filled").html(
    '<i class="fas fa-check-square"></i> Filled?'
  );
}

function check() {
  if (jQuery(document).width() < 400 && myVar === "1") {
    // use `===` and no quote around 783
    console.log("y");
    jQuery(".leaflet-control-zoom-out").trigger("click");
  }
}

check();

jQuery(window).resize(function () {
  check();
});

/* Search Jobs, Company Filter Alphabetical Order */


jQuery(document).ready(function ($) {
	var options = $('#company_name > option');
	var arr = options.map(function (_, o) { return { t: $(o).text(), v: o.value }; }).get();
	arr.sort(function (o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
	options.each(function (i, o) {
		o.value = arr[i].v;
		$(o).text(arr[i].t);
	});
}