<div class="box box-theme">

    <div>Temporary Application ID : <span class="text-uppercase fs17 ml15"><?= $this->session->userdata('application_id'); ?></span></div>
    <div>Applicant Name : <span class="text-uppercase fs17 ml15">
            <?= $apply_details[0]->fname; ?>  <?= $apply_details[0]->lname; ?></span></div>
</div>
<div class="p10">
    <div class="p10">
        <p>On pressing "Pay Now" ,the application will be redirected to Payment Gateway to pay the visa fee and will be outside the control of Visa Online Application. The responsibility of security of transaction process and details on payment page will be of Payment gateway. </p>
        <span class="red_heading text_bold" style="color:red; margin-left:24px">Disclaimer</span>
        <ul class="instructions_ul text_bold">
            <li>All travelers seeking admission to India under the e-Tourist Visa Scheme are required to carry printout of the Electronic Travel Authorisation sent through email by Bureau of Immigration. </li>
            <li>If your Electronic Travel Authorisation application is approved, it establishes that you are admissible to enter India under the e-Tourist Visa Scheme of Government of India. Upon arrival in India, records would be examined by an Immigration Officer at the port of entry. </li>
            <li>Biometric Details (Photograph & Fingerprints) of the applicant shall be mandatorily captured at Immigration on arrival in India. Non-compliance to do so would lead to denial of entry into India. </li>
            <li>A determination that you are not eligible for electronic travel authorisation does not preclude you from applying for a visa in Indian Mission for travel to India. </li>
            <li>
                All information provided by you, or on your behalf by a designated third party, must be true and correct. An electronic travel authorisation may be revoked at any time and for any reason, such as new information influencing eligibility. You may be subject to legal action if you make a materially false, fictitious, or fraudulent statement or representation in an electronic travel authorisation application submitted by you.
                For billing questions please call Allied Wallet at +1-888-255-1137 or <a href="https://www.alliedwallet.com/support">Click Here</a>
            </li>
            <li>Fee once paid is 100% non-refundable. </li>
            <li>Kindly note that your credit card statement will read "ALW*eindianvisa8882551137"</li>

        </ul>
        <span class="red_heading text_bold" style="color:red; margin-left:24px">Disclaimer</span>
        <p><input type="checkbox" name="agreement" id="agreement" value="agreed" onchange="agrement();" />I, the applicant, hereby certify that I agree to all the terms and conditions given on the website https://www.e-touristindiavisa.org and understand all the questions and statements of this application. The answers and information furnished in this application are true and correct to the best of my knowledge and belief. I understand and agree that once the fee is paid towards the Temporary application ID is 100% non-refundable and I will not claim a refund or dispute the transaction incase of cancellation request raised at my end.</p>
        <p><?= apptype($apply_details[0]->app_type) ?> = $<?= apptype_amount($apply_details[0]->app_type) ?></p>

        <div class="container">
            <div class="row" style="display: none;" id="payment">
                <div class="col-md-8 ml-md-auto">
                    <a href="<?= base_url('main/payment') ?>" class="btn btn-warning pl30 pr30">Pay Now</a>
                </div>
            </div>
        </div>
    </div>
</div>