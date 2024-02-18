(function ($) {
  "use strict";

  // ______________ PAGE LOADING
  $(window).on("load", function (e) {
    $("#global-loader").fadeOut("slow");
  });

  //Color-Theme
  $(document).on("click", "a[data-theme]", function () {
    $("head link#theme").attr("href", $(this).data("theme"));
    $(this).toggleClass("active").siblings().removeClass("active");
  });

  // FAQ Accordion
  $(document).on("click", '[data-bs-toggle="collapse"]', function () {
    $(this).toggleClass("active").siblings().removeClass("active");
  });

  // ______________Full screen
  $(document).on("click", ".fullscreen-button", function toggleFullScreen() {
    $(".fullscreen-button").addClass("fullscreen-button");
    if (
      (document.fullScreenElement !== undefined &&
        document.fullScreenElement === null) ||
      (document.msFullscreenElement !== undefined &&
        document.msFullscreenElement === null) ||
      (document.mozFullScreen !== undefined && !document.mozFullScreen) ||
      (document.webkitIsFullScreen !== undefined &&
        !document.webkitIsFullScreen)
    ) {
      if (document.documentElement.requestFullScreen) {
        document.documentElement.requestFullScreen();
      } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullScreen) {
        document.documentElement.webkitRequestFullScreen(
          Element.ALLOW_KEYBOARD_INPUT
        );
      } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
      }
    } else {
      $("html").removeClass("fullscreen-button");
      if (document.cancelFullScreen) {
        document.cancelFullScreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      }
    }
  });

  // ______________ BACK TO TOP BUTTON
  $(window).on("scroll", function (e) {
    if ($(this).scrollTop() > 0) {
      $("#back-to-top").fadeIn("slow");
    } else {
      $("#back-to-top").fadeOut("slow");
    }
  });
  $(document).on("click", "#back-to-top", function (e) {
    $("html").animate(
      {
        scrollTop: 0,
      },
      0
    );
    return false;
  });

  // ______________ COVER IMAGE
  $(".cover-image").each(function () {
    var attr = $(this).attr("data-bs-image-src");
    if (typeof attr !== typeof undefined && attr !== false) {
      $(this).css("background", "url(" + attr + ") center center");
    }
  });

 

  // ______________Chart-circle
  if ($(".chart-circle").length) {
    $(".chart-circle").each(function () {
      let $this = $(this);
      $this.circleProgress({
        fill: {
          color: $this.attr("data-bs-color"),
        },
        size: $this.height(),
        startAngle: (-Math.PI / 4) * 2,
        emptyFill: "rgba(119, 119, 142, 0.2)",
        lineCap: "round",
      });
    });
  }

  // __________MODAL
  // showing modal with effect
  $(".modal-effect").on("click", function (e) {
    e.preventDefault();
    var effect = $(this).attr("data-bs-effect");
    $("#modaldemo8").addClass(effect);
  });
  // hide modal with effect
  $("#modaldemo8").on("hidden.bs.modal", function (e) {
    $(this).removeClass(function (index, className) {
      return (className.match(/(^|\s)effect-\S+/g) || []).join(" ");
    });
  });

  // ______________ CARD
  const DIV_CARD = "div.card";

  // ___________TOOLTIP
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // __________POPOVER
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // ______________ FUNCTION FOR REMOVE CARD
  $(document).on("click", '[data-bs-toggle="card-remove"]', function (e) {
    let $card = $(this).closest(DIV_CARD);
    $card.remove();
    e.preventDefault();
    return false;
  });

  // ______________ FUNCTIONS FOR COLLAPSED CARD
  $(document).on("click", '[data-bs-toggle="card-collapse"]', function (e) {
    let $card = $(this).closest(DIV_CARD);
    $card.toggleClass("card-collapsed");
    e.preventDefault();
    return false;
  });

  // ______________ CARD FULL SCREEN
  $(document).on("click", '[data-bs-toggle="card-fullscreen"]', function (e) {
    let $card = $(this).closest(DIV_CARD);
    $card.toggleClass("card-fullscreen").removeClass("card-collapsed");
    e.preventDefault();
    return false;
  });

  //Input file-browser
  $(document).on("change", ".file-browserinput", function () {
    var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, "/").replace(/.*\//, "");
    input.trigger("fileselect", [numFiles, label]);
  }); // We can watch for our custom `fileselect` event like this

  //______File Upload
  $(".file-browserinput").on("fileselect", function (event, numFiles, label) {
    var input = $(this).parents(".input-group").find(":text"),
      log = numFiles > 1 ? numFiles + " files selected" : label;
    if (input.length) {
      input.val(log);
    } else {
      if (log) alert(log);
    }
  });

  function replay() {
    let replayButtom = document.querySelectorAll(".reply a");
    // Creating Div
    let Div = document.createElement("div");
    Div.setAttribute("class", "comment mt-5 d-grid");
    // creating textarea
    let textArea = document.createElement("textarea");
    textArea.setAttribute("class", "form-control");
    textArea.setAttribute("rows", "5");
    textArea.innerText = "Your Comment";
    // creating Cancel buttons
    let cancelButton = document.createElement("button");
    cancelButton.setAttribute("class", "btn btn-danger");
    cancelButton.innerText = "Cancel";

    let buttonDiv = document.createElement("div");
    buttonDiv.setAttribute("class", "btn-list ms-auto mt-2");

    // Creating submit button
    let submitButton = document.createElement("button");
    submitButton.setAttribute("class", "btn btn-success ms-3");
    submitButton.innerText = "Submit";

    // appending text are to div
    Div.append(textArea);
    Div.append(buttonDiv);
    buttonDiv.append(cancelButton);
    buttonDiv.append(submitButton);

    replayButtom.forEach((element, index) => {
      element.addEventListener("click", () => {
        let replay = $(element).parent();
        replay.append(Div);

        cancelButton.addEventListener("click", () => {
          Div.remove();
        });
      });
    });
  }
  replay();

  $(document).on("click", "a[data-sidetheme]", function () {
    $("head link#sidemenu-theme").attr("href", $(this).data("sidetheme"));
  });

  /*Theme-layout*/

  //  // Add slideDown animation to Bootstrap dropdown when expanding.
  // $('.dropdown').on('show.bs.dropdown', function() {
  //   $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
  // });

  // // Add slideUp animation to Bootstrap dropdown when collapsing.
  // $('.dropdown').on('hide.bs.dropdown', function() {
  //   $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
  // });
})(jQuery);



