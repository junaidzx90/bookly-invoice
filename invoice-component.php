<div class="search_user">
  <h2>Booookly Invoice</h2>
    <select name="" id="select_user">
      <option value="">Select a Customer</option>
      <?php 
      $myname = '';
      if(!empty($bookly_cappointments)){
        foreach($bookly_cappointments as $customerinfo){
          echo  '<option value="'.intval($customerinfo->ID).'">'.__($customerinfo->full_name, 'bookly-invoice').'</option>';
          // Name for pdf
          $myname = str_replace(' ','-',$customerinfo->full_name);
        }
      }
      ?>
    </select>
</div>

<div class="payments">
  <h1 class="selectuser">Please select a user</h1>
  <!-- Dynamic content goes here -->
</div>

<script>
    function CreatePDFfromHTML() {
        document.getElementsByClassName('bklyeditbtn')[0].style.display = 'none';
        var HTML_Width = jQuery("#wrapper").width();
        var HTML_Height = jQuery("#wrapper").height();
        var top_left_margin = 15;
        var PDF_Width = HTML_Width + (top_left_margin * 2);
        var PDF_Height = (PDF_Width * 1) + (top_left_margin * 2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;

        var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

        html2canvas(jQuery("#wrapper")[0]).then(function (canvas) {
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            pdf.save("<?php echo $myname; ?>-invoice.pdf");
            document.getElementsByClassName('bklyeditbtn')[0].style.display = 'block';
        });
    }
</script>
