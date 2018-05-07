<div><h4>Application Type  :  <?= apptype($apply_details[0]->app_type) ?></h4>
    <h4>Application NO : <?= strtoupper($this->session->userdata('application_id')); ?></h4>
</div>
<div class="p10">
    <form method="post" class="form-horizontal" action="<?=  base_url('visa_step4')?>" name="step4Form" id="step4Form" enctype="multipart/form-data">
        <div class="col-md-12 box_heading">Details of Visa Sought </div>
        <div class="form-group row">
            <label for="visaType" class="col-sm-4 require">Type of visa*</label>
            <div class="col-sm-6">e-Tourist Visa</div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4">Duration of Visa (in Days)</label>
            <div class="col-sm-6">
                <input type="hidden" class="form-control" name="applicationid" id="applicationid" value="<?= $this->session->userdata('application_id'); ?>" >
                <input type="text" class="form-control" name="visa_day" id="" value="60" readonly >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">No. of Entries</label>
            <div class="col-sm-6">
                <select required="" name="entries_no" class="form-control" id="entries_no"><option value="Double" title="Double">Double</option></select>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="app_type" class="col-sm-4 require">Application Type</label>
            <div class="col-sm-6">
                <select name="app_type" required="" label-name="Application Type" class="form-control">
                    <option value="">Select</option>
                    <?php if (!empty($application_type)) : foreach ($application_type as $a) : ?>
                            <option <?= ($apply_details[0]->app_type == $a->type) ? 'selected=""' : '' ?> value="<?= $a->type ?>"><?= $a->name ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="dtp_input2" class="col-sm-4 require">Expected Date journey</label>
            <div class="col-sm-6">
                <input type="text" required="" data-min_date="<?= date('Y,m,d', strtotime("+1 day")) ?>" class="form-control date_picker" name="expected_date_arrival" id="dob" placeholder="Expected Date journey" value="<?= get_date($apply_details[0]->expected_date_arrival, 'Y-m-d', 'd/m/Y') ?>" >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4">Port of Arrival in India</label>
            <div class="col-sm-6"><?= port_name($apply_details[0]->portofarrival) ?></div>
        </div>
        <div class="form-group row">
            <label for="port_of_exit" class="col-sm-4 require">Expected Port of Exit from India</label>
            <div class="col-sm-6">
                <select name="port_of_exit" required="" label-name="Expected Port of Exit from India" class="form-control" id="passportType">
                    <option value="">Select</option>
                    <?php foreach ($ports as $p) : ?>
                        <option value="<?= $p->id; ?>" title="<?= $p->name; ?>"> <?= $p->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Places likely to be visited</label>
            <div class="col-sm-6">
                <input type="text" name="visitedplace" value="<?= $apply_details[0]->places_likely_to_visit; ?>" required="" class="form-control" id="" placeholder="visited Place">
            </div>
        </div>
        <div class="col-md-12 box_heading">Previous Visa/Currently valid Visa Details </div>
        <div class="form-group row">
            <label for="dtp_input2" class="col-sm-4">Have you ever visited India before?</label>
            <div class="col-sm-7">
                <label class="radio-inline">
                    <input type="radio" <?= ($apply_details[0]->visited_India == 'yes') ? 'checked=""' : '' ?> name="visitedbefore" id="inlineRadio1" value="yes"> YES
                </label>
                <label class="radio-inline">
                    <input type="radio" name="visitedbefore" id="inlineRadio2" <?= (($apply_details[0]->visited_India == 'no') || (empty($apply_details[0]->visited_India))) ? 'checked=""' : '' ?>  value="no"> NO
                </label>
            </div>
        </div>
        <div id="visitedbefore_form" style="display: <?= (($apply_details[0]->visited_India == 'no') || (empty($apply_details[0]->visited_India))) ? 'none' : 'block' ?>;">
            <div class="form-group row">
                <label for="" class="col-sm-4 ">Address </label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="visitedaddress"><?= $apply_details[0]->visited_address ?></textarea>
                </div>
            </div>				
            <div class="form-group row">
                <label for="" class="col-sm-4 require">Cities previously visited in India</label>
                <div class="col-sm-7">
                    <textarea class="form-control" required="" name="visitedcities"><?= $apply_details[0]->previously_visited_city ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="dtp_input2" class="col-sm-4 require">Last Indian Visa No/Currently valid Indian Visa No.</label>
                <div class="col-sm-6">
                    <input type="text" name="visitedvisano" required="" class="form-control" value="<?= $apply_details[0]->last_Indian_visa_no ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-4 require">Type of Visa</label>
                <div class="col-sm-7">
                    <?php
                    $visitedvisatype = array(
                        '' => 'Select Type of Visa',
                        'ART SURROGACY VISA' => 'ART SURROGACY VISA',
                        'BUSINESS VISA' => 'BUSINESS VISA',
                        'BUSINESS VISA DEPENDENTS' => 'BUSINESS VISA DEPENDENTS',
                        'BUSINESS VISA TRANSFER' => 'BUSINESS VISA TRANSFER',
                        'CONFERENCE/SEMINARS VISA' => 'CONFERENCE/SEMINARS VISA',
                        'DIPLOMATIC DEPENDENT VISA' => 'DIPLOMATIC DEPENDENT VISA',
                        'DIPLOMATIC VISA' => 'DIPLOMATIC VISA',
                        'EMPLOYMENT VISA' => 'EMPLOYMENT VISA',
                        'EMPLOYMENT VISA DEPENDENTS' => 'EMPLOYMENT VISA DEPENDENTS',
                        'EMPLOYMENT VISA TRANSFER' => 'EMPLOYMENT VISA TRANSFER',
                        'ENTRY VISA' => 'ENTRY VISA',
                        'ENTRY VISA TRANSFER' => 'ENTRY VISA TRANSFER',
                        'JOURNALIST VISA' => 'JOURNALIST VISA',
                        'MEDICAL ATTENDANT' => 'MEDICAL ATTENDANT',
                        'MEDICAL  VISA' => 'MEDICAL  VISA',
                        'MEDICAL VISA TRANSFER' => 'MEDICAL VISA TRANSFER',
                        'MISSIONARY VISA' => 'MISSIONARY VISA',
                        'MOUNTAINEERING VISA' => 'MOUNTAINEERING VISA',
                        'OFFICIAL DEPENDENT VISA' => 'OFFICIAL DEPENDENT VISA',
                        'OFFICIAL VISA' => 'OFFICIAL VISA',
                        'PILGRIMES VISA' => 'PILGRIMES VISA',
                        'PROJECT VISA' => 'PROJECT VISA',
                        'RESEARCH VISA' => 'RESEARCH VISA',
                        'RESEARCH VISA DEPENDENTS' => 'RESEARCH VISA DEPENDENTS',
                        'RESEARCH VISA TRANSFER' => 'RESEARCH VISA TRANSFER',
                        'SOUTH ASIAN UNIVERSITY' => 'SOUTH ASIAN UNIVERSITY',
                        'SPORTS' => 'SPORTS',
                        'STUDENT VISA' => 'STUDENT VISA',
                        'STUDENT VISA DEPENDENTS' => 'STUDENT VISA DEPENDENTS',
                        'STUDENT VISA TRANSFER' => 'STUDENT VISA TRANSFER',
                        'TOURIST VISA' => 'TOURIST VISA',
                        'TOURIST VISA TRANSFER' => 'TOURIST VISA TRANSFER',
                        'TRANSIT VISA' => 'TRANSIT VISA',
                        'UN OFFICIAL' => 'UN OFFICIAL',
                        'VISIT VISA' => 'VISIT VISA'
                    );
                    echo form_dropdown('visitedvisatype', $visitedvisatype, $apply_details[0]->visited_type_Visa, ['id' => 'occupation', 'class' => 'form-control', 'required' => 'required', 'label-name' => 'Type of Visa']);
                    ?>  
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 require">Place of Issue</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" value="<?= $apply_details[0]->visited_visa_issue_place ?>" name="visitedplaceissue" >
                </div>
            </div>
            <div class="form-group row">
                <label for="dtp_input2" class="col-sm-4 require">Date of Issue</label>
                <div class="col-sm-6">
                    <input type="text" data-min_date="<?= date('Y,m,d', strtotime("-20 year")) ?>" class="form-control date_picker" value="<?= get_date($apply_details[0]->visited_visa_issue_date, 'Y-m-d', 'd/m/Y') ?>" name="visitedissuedate" id="visitedissuedate" placeholder="Date of Birth" >
                </div>
            </div>
        </div> 

        <div class="form-group row">
            <label for="" class="col-sm-5 ">Has permission to visit or to extend stay in India previously been refused? </label>
            <div class="col-sm-4">
                <label class="radio-inline">
                    <input type="radio" name="extendstay" <?= (!empty($apply_details[0]->extend_visa_details)) ? 'checked=""' : '' ?> value="yes"> YES
                </label>
                <label class="radio-inline">
                    <input type="radio" name="extendstay" id="inlineRadio2" <?= (empty($apply_details[0]->extend_visa_details)) ? 'checked=""' : '' ?> value="no"> NO
                </label>
            </div>
        </div>
        <div class="form-group row" id="extendstaydetails" style="display: <?= (!empty($apply_details[0]->extend_visa_details)) ? 'block' : 'none' ?>;">
            <label for="" class="col-sm-5 ">If so, when and by whom (Mention Control No. and date also) </label>
            <div class="col-sm-6">
                <input type="text" class="form-control" value="<?= $apply_details[0]->extend_visa_details ?>" name="extendstaydetails" id="" >
            </div>
        </div>
        <div class="col-md-12 box_heading">Other Information </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 ">Countries Visited in Last 10 years </label>
            <div class="col-sm-7">
                <textarea class="form-control" name="visited10Countries"><?= $apply_details[0]->visited10Countries ?></textarea>
            </div>
        </div>
        <div class="col-md-12 box_heading">Other Information </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Reference Name in India</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" value="<?= $apply_details[0]->ref_name ?>" required="" id="" name="refindia" >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Address</label>
            <div class="col-sm-7">
                <textarea class="form-control" required="" name="refaddress"><?= $apply_details[0]->ref_address ?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Phone </label>
            <div class="col-sm-7">
                <input type="number" min="0" required="" value="<?= $apply_details[0]->ref_phone ?>" class="form-control" id="" name="ref_phone" >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Reference Name in Home Country</label>
            <div class="col-sm-7">
                <input type="text" required="" value="<?= $apply_details[0]->ref_home_name ?>" class="form-control" id="" name="ref_home" >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Address</label>
            <div class="col-sm-7">
                <input type="text" required="" value="<?= $apply_details[0]->ref_home_address ?>" class="form-control" id="" name="ref_homeaddress" >
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">Phone</label>
            <div class="col-sm-7">
                <input type="text" required="" value="<?= $apply_details[0]->ref_home_phone ?>" class="form-control" id="" name="ref_homephone" >
            </div>
        </div>
        <div class="col-md-12 box_heading">Image Upload</div>
        <div class="form-group row">
            <label for="" class="col-sm-4 require">
                Image <br/>
                <img src="<?=  base_url('public/img/photo_required.png')?>" height="200">
            </label>
            <div class="col-sm-7">
                <?php if (!empty($apply_details[0]->image)) : ?>
                    <input type="hidden" name="old_img" value="<?= $apply_details[0]->image ?>">
                    <input type="file" accept="image/.jpe,.jpg,.jpeg,.png" class="form-control view_photo" id="image" name="image">
                    <div class="show_images mt5">
                        <img style="max-width: 250px" src="<?= base_url(APPLICATION_IMG . $apply_details[0]->image) ?>">
                    </div>
                <?php else : ?>
                    <input type="file" accept="image/.jpe,.jpg,.jpeg,.png" required="" class="form-control view_photo" id="image" name="image" >
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4"> </div>
            <div class="col-sm-4">
                <input type="submit" class="btn btn-primary" name="step4" value="Continue" />
            </div>
        </div>
    </form>
</div>