// FLAT BOOKING JS 

var price = 12000; //price
$(document).ready(function () {
    var $cart = $('#selected-flats'), //Sitting Area
        $counter = $('#counter'), //Votes
        $total = $('#total'); //Total money
        if (document.querySelector("#flat-map")) {

          var sc = $('#flat-map').flatCharts({
              map: [ //flat chart design
                  '_aaaaaa_',
                  'aaaaaaaa',
                  '________',
                  '_aaaaaa_',
                  'aaaaaaaa',
              ],
              naming: {
                  top: false,
                  getLabel: function (character, row, column) {
                      return column;
                  }
              },
              legend: { //Definition legend
                  node: $('#legend'),
                  items: [
                      ['a', 'available', 'Vacant'],
                      ['a', 'unavailable', 'Accommodated'],
                      ['a', 'selected', 'Selected'],
                      ['a', 'undermaintenence', 'Under Maintenence']
                  ]
              },
              click: function () { //Click event
                  if (this.status() == 'available') { //optional flat
                      $('<span>Flat Row ' + (this.settings.row + 1) + ' Flat ' + this.settings.label +
                              ', </span>')
                          .attr('id', 'cart-item-' + this.settings.id)
                          .data('seatId', this.settings.id)
                          .appendTo($cart);
      
                      $counter.text(sc.find('selected').length + 1);
                      $total.text(recalculateTotal(sc) + price);
      
                      return 'selected';
                  } else if (this.status() == 'selected') { //Checked
                      //Update Number
                      $counter.text(sc.find('selected').length - 1);
                      //update totalnum
                      $total.text(recalculateTotal(sc) - price);
      
                      //Delete reservation
                      $('#cart-item-' + this.settings.id).remove();
                      //optional
                      return 'available';
                  } else if (this.status() == 'unavailable') { //sold
                      return 'unavailable';
                  } else {
                      return this.style();
                  }
              }
          });
          //Accommodated flat
          sc.get(['1_2', '4_4', '4_5']).status('unavailable');
          //Under Maintenence flat
          sc.get(['2_8', '5_3']).status('undermaintenence');
        }

});
//sum total money
function recalculateTotal(sc) {
    var total = 0;
    sc.find('selected').each(function () {
        total += price;
    });

    return total;
}