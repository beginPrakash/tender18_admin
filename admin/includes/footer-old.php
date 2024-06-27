
    <script src="../lib/jquery/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="../lib/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../lib/moment/min/moment.min.js"></script>
    <script src="../lib/peity/jquery.peity.min.js"></script>
    <script src="../lib/rickshaw/vendor/d3.min.js"></script>
    <script src="../lib/rickshaw/vendor/d3.layout.min.js"></script>
    <script src="../lib/rickshaw/rickshaw.min.js"></script>
    <script src="../lib/jquery.flot/jquery.flot.js"></script>
    <script src="../lib/jquery.flot/jquery.flot.resize.js"></script>
    <script src="../lib/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../lib/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="../lib/echarts/echarts.min.js"></script>
    <script src="../lib/select2/js/select2.full.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyAq8o5-8Y5pudbJMJtDFzb8aHiWJufa5fg"></script>
    <script src="../lib/gmaps/gmaps.min.js"></script>
    <script src="../lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="../lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script src="../js/bracket.js"></script>
    <script src="../js/map.shiftworker.js"></script>
    <script src="../js/ResizeSensor.js"></script>
    <script src="../js/dashboard.js"></script>
    <script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="../css/bracket.js"></script>
    
    <script>
      $(function(){
        'use strict'

        // FOR DEMO ONLY
        // menu collapsed by default during first page load or refresh with screen
        // having a size between 992px and 1299px. This is intended on this page only
        // for better viewing of widgets demo.
        $(window).resize(function(){
          minimizeMenu();
        });

        minimizeMenu();

        function minimizeMenu() {
          if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1299px)').matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
          } else if(window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
          }
        }
      });
    </script> 
 

 

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('long_desc', {
    width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('ckt_opportunity_1', {
    width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('ckt_opportunity_2', {
    width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('ckt_erc_partner_flow', {
     width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('faq_id', {
     width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('faq_id_1', {
     width: "875px",
    height: "300px"

});
</script>

<script type="text/javascript">
// Initialize CKEditor

CKEDITOR.config.allowedContent = true;
CKEDITOR.replace('new_faq_2', {
     width: "875px",
    height: "300px"

});
</script>
 
 

<script type="text/javascript">
  $('#datatable1').DataTable({
  responsive: true,
  language: { 
    searchPlaceholder: 'Search...',
    sSearch: '',
    lengthMenu: '_MENU_ items/page',
  }
});
</script>



<!-- Header admin side validation -->
<script>

  $(document).ready(function () {
    $('#header_form').validate({
    /* only  also Write in Form  in id ex:id="fform"*/
      rules: {
        'desklogo': {
          required: true
        },
        'moblogo': {
          required: true,
        },
        contact_num: {
          required: true,
        },
        contact_text: {
          required: true,
        },
        button_link: {
          required: true,
        },
        button_text: {
          required: true,
        },
        copyright_text: {
          required: true,
        },
        privacy_link: {
          required: true,
        },
        privacy_text: {
          required: true,
        },
        terms_link: {
          required: true,
        },
        terms_text: {
          required: true
        }

      
      },

      submitHandler: function (form) {
        form.submit();
      }
    });
  });
</script>


<!-- Header admin side validation -->
<script>

  $(document).ready(function () {
    $('#img_form').validate({
    /* only  also Write in Form  in id ex:id="fform"*/
      rules: {
        
        contact_num: {
          required: true
        },
        contact_text: {
          required: true,
        },
        button_link: {
          required: true,
        },
        button_text: {
          required: true,
        },
        copyright_text: {
          required: true,
        },
        privacy_link: {
          required: true,
        },
        privacy_text: {
          required: true,
        },
        terms_link: {
          required: true,
        },
        terms_text: {
          required: true
        }

      
      },

      submitHandler: function (form) {
        form.submit();
      }
    });
  });
</script>




<!-- Banner Section validation -->
<script>

  $(document).ready(function () {
    $('#index_validation').validate({
    /* only  also Write in Form  in id ex:id="fform"*/
      rules: {
        
        ban_title: {
          required: true
        },
        ban_description: {
          required: true,
        },
        ban_link: {
          required: true,
        },
        ban_link_text: {
          required: true,
        },
        main_title: {
          required: true,
        },
        left_title: {
          required: true,
        },
        left_content: {
          required: true,
        },
        right_title: {
          required: true,
        },
        right_content: {
          required: true,
        },
        right_btn_link: {
          required: true,
        },
        right_btn_text: {
          required: true,
        },
        why_title: {
          required: true,
        },
        why_content: {
          required: true,
        },
        erc_sub_title: {
          required: true,
        },
        erc_title: {
          required: true,
        },
        erc_btn_link: {
          required: true,
        },
        erc_btn_text: {
          required: true
        }
        

      
      },

      submitHandler: function (form) {
        form.submit();
      }
    });
  });
</script>


<!-- Add Testimonial Validation -->
<script>

  $(document).ready(function () {
    $('#add_testimonial_form_val').validate({
    /* only  also Write in Form  in id ex:id="fform"*/
      rules: {
        
       testimonial_desri: {
          required: true
        },
        testimonial_title: {
          required: true
        },

      },

      submitHandler: function (form) {
        form.submit();
      }
    });
  });


</script>

<!-- applicants page form val -->
<script>

  $(document).ready(function () {
    $('#applicants_form_id').validate({
    /* only  also Write in Form  in id ex:id="fform"*/
      rules: {
        
       first_name: {
          required: true
        },
        last_name: {
          required: true,
        },
        phone: {
          required: true,
        },
        email: {
          required: true,
        },
        address: {
          required: true,
        },
        // address_2: {
        //   required: true,
        // },
        city: {
          required: true,
        },
        zip_code: {
          required: true,
        },
        state: {
          required: true
        }
        

      },

      submitHandler: function (form) {
        form.submit();
      }
    });
  });



</script>


<script>
    (function() {
      $('.hamburger').on('click', function() {
         $('body').toggleClass('menu-open');
      });
    })();
    
</script>
    
<script>
    $('.accordion.active').siblings('.panel').slideDown('slow');
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
    if($(this).hasClass('active')){
    $(this).removeClass('active');
    $(this).siblings('.panel').slideUp('slow');
    }
    else{
    $('.accordion').removeClass('active');
    $(this).addClass('active');
    $('.accordion').siblings('.panel').slideUp('slow');
    $(this).siblings('.panel').slideDown('slow');
    }
    });
    }
</script>

<script>
    function autocomplete(inp, arr) {
      /*the autocomplete function takes two arguments,
      the text field element and an array of possible autocompleted values:*/
      var currentFocus;
      /*execute a function when someone writes in the text field:*/
      inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          /*close any already open lists of autocompleted values*/
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
          for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
              /*create a DIV element for each matching element:*/
              b = document.createElement("DIV");
              /*make the matching letters bold:*/
              b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i].substr(val.length);
              /*insert a input field that will hold the current array item's value:*/
              b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
              /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
                  /*insert the value for the autocomplete text field:*/
                  inp.value = this.getElementsByTagName("input")[0].value;
                  /*close the list of autocompleted values,
                  (or any other open lists of autocompleted values:*/
                  closeAllLists();
              });
              a.appendChild(b);
            }
          }
      });
      /*execute a function presses a key on the keyboard:*/
      inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
              /*and simulate a click on the "active" item:*/
              if (x) x[currentFocus].click();
            }
          }
      });
      function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
      }
      function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }
      function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }
      /*execute a function when someone clicks in the document:*/
      document.addEventListener("click", function (e) {
          closeAllLists(e.target);
      });
    }
    
    /*An array containing all the country names in the world:*/
    var countries = ["faq.php","contact-us.php","apply-now.php","#calculator_menu_id","index.php#calculator_menu_id","thank-you.php"];
    
    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    autocomplete(document.getElementById("appliynow_calc_btn"), countries);
    autocomplete(document.getElementById("myInput"), countries);
    autocomplete(document.getElementById("erc_btn_link"), countries);
    autocomplete(document.getElementById("right_btn_link"), countries);
    autocomplete(document.getElementById("contact_auto"), countries);
    autocomplete(document.getElementById("myInput_con"), countries);
    autocomplete(document.getElementById("thankyoubtn"), countries);

</script>

<script>
function exportTableToExcel(tableId, filename) {
    let dataType = 'application/vnd.ms-excel';
    let extension = '.xls';

    let base64 = function(s) {
        return window.btoa(unescape(encodeURIComponent(s)))
    };

    let template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
    let render = function(template, content) {
        return template.replace(/{(\w+)}/g, function(m, p) { return content[p]; });
    };

    let tableElement = document.getElementById(tableId);

    let tableExcel = render(template, {
        worksheet: filename,
        table: tableElement.innerHTML
    });

    filename = filename + extension;

    if (navigator.msSaveOrOpenBlob)
    {
        let blob = new Blob(
            [ '\ufeff', tableExcel ],
            { type: dataType }
        );

        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        let downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        downloadLink.href = 'data:' + dataType + ';base64,' + base64(tableExcel);

        downloadLink.download = filename;

        downloadLink.click();
    }
}
</script>


</body>

<!-- Mirrored from themepixels.me/demo/bracketplus1.4/app/template/ by HTTrack Website Copier/3.x [XR&CO'2010], Sat, 12 Sep 2020 04:34:53 GMT -->
</html>