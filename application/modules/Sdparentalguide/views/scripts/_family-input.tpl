<div id="<?php echo $this->name; ?>-wrapper" class="form-wrapper-heading" style="margin-bottom: 1rem;">
    <span class="field_container">
        <?php echo $this->translate('Tell us about your kids'); ?>
    </span>
</div>

<div class="container p-0 col-12 col-sm-12 family-members-box" id="family-information">
    
    <?php 

        $subject = $this->subject();
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        
        $select = $table->select()
            ->where('owner_id = ?', $subject->getIdentity())
            ->limit(10)
        ;

        $families = $table->fetchAll($select);

        (count($families) > 0) ? $last_item = end($families->toArray())['family_member_id'] : $last_item = 0;

    ?>
    <?php if(count($families) > 0): ?>
    <?php foreach($families as $item): ?>
        <div class="row d-flex align-items-center my-4 mx-0" id="<?php echo $item->getIdentity(); ?>">
            
            <div class="col-2 p-0 family-item-holder">
                <?php if(!$item->gender || $item->gender == 3): ?>
                <div class="unknown d-flex align-items-center justify-content-center text-white">
                    <?php echo $this->translate('X'); ?>
                </div>
                <?php elseif($item->gender == 1): ?>
                <div class="male d-flex align-items-center justify-content-center">
                    <svg id="male" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#fff"/></svg>
                </div>
                <?php elseif($item->gender == 2): ?>
                <div class="female d-flex align-items-center justify-content-center">
                    <svg id="1d586a36-69c9-43a9-b3c9-6e87bc4008b6" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"></path><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#fff"></path></svg>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-6 family-item p-0">
                <span class="text-muted">
                    <?php 
                        $date = strtotime( $item->dob );
                        echo $date = date( 'F Y', $date  );
                    ?>
                </span>
                <p class="desc text-muted small">
                    <?php if(!$item->gender || $item->gender == 3): ?>
                        <?php echo $this->translate("Unknown"); ?>
                    <?php elseif($item->gender == 1): ?>
                        <?php echo $this->translate("Male"); ?>
                    <?php elseif($item->gender == 2): ?>
                        <?php echo $this->translate("Female"); ?>
                    <?php endif; ?>
                </p>
            </div>

            <div class="col-4 p-0 actions d-flex justify-content-end">
                <a href="javascript:void(0)" onclick="editFamily(this)" class="btn btn-light small p-3 mr-1">
                    <?php echo $this->translate('Edit'); ?>
                </a>
                <a href="javascript:void(0)" onclick="removeFamily(this)" class="btn btn-danger text-white small p-3">
                    <?php echo $this->translate('Delete'); ?>
                </a>
            </div>

            <input type="hidden" name="family_<?php echo $item->getIdentity(); ?>[gender]" id="field-gender" value="<?php echo $item->gender; ?>">
            <input type="hidden" name="family_<?php echo $item->getIdentity(); ?>[birthday]" id="field-birthday" value="<?php echo $item->dob; ?>">

        </div> <!-- end of row -->
    <?php endforeach; ?>
    <?php endif; ?>

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
            <div class="col-3 col-xl-2 col-lg-2 mr-1 p-0 text-center male small add-gender-items add-gender-items-family">
                <a href="javascript:void(0)" onclick="selectChildGender(1)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="male" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Male'); ?>
                </a>
            </div>
            <div class="col-3 col-xl-2 col-lg-2 mr-1 p-0 p-0 text-center female small add-gender-items add-gender-items-family">
                <a href="javascript:void(0)" onclick="selectChildGender(2)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="female" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Female'); ?>
                </a>
            </div>
            <div class="col-3 col-xl-2 col-lg-2 mr-1 p-0 text-center d-flex align-items-center small not-to-answer add-gender-items add-gender-items-family">
                <a href="javascript:void(0)" onclick="selectChildGender(3)" class="d-block p-2">
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
            
            <div class="col-12 selector-holder p-0" id="months">
                <div class="col-12 p-0 d-flex"></div>
                <div class="col-12 p-0 d-flex"></div>
            </div> <!-- months -->

            <div class="col-12 years-range selector-holder p-0 d-none" id="years-range">
            </div> <!-- year-range -->

            <div class="col-12 years selector-holder p-0 d-none" id="years">
                <div class="col-12 selector-holder p-0 d-flex" id="years-first"></div>
                <div class="col-12 selector-holder p-0 d-flex" id="years-second"> </div>
            </div> <!-- years -->

            <div class="col-12 final-date selector-holder p-0 d-none" id="final-date">
            </div> <!-- final-date -->

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

var lastItem = <?php echo $last_item; ?>;

