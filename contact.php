<?php 
  /* Template Name: Contact */ 
  get_header();
?>


<main class="contact_page">

 <!-- Contact section one start  -->
  <section class="cntct_pg_1"> 
    <div class="container-fluid">
      <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="cntct_pg_title">
              <h1> Contact </h1>
            </div> 

             <div class="cntct_pg_desc">   
                <h3> We’d love to hear from you. </h3> 
                <p>  Every project begins with a spark. <br>
                     An idea, a vision, a question. And often... it all begins with a simple message.
                </p>
                <ul>
                    <li>Want to discover PUK's outdoor lighting solutions? </li>
                    <li> Do you have an architectural project and are looking for a technical partner? </li>
                    <li> Are you a designer, architect, or planner looking for lighting inspiration? </li>
                    <li> Or do you need support, technical documentation, or advice? </li> 
                </ul>
                <p> We're here to listen, advise and, if you wish, support you step by step in your projects. <br>
                    Or rather: light after light.
                </p>
                
            </div>
            
      </div>
    </div>
  </section> 
<!-- Contact section one end  -->



 <!-- Contact section two start  -->
  <section class="cntct_pg_2"> 
    <div class="container-fluid">
      <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
               <form action="#" class="cntct_pg_2_form">
                   <h2> Contact us for... </h2>
                   <div class="cntct_pg_2_form_box">
                       <input type="text" name="first_name" placeholder="First name*" required>
                       <input type="text" name="last_name"  placeholder="Last name*" required>
                   </div>
                   <div class="cntct_pg_2_form_box">
                       <input type="email" name="email" placeholder="Email*" required>
                       <input type="phone" name="phone"  placeholder="Phone*" required>
                   </div>
                   <div class="cntct_pg_2_form_box">
                       <input type="text" name="company" placeholder="Company" >
                   </div>
                   <div class="cntct_pg_2_form_box">
                       <textarea name="message" id="message"  placeholder="Message"></textarea>
                   </div>

                   <div class="cntct_pg_2_form_box cntct_pg_2_checkbox">
                       <input type="checkbox" name="checkbox" id="form_checkbox">
                       <label for="form_checkbox">I hereby declare that I have reviewed the <a href="#" target="_blank">privacy</a> notice of PUK Italia Group Srl concerning the processing of personal data for purpose (a).</label>
                   </div>
                    <div class="cntct_pg_2_form_box">
                        <input type="submit" value="Submit"> 
                    </div>

               </form>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
            <div class="cntct_pg_2_box">
                <div class="cntct_pg_2_right_box">
                    <span>Call us </span>
                    <p>T. +39 039 24.57.920 </p>
                </div>

                <div class="cntct_pg_2_right_box">
                    <span> General information request </span>
                    <p> puk@puk.it </p>
                </div>
            </div>
          </div>
      </div>
    </div>
  </section> 
<!-- Contact section two end  -->


<!-- Contact section three start  -->
  <section class="cntct_pg_3"> 
    <div class="container-fluid">
      <div class="row"> 
        <div class="cntct_pg_3_flex">

            <div class="cntct_pg_3_left">
                <h3>Our headquarter</h3>
               <p>PUK ITALIA GROUP srl <br>
                  Via San Giorgio,<br>
                  16 Lissone (MB) - ITALY
                </p>
            </div>
             <div class="cntct_pg_3_right"> 
                 <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/azienda_foto1-1.jpg" alt="contact-image">
            </div>
            
        </div>
            
          
        


      </div>
    </div>
  </section> 
<!-- Contact section three end  -->


<!-- Contact section four start  -->
  <section class="cntct_pg_4"> 
    <div class="container-fluid">
      <div class="row">

          <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
            <div class="cntct_pg_4_form">
                <h3> Subscribe to our newsletter to discover the latest news in outdoor
                     lighting design. <br>
                     Product news, case studies, and inspiration for your outdoor projects.
                </h3>

                <form action="#">
                    <div class="cntct_pg_4_form_box">
                      <input type="email" name="subscribe_email" placeholder="Your Email">
                    </div>
                    <div class="cntct_pg_4_form_box">
                       <input type="checkbox" name="checkbox" id="cntct_pg_4_checkbox_1">
                       <label for="cntct_pg_4_checkbox_1"> I declare that I have read the information regarding the processing of personal data * </label>
                   </div>
                   <div class="cntct_pg_4_form_box">
                       <input type="checkbox" name="checkbox" id="cntct_pg_4_checkbox_2">
                       <label for="cntct_pg_4_checkbox_2"> I consent to the processing of my personal data for marketing purposes. * </label>
                   </div>
                   <div class="cntct_pg_4_form_box">
                      <input type="submit" value="Subscribe"> 
                   </div>
                    
                </form>
            </div>
          </div>

      

      </div>
    </div>
  </section> 
<!-- Contact section four end  -->

</main>



<?php get_footer(); ?>