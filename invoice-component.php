<h2>Generate User</h2>
<div class="search_user">
    <select name="" id="select_user">
      <option value="">Select User</option>
      <?php 
      if(!empty($bookly_cappointments)){
        foreach($bookly_cappointments as $customerinfo){
          echo  '<option value="'.intval($customerinfo->ID).'">'.__($customerinfo->full_name, 'bookly-invoice').'</option>';
        }
      }
      ?>
    </select>
</div>

<div class="payments">
  
</div>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">

<!-- <div class="popup">
  <div class="poupcontents">
    <div id="wrapper">
        <div class="clearfix" id="header_section">
          <h1>INVOICE 3-2-1</h1>

          <div id="company" class="clearfix">
            <div>Company Name</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
            <div><a href="mailto:company@example.com">company@example.com</a></div>
          </div>

          <div id="informations">
            <div><span>PROJECT</span> Website development</div>
            <div><span>CLIENT</span> John Doe</div>
            <div><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div>
            <div><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div>
            <div><span>DATE</span> August 17, 2015</div>
            <div><span>DUE DATE</span> September 17, 2015</div>
          </div>

        </div>
        <main>
          <table>
            <thead>
              <tr>
                <th class="service">SERVICE</th>
                <th class="desc">DESCRIPTION</th>
                <th>PRICE</th>
                <th>QTY</th>
                <th>TOTAL</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="service">Design</td>
                <td class="desc">Creating a recognizable design solution based on the company's existing visual identity</td>
                <td class="unit">$40.00</td>
                <td class="qty">26</td>
                <td class="total">$1,040.00</td>
              </tr>
              <tr>
                <td colspan="4">SUBTOTAL</td>
                <td class="total">$5,200.00</td>
              </tr>
              <tr>
                <td colspan="4">TAX 25%</td>
                <td class="total">$1,300.00</td>
              </tr>
              <tr>
                <td colspan="4" class="grand total">GRAND TOTAL</td>
                <td class="grand total">$6,500.00</td>
              </tr>
            </tbody>
          </table>
        </main>
    </div>
  </div>
</div> -->
<button type="button" onclick="printJS('wrapper', 'html')">
    Print Form
 </button>
 
<script type="text/javascript" src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<!-- <button onclick=CreatePDFfromHTML()>Download as a PDF</button>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script>
    function CreatePDFfromHTML() {
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
            pdf.save("<?php //echo $name ?>_invoice.pdf");
        });
    }
</script> -->
