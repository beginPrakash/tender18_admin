<?php  include '../authentication/authentication.php';  ?>
<?php  $pages = 'index'; ?>
<?php  include '../includes/header.php' ?>
<?php  include '../includes/connection.php';  

?>
 
          
      <div class="br-pageheader">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="#">Dashboard</a>
          <a class="breadcrumb-item active" href="../pages/index.php">Pages</a>
          <!-- <span class="breadcrumb-item active">index.php</span> -->
        </nav>
      </div><!-- br-pageheader -->
      

      <div class="br-pagebody">
        <div class="br-section-wrapper">
          <div class="bd bd-gray-300 rounded table-responsive">
            <table class="table table-striped mg-b-0">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Pages Name</th>
                  <th>Published Date</th>
                  <th>Updated Date</th>
                </tr>

              </thead>
              <tbody>

                <tr>
                  <th scope="row">1</th>
                  <td>Home Page</td>
                  <td>20/09/2022</td>
                  <td>20/09/2022</td>
                   <th ><a href="../home/index.php"> Edit </a> </th>
                </tr>

                 <tr>
                  <th scope="row">2</th>
                  <td>Become Partner Page</td>
                  <td>22/09/2022</td>
                  <td>22/09/2022</td>
                   <th ><a href="../become-partner/index.php"> Edit </a> </th>
                </tr>
                
                <tr>
                  <th scope="row">3</th>
                  <td>Refer a Client Page</td>
                  <td>11/01/2022</td>
                  <td>11/01/2022</td>
                   <th ><a href="../refer-client/index.php"> Edit </a> </th>
                </tr>

                <tr>
                  <th scope="row">4</th>
                  <td>Apply Now Page</td>
                  <td>22/09/2022</td>
                  <td>22/09/2022</td>
                   <th ><a href="../apply-now/index.php"> Edit </a> </th>
                </tr>

                <tr>
                  <th scope="row">5</th>
                  <td>Thank You Page</td>
                  <td>23/09/2022</td>
                  <td>23/09/2022</td>
                   <th ><a href="../thank-you/index.php"> Edit </a> </th>
                </tr>

                 <tr>
                  <th scope="row">6</th>
                  <td>FAQ</td>
                  <td>26/09/2022</td>
                  <td>26/09/2022</td>
                   <th ><a href="../faq/index.php"> Edit </a> </th>
                </tr>
                
                 <tr>
                  <th scope="row">7</th>
                  <td>Contact Us</td>
                  <td>27/09/2022</td>
                  <td>27/09/2022</td>
                  <th ><a href="../contact-us/index.php"> Edit </a> </th>
                </tr>
                
                <tr>
                  <th scope="row">8</th>
                  <td>Terms of Use and Privacy Policy</td>
                  <td>20/09/2022</td>
                  <td>20/09/2022</td>
                   <th ><a href="../privacy/index.php"> Edit </a> </th>
                </tr>
                
                <tr>
                  <th scope="row">9</th>
                  <td>Terms Page</td>
                  <td>20/09/2022</td>
                  <td>20/09/2022</td>
                   <th ><a href="../terms/index.php"> Edit </a> </th>
                </tr>
                
                <tr>
                  <th scope="row">10</th>
                  <td>Contact Partner Support</td>
                  <td>11/01/2022</td>
                  <td>11/01/2022</td>
                   <th ><a href="../contact-partner-support/index.php"> Edit </a> </th>
                </tr>
                
                <tr>
                  <th scope="row">11</th>
                  <td>Sales Training</td>
                  <td>11/01/2022</td>
                  <td>11/01/2022</td>
                   <th ><a href="../sales-training/index.php"> Edit </a> </th>
                </tr>
              
              </tbody>

            </table>
          </div><!-- bd -->

</div>
</div>

  <!-- *FAQs--> 

<script type="text/javascript">
    // add row
    $("#addRowFaqs").click(function () {
        var html = '';
      
        html += '<div id="inputFormRow">';
        html += '<div class="col-lg-40">';
        html += ' <label class="form-control-label">Title <span class="tx-danger">*</span></label>';
        html += '<input class="form-control" type="text" name="testi_title[]"  placeholder="Enter Title">';
        html += '<label class="form-control-label">Description <span class="tx-danger">*</span></label>';
        html += '<textarea  class="form-control"  name="testi_desri[]" placeholder="Enter Description"> </textarea> ';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
        html += '</div>';
        html += '</div>';
     


        $('#newRowFaqs').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#inputFormRow').remove();
    });
</script>
<!-- 
<script type="text/javascript">
      $(document).ready(function() {
        $("body").on("click",".add-more",function(){
            var html = $(".after-add-more").first().clone();
         
            $(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
         
            $(".after-add-more").last().after(html);
          
        });
           $("body").on("click",".remove",function(){
                $(this).parents(".after-add-more").remove();
            });
    });
</script>
 -->

<?php  include'../includes/footer.php';  ?> 

