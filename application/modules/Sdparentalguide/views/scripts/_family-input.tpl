<div id="<?php echo $this->name; ?>-wrapper" class="form-wrapper-heading" style="margin-bottom: 1rem;">
    <span class="field_container">
        <?php echo $this->translate('Tell us about your kids'); ?>
    </span>
</div>

<div class="container p-0" id="family-information">
    <div class="row d-flex align-items-center my-4 mx-0">
        
        <div class="col-2 p-0 family-item-holder">
            <div class="female d-flex align-items-center justify-content-center">
                <svg id="1d586a36-69c9-43a9-b3c9-6e87bc4008b6" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#fff"/></svg>
            </div>
        </div>

        <div class="col-8 family-item p-0">
            <span class="font-weight-bold"> Babie </span>
            <span class="text-muted"> 1-12 Months </span>
            <p class="desc text-muted small">
                Female
            </p>
        </div>

        <div class="col-2 p-0">
            <a href="javascript:void(0)" class="btn btn-light small p-3">
                <?php echo $this->translate('Edit'); ?>
            </a>
        </div>

    </div> <!-- end of row -->

    <div class="row add-child-form add-another-child my-5 mx-0 py-4" id="add-child-form">

        <div class="col-6 p-0">
            <p class="font-weight-normal">
                <?php echo $this->translate('Tell us their gender'); ?>
            </p>
        </div>
        <div class="col-5 p-0 d-flex justify-content-end">
            <div class="close-form">
                <a href="javascript:void(0)" class="d-flex align-items-center justify-content-center" onclick="addChild(1)">
                    <i class="fa fa-close text-white"> </i>
                </a>
            </div>
        </div>
        
        <div class="col-12 d-flex add-gender p-0 mb-4" id="add-gender">
            <div class="col-2 mr-1 p-0 text-center male small add-gender-items">
                <a href="javascript:void(0)" onclick="selectChildGender(1, this)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="male" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Male'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center female small add-gender-items">
                <a href="javascript:void(0)" onclick="selectChildGender(2, this)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="female" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Female'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 text-center d-flex align-items-center small not-to-answer add-gender-items">
                <a href="javascript:void(0)" onclick="selectChildGender(3, this)" class="d-block p-2">
                    <?php echo $this->translate('Prefer Not to Answer'); ?>
                </a>
            </div>
        </div> <!-- end of add-gender -->

        <div class="col-12 p-0">
            <span class="font-weight-normal">
                <?php echo $this->translate('What is their age'); ?> 
                <span class="text-muted small">(<?php echo $this->translate('This helps us to better provide and recommend products and content'); ?>)</span>
            </span>
        </div>

        <div class="col-12 d-flex add-birthday p-0 mb-4" id="add-birthday">
            
            <div class="col-12 months p-0" id="months">
                
                <div class="col-12 p-0 d-flex">
                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(1, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('January'); ?>
                        </a>
                    </div>
                    
                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(2, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('February'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(3, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('March'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(4, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('April'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(5, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('May'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(6, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('June'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="col-12 p-0 d-flex">
                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(7, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('July'); ?>
                        </a>
                    </div>
                    
                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(8, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('August'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(9, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('September'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(10, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('October'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(11, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('November'); ?>
                        </a>
                    </div>

                    <div class="mr-1 mt-2 p-0 text-center d-flex align-items-center small month-select">
                        <a href="javascript:void(0)" onclick="selectChildGender(12, this)" class="d-block px-3 py-4 w-100">
                            <?php echo $this->translate('December'); ?>
                        </a>
                    </div>
                </div>
                
                

            </div> <!-- months -->

            <div class="col-12 d-flex years p-0 d-none" id="year-range">
                <div class="col-2 mr-1 p-0 text-center d-flex align-items-center small not-to-answer add-gender-items">
                    <a href="javascript:void(0)" onclick="selectChildGender(3, this)" class="d-block p-2">
                        <?php echo $this->translate('January'); ?>
                    </a>
                </div>
            </div> <!-- year-range -->

            <div class="col-12 d-flex year p-0 d-none" id="year">
                <div class="col-2 mr-1 p-0 text-center d-flex align-items-center small not-to-answer add-gender-items">
                    <a href="javascript:void(0)" onclick="selectChildGender(3, this)" class="d-block p-2">
                        <?php echo $this->translate('January'); ?>
                    </a>
                </div>
            </div> <!-- year -->

        </div> <!-- add-birthday -->

    </div> <!-- end of add-child-form -->

    <div class="row m-0 add-another-child">
        <div class="col-12 d-flex justify-content-center add-child py-3 px-0">
            <div class="add-child-holder">
                <a href="javascript:void(0);" onclick="addChild(0)" class="text-muted" id="add-child-button">
                    <i class="fa fa-plus text-primary px-2"> </i>
                    <?php echo $this->translate('Add another child'); ?>
                </a>
            </div>
        </div>
    </div> <!-- end of add another child -->

</div> <!-- end of family-information -->

<script>

en4.core.runonce.add(function(){
    let familyHolder = document.getElementById('family-information');
    let familyWrapper = familyHolder.getParent();

    // add style modifications
    familyWrapper.style.minWidth = '600px';
    familyWrapper.style.width = '600px';
    familyWrapper.getParent().style.padding = '0';
    familyWrapper.getParent().style.margin = '2rem 0';

    // remove label
    familyWrapper.getParent().getChildren()[0].remove();

});

function addChild(type) {
    let form = document.getElementById('add-child-form');
    let actionButton = document.getElementById('add-child-button').getParent().getParent().getParent();
    (type == 1) ? form.classList.remove('active') : form.classList.add('active');
    (type == 1) ? actionButton.style.display = 'block' : actionButton.style.display = 'none';
}

function selectChildGender(type, e) {
    let addGenderItems = document.getElementsByClassName('add-gender-items');
    for(var i = 0; i < addGenderItems.length; i++) {
        addGenderItems[i].classList.remove('selected');
    }
    e.getParent().classList.add('selected');
}

</script>