en4.core.runonce.add(function() {
    let familyHolder = document.getElementsByClassName('family-members-box')[0];
    let familyWrapper = familyHolder.getParent();
    
    // add style modifications
    familyWrapper.classList.add('family-wrapper');
    familyWrapper.style.minWidth = '600px';
    familyWrapper.style.width = '600px';
    familyWrapper.getParent().style.padding = '0';
    familyWrapper.getParent().style.margin = '2rem 0';

    // remove label
    familyWrapper.getParent().getChildren()[0].remove();

});

function getMonth(month) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return months[month];
}

function editFamily(el) {

    let memberHolder = el.getParent('div.row');
    let memberID = memberHolder.getAttribute('id');
    let gender = memberHolder.getElement('input').getAttribute('value');

    localStorage.setItem('update', memberID);

    selectChildGender(gender);
    addChild(0);
}

function removeFamily(el) {
    el.getParent('div.row').remove();
}

function addChild(type) {
    
    let form = document.getElementById('add-child-form');
    let actionButton = document.getElementById('add-child-button').getParent().getParent().getParent();
    (type == 1) ? form.classList.remove('active') : form.classList.add('active');
    (type == 1) ? actionButton.style.display = 'block' : actionButton.style.display = 'none';

    if(type == 0) {

        const monthsBirthday = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        let monthsHolder = document.getElementById('months').getChildren();
        monthsHolder[0].empty();
        monthsHolder[1].empty();

        for(var i = 0; i < monthsBirthday.length; i++) {
            new Element('div', {
                'class' : 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
                'html': '<a href="javascript:void(0)" onclick="selectMonth('+i+')" class="d-block px-3 py-4 w-100">'+monthsBirthday[i]+'</a>',
            }).inject( (i < 6) ? monthsHolder[0] : monthsHolder[1]  );
        }
    }
}

function selectChildGender(type) {
    let addGenderItems = document.getElementsByClassName('add-gender-items-family');
    for(var i = 0; i < addGenderItems.length; i++) {
        addGenderItems[i].classList.remove('selected');
        if(type == i) {
            addGenderItems[i - 1].classList.add('selected');
        }
    }
    setupFieldValue('gender', type);
}

function selectMonth(type, e) {

    const yearsBirthday = ['1970', '1980', '1990', '2000', '2010'];

    let yearsRange = document.getElementById('years-range');
    yearsRange.empty();
    
    new Element('div', {
        'class': 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
        'html': '<a href="javascript:void(0)" class="d-block px-3 py-4 w-100"> <i class="fa fa-arrow-left"> </i> </a>',
        'onclick': 'displayFamilySelector("month")',
    }).inject(yearsRange);

    for(var i = 0; i < yearsBirthday.length; i++) {
        new Element('div', {
            'class' : 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
            'html': '<a href="javascript:void(0)" onclick="selectYearRange('+yearsBirthday[i]+')" class="d-block px-3 py-4 w-100">'+yearsBirthday[i]+'</a>',
        }).inject( yearsRange  );
    }

    displayFamilySelector('years-range');
    setupFieldValue('month', type);
}

function selectYearRange(type, e) {

    let years = document.getElementById('years').getChildren();
    const monthsBirthday = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    // empty years
    years[0].empty();
    years[1].empty();

    new Element('div', {
        'class': 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
        'html': '<a href="javascript:void(0)" class="d-block px-3 py-4 w-100"> <i class="fa fa-arrow-left"> </i> </a>',
        'onclick': 'displayFamilySelector("years-range")',
    }).inject(years[0]);
    
    var date = new Date();
    date.setFullYear(type, 0, 1);
    var start_year = date.getFullYear();
    var count = 0;
    for( var i = start_year; i < start_year + 10; i++ ) {
        new Element('div', {
            'class' : 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
            'html': '<a href="javascript:void(0)" onclick="displayFinalDate('+i+')" class="d-block px-3 py-4 w-100">'+i+'</a>',
        }).inject( (count < 5) ? years[0] : years[1]  );
        count++;
    }

    displayFamilySelector('years');

}

function displayFinalDate(type, e) {

    setupFieldValue('final-year', type);

    let finalDateHolder = document.getElementById('final-date');
    finalDateHolder.empty();

    const monthsBirthday = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    new Element('div', {
        'class': 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
        'html': '<a href="javascript:void(0)" class="d-block px-3 py-4 w-100"> <i class="fa fa-arrow-left"> </i> </a>',
        'onclick': 'displayFamilySelector("years")',
    }).inject(finalDateHolder);

    new Element('div', {
        'class': 'mr-1 mt-2 p-0 text-center d-flex align-items-center small years-range-select selector-item',
        'html': '<a href="javascript:void(0)" onclick="setupFamilyMember()" class="d-block px-3 py-4 w-100"> '+ monthsBirthday[localStorage.getItem('month')] + ', ' + localStorage.getItem('final-year') +' </a>'
    }).inject(finalDateHolder);
    
    displayFamilySelector('final-date');
    
}

