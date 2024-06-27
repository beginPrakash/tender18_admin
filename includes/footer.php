          </div>
          <!-- container-fluid -->
          </div>
          <!-- End Page-content -->

          <footer class="footer">
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-6">
                  <script>
                    document.write(new Date().getFullYear())
                  </script>
                  © Tender18.
                </div>
                <!-- <div class="col-sm-6">
                  <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Greencubes
                  </div>
                </div> -->
              </div>
            </div>
          </footer>
          </div>
          </div>
          <!-- END layout-wrapper -->

          <!--start back-to-top-->
          <button class="btn btn-dark btn-icon" id="back-to-top">
            <i class="bi bi-caret-up fs-3xl"></i>
          </button>
          <!--end back-to-top-->

          <!--preloader-->
          <div id="preloader">
            <div id="status">
              <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>

          <!-- JAVASCRIPT -->
          <script src="<?php echo ADMIN_URL; ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
          <script src="<?php echo ADMIN_URL; ?>assets/libs/simplebar/simplebar.min.js"></script>
          <script src="<?php echo ADMIN_URL; ?>assets/js/plugins.js"></script>
          <!-- prismjs plugin -->
          <script src="<?php echo ADMIN_URL; ?>assets/libs/prismjs/prism.js"></script>

          <!-- Modal Js -->
          <script src="<?php echo ADMIN_URL; ?>assets/js/pages/modal.init.js"></script>

          <!-- App js -->
          <script src="<?php echo ADMIN_URL; ?>assets/js/app.js"></script>

          <script src="<?php echo ADMIN_URL; ?>assets/js/jquery.min.js"></script>

          <!-- apexcharts -->
          <script src="<?php echo ADMIN_URL; ?>assets/libs/apexcharts/apexcharts.min.js"></script>

          <script src="<?php echo ADMIN_URL; ?>assets/libs/list.js/list.min.js"></script>

          <!-- Dashboard init -->
          <!-- <script src="assets/js/pages/dashboard-analytics.init.js"></script> -->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

          <!--datatable js-->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/1.11.5/jquery.dataTables.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.6/dataTables.bootstrap5.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.2.9/dataTables.responsive.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.2/js/dataTables.buttons.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.2/js/buttons.print.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.2/js/buttons.html5.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

          <script src="<?php echo ADMIN_URL; ?>assets/js/pages/datatables.init.js"></script>

          <!-- ckeditor -->
          <script src="<?php echo ADMIN_URL; ?>assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

          <!-- init js -->
          <script src="<?php echo ADMIN_URL; ?>assets/js/pages/form-editor.init.js"></script>

          <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
          <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
          <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
          <script>
            $(document).ready(function() {
              $('#pass_check').validate({
                rules: {
                  'opass': "required",
                  'npass': "required",
                  'cpass': {
                    required: true,
                    equalTo: "#npass"
                  }
                },
              });
            });
          </script>
          <script>
            $(document).ready(function() {
              $('#register').validate({
                rules: {
                  'email': "required",
                  'username': "required",
                  'pass': "required",
                  "user_role": "required",
                  "company_name": "required",
                  "customer_name": "required",
                  // "alt_email": "required",
                  "mobile_number": "required",
                  // "alt_mobile": "required",
                  "whatsapp_alert_no": "required",
                  "address": "required",
                  "state": "required",
                  "status": "required",
                  "keywords": "required",
                  "words": "required",
                  "not_used_keywords": "required",
                  "all_filters[]": "required",
                  "start_date": "required",
                  "duration": "required",
                  // "expired_date": "required"
                },
              });
            });
          </script>
          <script>
            $(document).ready(function() {
              $('#settings').validate({
                rules: {
                  'desktop_logo': "required",
                  'mobile_logo': "required",
                  'button_text': "required",
                  'button_link': "required",
                  'button_text1': "required",
                  'button_link1': "required",
                  'address': "required",
                  'first_email': "required",
                  'second_email': "required",
                  'contact_no': "required",
                  'facebook_link': "required",
                  'twitter_link': "required",
                  'linked_link': "required",
                  'youtube_link': "required",
                  'instagram_link': "required",
                  'whatsapp_num': "required",
                  'quick_menu_title': "required",
                  'contact_menu_title': "required",
                  'tender_menu_title': "required",
                  'terms_text': "required",
                  'terms_link': "required",
                  'copyright_text': "required"
                },
              });

              $('#setting').validate({
                rules: {
                  'button_text': "required",
                  'button_link': "required",
                  'button_text1': "required",
                  'button_link1': "required",
                  'address': "required",
                  'first_email': "required",
                  'second_email': "required",
                  'contact_no': "required",
                  'facebook_link': "required",
                  'twitter_link': "required",
                  'linked_link': "required",
                  'youtube_link': "required",
                  'instagram_link': "required",
                  'whatsapp_num': "required",
                  'quick_menu_title': "required",
                  'contact_menu_title': "required",
                  'tender_menu_title': "required",
                  'terms_text': "required",
                  'terms_link': "required",
                  'copyright_text': "required"
                },
              });
            });
          </script>
          <!-- <script>
            $(document).ready(function() {
              // setTimeout(function () {
              //   $("#vertical-hover").trigger("click")
              // }, 3000)
              if (screen.width > 1024)
                $("html").attr("data-sidebar-size", "sm-hover-active")
            })
          </script> -->
          </body>

          </html>