function setupFamilyMember() {

    console.log(localStorage.getItem('month'), localStorage.getItem('final-year'));
    
    let familyHolder = document.getElementsByClassName('family-members-box')[0];
    var genderImage;
    var genderType;
    var gender = localStorage.getItem('gender');
    var birthdayDate = getMonth(localStorage.getItem('month')) + ' ' + localStorage.getItem('final-year');
    var editMember;

    lastItem = lastItem + 1;

    var htmlInputFields = '<input type="hidden" name="family_'+ lastItem +'[gender]" id="field-gender" value="'+gender+'"><input type="hidden" name="family_'+ lastItem +'[birthday]" id="field-birthday" value="'+localStorage.getItem('final-year')+'-'+localStorage.getItem('month')+'">';

    if(gender == 3) {
        genderImage = '<div class="unknown d-flex align-items-center justify-content-center text-white">X</div>';
        genderType = '<?php echo $this->translate("Unknown"); ?>';
    } else if ( gender == '1' ) {
        genderImage = '<div class="male d-flex align-items-center justify-content-center"><svg id="male" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"></path><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#fff"></path></svg></div>';
        genderType = '<?php echo $this->translate("Male"); ?>';
    } else if ( gender == '2' ) {
        genderImage = '<div class="female d-flex align-items-center justify-content-center"><svg id="1d586a36-69c9-43a9-b3c9-6e87bc4008b6" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"></path><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#fff"></path></svg></div>';
        genderType = '<?php echo $this->translate("Female"); ?>';
    }

    console.log(localStorage.getItem('month'), localStorage.getItem('final-year'));

    editMember = '<div class="col-4 p-0 actions d-flex justify-content-end"><a href="javascript:void(0)" onclick="editFamily(this)" class="btn btn-light small p-3 mr-1">Edit</a><a href="javascript:void(0)" onclick="removeFamily(this)" class="btn btn-danger text-white small p-3">Delete</a></div>';

    let item = new Element('div', {
        'class': 'row d-flex align-items-center my-4 mx-0',
        'id': lastItem,
        'html': '<div class="col-2 p-0 family-item-holder">'+genderImage+'</div><div class="col-6 family-item p-0"> <span class="text-muted"> '+birthdayDate+' </span><p class="desc text-muted small">'+genderType+'</p>'+htmlInputFields+'</div>' + editMember
    });

    let updateItem = document.getElementById(localStorage.getItem('update'));
    if(updateItem) {
        updateItem.remove();
        localStorage.removeItem('update');
    }
        
    familyHolder.insertBefore(item, familyHolder.childNodes[0]);

    displayFamilySelector('month');
    
    // close
    addChild(1);

}

function setupFieldValue(type, value) {
    if (typeof(Storage) !== "undefined") {
        localStorage.setItem(type, value);
    }
}

function displayFamilySelector(type) {
    
    var monthsHolder = document.getElementById('months');
    var yearsRangeHolder = document.getElementById('years-range');
    var yearsHolder = document.getElementById('years');
    var finalDateHolder = document.getElementById('final-date');
    
    if(type == 'month') {
        yearsHolder.classList.remove('d-block');
        yearsHolder.classList.add('d-none');
        yearsRangeHolder.classList.remove('d-flex');
        yearsRangeHolder.classList.add('d-none');

        monthsHolder.classList.remove('d-none');
    }

    if(type == 'years-range') {
        monthsHolder.classList.add('d-none');
        yearsHolder.classList.remove('d-block');
        yearsHolder.classList.add('d-none');
        yearsRangeHolder.classList.remove('d-none');
        yearsRangeHolder.classList.add('d-flex');
    }

    if(type == 'years') {
        monthsHolder.classList.add('d-none');
        yearsRangeHolder.classList.remove('d-flex');
        yearsRangeHolder.classList.add('d-none');
        yearsHolder.classList.remove('d-none');
        yearsHolder.classList.add('d-block');
    }

    if(type == 'final-date') {

        finalDateHolder.classList.remove('d-none');
        finalDateHolder.classList.add('d-flex');

        yearsRangeHolder.classList.remove('d-flex');
        yearsRangeHolder.classList.add('d-none');

        yearsHolder.classList.remove('d-block');
        yearsHolder.classList.add('d-none');
    }

}

</script>